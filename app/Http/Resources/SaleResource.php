<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
        'id' => $this->id,
        'total_amount' => $this->total_amount,
         //Include the name and role of the user who made this sale
        'name' => $this->user->name,
        'role' => $this->user->role,
         //Include all sale items for this sale, and also include each item's product name, quantity, price, and subtotal
        'items' => $this->saleItems->map(function ($item) {
            return [
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->subtotal,
            ];
        }),
    ];
    }
}
