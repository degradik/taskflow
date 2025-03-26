<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'title' => $this->title,
            "owner_id" => $this->owner_id,
            "description" => $this->description,
            "status" => $this->status,
            "deadline" => $this->deadline,
        ];
    }
}
