<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function cells(){
        return $this->hasMany(Cell::class);
    }
    public function getActiveCellsCountAttribute()
    {
        return  $this->activeCells()->count() ." panels are active";
    }
    public function getSystemTemperatureLabelAttribute(): string
    {
        $temp = $this->system_temperature;

        if ($temp <= 40) {
            return 'Normal';
        } elseif ($temp <= 70) {
            return 'High';
        } else {
            return 'High Intense';
        }
    }
    public function getTemperatureTextAttribute()
    {
        return "The system temperature is {$this->temperature}°C";
    }
    public function totalDailygeneration():Attribute {
        return Attribute::get(fn()=> $this->powerPredictsToday()?->sum('power_actual') ." Kw/ " .$this->powerPredictsToday()?->sum('power_predicted')." Kw");
    }
    public function getTrackingStatusTextAttribute()
    {
        return $this->tracking_system_working
            ? "Solar tracking system is activated"
            : "Solar tracking system is deactivated";
    }
    public function activeCells()
    {
        return $this->hasMany(Cell::class)->whereHas('latestFault', function ($query) {
            $query->where('value', 0);
        });
    }
    public function powerPredictsToday(){
        return $this->hasManyThrough(PowerPredicted::class,Cell::class)->whereDate('power_predicteds.created_at','=', Carbon::now()->format('Y-m-d'));
    }
}
