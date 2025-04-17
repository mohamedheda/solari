<?php

namespace App\Http\Resources\V1\System;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SystemGeneralResource extends JsonResource
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
            'active_cells' => $this->active_cells_count ,
            'temperature' => $this->temperature_text ,
            'tracking_status' => $this->tracking_status_text ,
            'cells' => CellResource::collection($this->cells) ,
         ];
    }
}
