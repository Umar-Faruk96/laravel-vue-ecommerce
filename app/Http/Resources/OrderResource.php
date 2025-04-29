<?php

namespace App\Http\Resources;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public static $wrap = false;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'items' => $this->items->map(fn($item) => [
                'id' => $item->id,
                'product' => [
                    'id' => $item->product->id,
                    'title' => $item->product->title,
                    'slug' => $item->product->slug,
                    'image' => $item->product->image,
                    'description' => $item->product->description,
                ],
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
            ]),
            'customer' => CustomerResource::make($this->user->customer),
            'created_at' => (new DateTime($this->created_at))->format('Y-m-d H:i:s'),
            'updated_at' => (new DateTime($this->updated_at))->format('Y-m-d H:i:s'),
        ];
    }
}