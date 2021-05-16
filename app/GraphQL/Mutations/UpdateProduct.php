<?php

namespace App\GraphQL\Mutations;

use App\Models\Product;
use Illuminate\Support\Facades\Log;

class UpdateProduct
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $id = $args['id'];
        $data = $args['input'];
        $product = Product::find($id);
        $product->update($data);
        $product->categories()->sync($data['categories']);
        return $product;
    }
}
