<?php

namespace App\Domains\Poidu\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "category" => new CategoryResource($this->category),
            "title" => $this->title,
            "description" => $this->description,
            "date_start" => $this->date_start,
            "time_start" => $this->time_start,
            "price_min" => $this->price_min,
            "price_max" => $this->price_max,
            // "channel" => $this->channel,
            // "channel_id" => $this->channel_id,
            // "post_id" => $this->post_id,
            "link_to_post" => $this->link_to_post,
            // "post_was_created" => $this->post_was_created,
            // "human" => $this->human,
            // "approved" => $this->approved,
            // "created_at" => $this->created_at,
            // "updated_at" => $this->updated_at,
        ];
    }
}
