<?php

namespace App\Http\Resources\Administrator;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AdministratorResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'list' => $this->collection,
            'total' => $this->total(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
        ];
    }
}
