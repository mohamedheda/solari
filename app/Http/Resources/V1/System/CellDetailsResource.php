<?php

namespace App\Http\Resources\V1\System;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CellDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ,
            'system_id' => $this->system_id ,
            'name' => $this->name ,
            'status' => $this->latestFault?->health_status ,
            'current' => $this->current ,
            'voltage' => $this->voltage ,
            'power' => $this->power ,
            'is_cleaning' => $this->system?->cleaning ,
            'today_energy' => $this->today_energy ,
            'faults' => FaultResource::collection($this->latestFaults),
            'water_level' => $this->system?->water_level ,
            'next_cleaning_time' => $this->system?->next_clean ,
        ];
    }
}
