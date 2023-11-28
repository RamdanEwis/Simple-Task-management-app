<?php

namespace App\Http\Resources\User;


use App\Http\Resources\User\TrainingCoursesResource;
use App\Constants\Media_Collections;
use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class UserResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */


    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
       ];
    }
}
