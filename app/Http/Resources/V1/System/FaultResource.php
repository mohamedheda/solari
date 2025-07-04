<?php

namespace App\Http\Resources\V1\System;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'desc' => $this->desc,
            'time' => Carbon::parse($this->created_at)->format('h:i A'),
            'date' => Carbon::parse($this->created_at)->format('j-n-y'),
        ];
    }
}
