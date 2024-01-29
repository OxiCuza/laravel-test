<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public $status;
    public $message;

    public function __construct($resource, $status = true, $message = null)
    {
        parent::__construct($resource);
        $this->status = $status;
        $this->message = $message;
    }

    public function with(Request $request)
    {
        return [
            "status" => $this->status,
            "message" => $this->message,
        ];
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "credit" => $this->credit,
        ];
    }
}
