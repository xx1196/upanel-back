<?php

namespace App\GraphQL\Mutations;

use App\Models\Category;

class CreateCategory
{
    /**
     * @param null $_
     * @param array<string, mixed> $args
     */
    public function __invoke($_, array $args)
    {
        $data = $args['input'];
        return Category::create($data);
    }
}
