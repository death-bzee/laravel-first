<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'host' => $this->properties['ip'] ?? 'неизвестно',
            'event' => $this->description,
            'timestamp' => $this->created_at->toDateTimeString(),
            'user' => $this->causer?->email ?? 'Система',
        ];
    }

}
