<?php

namespace App\Http\Resources\RolePermission;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'guard_name' => $this->guard_name,
            'parent_id' => $this->parent_id,
        ];
        if (!empty($this->children)) {
            $result['children'] = $this->children;
        }
        return $result;
    }
}
