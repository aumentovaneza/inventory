<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource
{
    const PURCHASE = 'Purchase';
    const APPLICATION = 'Application';
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'price' => $this->price,
            'quantity' => $this->calculateTotalQty(),
            'inventory' => ProductMovementResource::collection($this->inventories),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }

    private function calculateTotalQty()
    {
        $application = 0;
        $purchase = 0;

        foreach ($this->inventories as $inventory) {
            if($inventory->type === self::PURCHASE) {
                $purchase += $inventory->quantity;
            }

            if($inventory->type === self::APPLICATION) {
                $application += $inventory->quantity;
            }
        }

        $difference = $purchase - $application;

        return max($difference, 0);
    }

}
