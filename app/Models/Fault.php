<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fault extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function getTitleAttribute()
    {
        return match ($this->value) {
            1 => 'Inverter fault',
            2 => 'Feedback Sensor fault',
            3 => 'Grid anomaly',
            4 => 'PV array mismatch',
            5 => 'PV array mismatch',
            6 => 'MPPT/IPPT controller fault',
            7 => 'Boost converter controller fault',
            default => 'No fault',
        };
    }

    public function getDescAttribute()
    {
        return match ($this->value) {
            1 => 'Complete failure in one of the six IGBTs',
            2 => 'One phase sensor fault 20%',
            3 => 'Intermittent voltage sags',
            4 => '10 to 20% nonhomogeneous partial shading',
            5 => '15% open circuit in PV array',
            6 => '-20% gain parameter of PI controller in MPPT/IPPT controller of the boost converter',
            7 => '+20% in time constant parameter of PI controller in MPPT/IPPT controller of the boost converter',
            default => 'System operating normally',
        };
    }

    public function getHealthStatusAttribute()
    {
        return match ($this->value) {
            0 => 'Healthy',
            1, 2, 3 => 'Good',
            4, 5, 6, 7 => 'Poor',
            default => 'Unknown',
        };
    }
}
