<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ArchiveResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'archive_id' => $this->id,
            'master_id' => $this->master_id,
            'created_at' => $this->created_at,
            'sex' => $this->baseSetting ? $this->baseSetting->sex : 0
        ];
    }
}