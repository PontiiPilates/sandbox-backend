<?php

namespace App\Domains\Parsing\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExtractTgEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'peer' => $this->peer,
            'peer_id' => $this->peer_id,
            'post' => $this->post,
            'post_id' => $this->post_id,
            'date' => $this->date,
            'message' => $this->message,
            'source' => $this->source,
        ];
    }
}
