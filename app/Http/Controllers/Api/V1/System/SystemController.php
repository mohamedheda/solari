<?php

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\System\CellRequest;
use App\Http\Requests\Api\V1\System\SystemRequest;
use App\Http\Resources\V1\System\CellDetailsResource;
use App\Http\Resources\V1\System\FaultResource;
use App\Http\Resources\V1\System\SystemGeneralResource;
use App\Http\Resources\V1\System\SystemResource;
use App\Http\Services\Api\V1\System\SystemService;
use App\Http\Traits\Responser;
use App\Models\Cell;
use App\Models\System;
use App\Repository\SystemRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
    use Responser;
    public function __construct(
        private readonly SystemRepositoryInterface $systemRepository ,
    )
    {
    }
    public function index(){
        $systems=auth('api')->user()?->load('systems')->systems;
        return $this->responseSuccess(data: SystemResource::collection($systems));
    }
    public function show($id){
        $system=System::with(['cells.latestFault'])->find($id);
        if (!$system)
            return $this->responseFail();
        return $this->responseSuccess(data: SystemGeneralResource::make($system));
    }
    public function update($id,Request $request){
        if ($request->minutes)
            System::query()->where('id',$id)->update(['next_clean_after' => $request->minutes]);
        return $this->responseSuccess(message: __('Updated Successfully'));
    }
    public function getCell($id){
        $cell=Cell::with(['latestFaults','system'])->find($id);
        if (!$cell)
            return $this->responseFail();
        return $this->responseSuccess(data: CellDetailsResource::make($cell));
    }
    public function getCellFaults($id){
        $faults=Cell::with(['poorFaults'])->find($id)->poorFaults;
        if (!$faults)
            return $this->responseFail();
        return $this->responseSuccess(data: FaultResource::collection($faults));
    }
    public function store(SystemRequest $request){
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
    public function storeCell(CellRequest $request){
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
