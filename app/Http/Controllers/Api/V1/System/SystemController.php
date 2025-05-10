<?php

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\System\CellRequest;
use App\Http\Requests\Api\V1\System\SystemRequest;
use App\Http\Resources\V1\System\CellDetailsResource;
use App\Http\Resources\V1\System\CellGeneralResource;
use App\Http\Resources\V1\System\CellResource;
use App\Http\Resources\V1\System\EnergyDayResource;
use App\Http\Resources\V1\System\FaultResource;
use App\Http\Resources\V1\System\SystemGeneralResource;
use App\Http\Resources\V1\System\SystemResource;
use App\Http\Services\Api\V1\System\SystemService;
use App\Http\Traits\Responser;
use App\Models\Cell;
use App\Models\System;
use App\Repository\SystemRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
    use Responser;

    public function __construct(
        private readonly SystemRepositoryInterface $systemRepository,
    )
    {
    }

    public function getSystemData($id, $cell_id)
    {
        $systemId = $id;
        $energiesPerHour = $this->getEnergyChartData($systemId);
        $powerChartData = $this->getPowerChartData($cell_id);
        $totalEnergyToday = $this->getTotalEnergyToday();
        $totalEnergyYesterday = $this->getTotalEnergyYesterday();
        $percentageChange = $this->getPercentageChange($totalEnergyToday, $totalEnergyYesterday);
        $response = [
            "energies_chart" => $energiesPerHour,
            "total_energy_today" => $totalEnergyToday,
            "precentage_change" => $percentageChange,
            'power_chart' => $powerChartData
        ];
        return $this->responseSuccess(data: $response);
    }

    public function getSystemHomeData($id)
    {

        $system = System::query()->where('id', $id)->with(['cells', 'powerPredictsToday'])->first();
        if(!$system)
            return $this->responseCustom(404,"NOT FOUND");
        $data = [
            'total_power' => $system->cells()?->sum('power') . " kw",
            'total_daily_generation' => $system->totalDailygeneration,
            'cells' => CellGeneralResource::collection($system->cells),
            'temperature' => $system->temperature,
            'system_temperature_label' => $system->system_temperature_label,
        ];
        return $this->responseSuccess(data: $data);
    }

    private function getEnergyChartData($systemId)
    {
        $date = Carbon::today();
        $energiesPerHour = DB::table('energies')
            ->select(
                DB::raw('HOUR(energies.created_at) as hour'),
                DB::raw('SUM(energy) as total_energy')
            )
            ->join('cells', 'energies.cell_id', '=', 'cells.id')
            ->where('cells.system_id', $systemId)
            ->whereDate('energies.created_at', $date)
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderBy('hour')
            ->get();
        return EnergyDayResource::collection($energiesPerHour);
    }

    private function getPowerChartData($cell_id)
    {
        $powerData = DB::table('power_predicteds')
            ->join('cells', 'power_predicteds.cell_id', '=', 'cells.id')
            ->selectRaw('
        DAYOFWEEK(power_predicteds.created_at) as day_of_week,
        SUM(power_actual) as total_actual_power,
        SUM(power_predicted) as total_predicted_power
    ')
            ->where('cells.id', $cell_id)
            ->whereBetween('power_predicteds.created_at', [
                Carbon::now()->subDays(6)->startOfDay(),
                Carbon::now()->endOfDay()
            ])
            ->groupBy(DB::raw('DAYOFWEEK(power_predicteds.created_at)'))
            ->orderBy('day_of_week')
            ->get()
            ->keyBy('day_of_week');

        $daysOfWeek = [
            1 => 'Sunday',
            2 => 'Monday',
            3 => 'Tuesday',
            4 => 'Wednesday',
            5 => 'Thursday',
            6 => 'Friday',
            7 => 'Saturday',
        ];

        $actual = [];
        $expected = [];

        foreach ($daysOfWeek as $index => $dayName) {
            $actual[] = [
                'x' => $dayName,
                'y' => isset($powerData[$index]) ? (float)$powerData[$index]->total_actual_power : 0.0
            ];

            $expected[] = [
                'x' => $dayName,
                'y' => isset($powerData[$index]) ? (float)$powerData[$index]->total_predicted_power : 0.0
            ];
        }
        return [
            'actual' => $actual,
            'expected' => $expected,
        ];
    }

    private function getTotalEnergyToday()
    {
        $today = Carbon::today();
        $totalEnergyToday = DB::table('energies')
            ->whereDate('created_at', $today)
            ->sum('energy');
        return $totalEnergyToday;
    }

    private function getPercentageChange($totalEnergyToday, $totalEnergyYesterday)
    {
        if ($totalEnergyYesterday > 0) {
            $percentageChange = (($totalEnergyToday - $totalEnergyYesterday) / $totalEnergyYesterday) * 100;
        } else {
            $percentageChange = $totalEnergyToday > 0 ? 100 : 0; // If no energy yesterday, consider a 100% increase if there is energy today
        }
        return $percentageChange;

    }

    private function getTotalEnergyYesterday()
    {
        $yesterday = Carbon::yesterday();

        $totalEnergyYesterday = DB::table('energies')
            ->whereDate('created_at', $yesterday)
            ->sum('energy');
        return $totalEnergyYesterday;
    }

    public function index()
    {
        $systems = auth('api')->user()?->load('systems')->systems;
        return $this->responseSuccess(data: SystemResource::collection($systems));
    }

    public function show($id)
    {
        $system = System::with(['cells.latestFault'])->find($id);
        if (!$system)
            return $this->responseFail();
        return $this->responseSuccess(data: SystemGeneralResource::make($system));
    }

    public function update($id, Request $request)
    {
        if ($request->minutes)
            System::query()->where('id', $id)->update(['next_clean_after' => $request->minutes]);
        return $this->responseSuccess(message: __('Updated Successfully'));
    }

    public function getCell($id)
    {
        $cell = Cell::with(['latestFaults', 'system'])->find($id);
        if (!$cell)
            return $this->responseFail();
        return $this->responseSuccess(data: CellDetailsResource::make($cell));
    }

    public function getCellFaults($id)
    {
        $faults = Cell::with(['poorFaults'])->find($id)->poorFaults;
        if (!$faults)
            return $this->responseFail();
        return $this->responseSuccess(data: FaultResource::collection($faults));
    }

    public function store(SystemRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $system = $this->systemRepository->create($data);
            DB::commit();
            return $this->responseSuccess(message: __('messages.created successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
//            dd($e);
            return $this->responseFail(message: __('messages.Something went wrong'));
        }
    }

    public function storeCell(CellRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $cell = Cell::query()->create($data);
            DB::commit();
            return $this->responseSuccess(message: __('messages.created successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
//            dd($e);
            return $this->responseFail(message: __('messages.Something went wrong'));
        }
    }

}
