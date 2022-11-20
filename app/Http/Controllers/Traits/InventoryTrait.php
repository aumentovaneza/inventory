<?php

namespace App\Http\Controllers\Traits;

trait InventoryTrait
{

    public function getTotalProductInventory($product)
    {
        $application = 0;
        $purchase = 0;

        foreach ($product->variants as $variant) {
            $variantPurchase = 0;
            $variantApplication = 0;

            foreach ($variant->inventories as $inventory) {
                if ($inventory->type === self::PURCHASE) {
                    $variantPurchase += $inventory->quantity;
                }

                if ($inventory->type === self::APPLICATION) {
                    $variantApplication += $inventory->quantity;
                }
            }

            $application += $variantApplication;
            $purchase += $variantPurchase;
        }

        return $purchase - $application;
    }

    public function getTotalVariantInventory($variant)
    {
        $application = 0;
        $purchase = 0;

        foreach ($variant->inventories as $inventory) {
            if($inventory->type === self::PURCHASE) {
                $purchase += $inventory->quantity;
            }

            if($inventory->type === self::APPLICATION) {
                $application += $inventory->quantity;
            }
        }
        return $purchase - $application;
    }
}
