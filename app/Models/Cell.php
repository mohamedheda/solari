<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cell extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function getTodayEnergyAttribute()
    {
        return $this->energies()
            ->whereDate('created_at', Carbon::today())
            ->sum('energy') .' kwh';
    }
    public function getCurrentAttribute()
    {
        return $this->attributes['current'] . ' A';
    }

    public function getVoltageAttribute()
    {
        return $this->attributes['voltage'] . ' V';
    }

    public function getPowerAttribute()
    {
        return $this->attributes['power'] . ' kw';
    }
    public function system(){
        return $this->belongsTo(System::class);
    }
    public function faults(){
        return $this->hasMany(Fault::class);
    }
    public function latestFaults(){
        return $this->faults()->whereNot('value',0)->latest()->limit(5);
    }
    public function poorFaults(){
        return $this->faults()->whereNot('value',0)->latest();
    }
    public function energies(){
        return $this->hasMany(Energy::class);
    }
    public function latestFault()
    {
        return $this->hasOne(Fault::class)->latestOfMany();
    }
}
