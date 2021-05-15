<?php

namespace App\GraphQL\Mutations;

use App\Models\Product;

class CreateProduct
{
    /**
     * @param null $_
     * @param array<string, mixed> $args
     */
    public function __invoke($_, array $args)
    {
        $data = $args['input'];
        $categoriesIds = $data['categories'];
        $product = Product::create($args['input']);
        $product->categories()->attach($categoriesIds);
        return $product;
    }
}
