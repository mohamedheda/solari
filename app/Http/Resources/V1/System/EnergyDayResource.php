<?php

namespace App\Http\Resources\V1\System;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnergyDayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'hour' => (float) $this->hour ,
            'total_energy' => (float) $this->total_energy ,
        ];
    }
}
