<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GigResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'timestamp_start' => $this->timestamp_start,
            'timestamp_end' =>  $this->timestamp_end,
            'number_of_positions' =>  $this->number_of_positions,
            'pay_per_hour' => $this->pay_per_hour,
            'posted' => $this->posted,
            'status' => $this->status,
            'company' => $this->company
        ];
    }
}
