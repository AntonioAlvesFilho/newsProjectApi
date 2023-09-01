<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
			return [
				'id' => (integer)$this->id,	
				'label' => (string)$this->label,
				'todo_id' => (integer)$this->todo_id,
				'completed' => (boolean)$this->completed,
				'created_at' => (string)$this->created_at,
			];
    }
}
