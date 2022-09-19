<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "id_sender" => $this->id_sender,
            "id_recever" => $this->id_recever,
            "montant" => $this->montant,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
