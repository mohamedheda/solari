<?php

namespace App\Models;

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
    public function getTemperatureTextAttribute()
    {
        return "The system temperature is {$this->temperature}°C";
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
}
