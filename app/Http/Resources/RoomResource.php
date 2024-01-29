<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => $this->location,
            'price' => $this->price,
            'image' => $this->image,
            'created_at' => $this->created_at,
            'owner' => new UserResource($this->whenLoaded('owner')),
            'facilities' => new RoomDetailCollection($this->whenLoaded('details')),
            'discussions' => new RoomDiscussionCollection($this->whenLoaded('discussions')),
        ];
    }
}
