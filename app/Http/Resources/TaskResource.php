<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'attachments' => $this->attachments->map(function ($attachment) {
                return [
                    'id' => $attachment->id,
                    'path' => "storage/" . $attachment->path,
                    'created_at' => Carbon::parse($attachment->created_at)->format('d/m/Y'),
                    'updated_at' =>  Carbon::parse($attachment->updated_at)->format('d/m/Y'),
                ];
            }),
            'date_done' => $this->date_done != null ? Carbon::parse($this->date_done)->format('d/m/Y') : null,
            'deleted_at' => $this->deleted_at != null ? Carbon::parse($this->deleted_at)->format('d/m/Y') : null,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y'),
            'updated_at' =>  Carbon::parse($this->updated_at)->format('d/m/Y'),
        ];
    }
}
