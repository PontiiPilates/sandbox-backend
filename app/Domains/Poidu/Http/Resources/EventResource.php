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
            "time_start" => $this->time_start == '00:00' ? 'Уточняйте у организатора' : $this->time_start,
            "price_min" => $this->price_min,
            "price_max" => $this->price_max,
            "link_to_post" => $this->link_to_post,
            "is_active" => $this->isActive,
            "views" => $this->views,
            "approved" => $this->approved,
            "preview" => $this->preview
                ? url('/storage/previews/' . $this->preview)
                : url('/storage/previews/default/' . 'summer:camping:company:flame.jpg'),
        ];
    }
}
