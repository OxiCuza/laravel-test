<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceResponse;

class ErrorResource extends JsonResource
{
    public $statusCode;

    public static $wrap = null;

    public function __construct($resource, $statusCode = 400)
    {
        parent::__construct($resource);
        $this->statusCode = $statusCode;
    }

    public function toResponse($request)
    {
        return (new ResourceResponse($this))
            ->toResponse($request)
            ->setStatusCode($this->statusCode);
    }
}
