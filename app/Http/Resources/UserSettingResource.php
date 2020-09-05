<?php

namespace App\Http\Resources;

use App\Models\Api\Checkpoint;
use Illuminate\Http\Resources\Json\Resource;

class UserSettingResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $checkpoints = Checkpoint::getNumberOfLevels($this->uid);

        return [
            'nickname' => $this->nickname,
            'sex' => $this->sex,
            'hair' => $this->hair,
            'clothes' => $this->clothes,
            'revive_times' => $this->revive_times,
            'icon_settings' => $this->IconSettings,
            'related_name' => $this->related_name,
            'checkpoints' => $checkpoints
        ];
    }
}
