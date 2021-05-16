<?php

namespace App\GraphQL\Mutations;

use App\Models\Category;
use Illuminate\Support\Facades\Log;

class UpdateCategory
{
    /**
     * @param null $_
     * @param array<string, mixed> $args
     */
    public function __invoke($_, array $args)
    {
        $id = $args['id'];
        $data = $args['input'];
        $category = Category::find($id);
        $category->update($data);
        return $category;
    }
}
