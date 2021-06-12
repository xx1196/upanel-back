<?php

namespace Tests\Feature\Grocer;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useUserPassport('grocer');
    }

    public function testGetProducts(): void
    {
        $count = 10;
        $namesProductsDB = Product::all()->take($count)->pluck('name');

        $response = $this->graphQL(/** @lang GraphQL */ '
        query getProducts($page: Int!) {
        products(first: $page) {
            data {
                name
            }
        }
}
    ', [
            'page' => $count
        ]);

        $namesJSON = $response->json("data.products.data.*.name");

        $this->assertSame(
            $namesProductsDB->toArray(),
            $namesJSON
        );
    }

    public function testCreateProduct(): void
    {
        $count = 10;
        $code = $this->faker->uuid;
        $name = $this->faker->name;
        $price = $this->faker->randomFloat();
        $categoriesIds = Category::inRandomOrder()->limit($count)->get()->pluck('id')->toArray();

        $this->graphQL(/** @lang GraphQL */ '
        mutation createProduct($code: String!, $name: String!, $price: Float! $categories:[ID!]!) {
                createProduct(input: {
                    code: $code
                    name: $name
                    price: $price
                    categories: $categories
                }) {
                    name
                }
        }', [
            'code' => $code,
            'name' => $name,
            'price' => $price,
            'categories' => $categoriesIds,
        ])->assertJson([
            'data' => [
                'createProduct' => [
                    'name' => $name,
                ]
            ]
        ]);
    }

    public function testCanNotCreateProductsWhenHaveParametersVoid(): void
    {
        $count = 0;
        $code = '';
        $name = '';
        $price = 0;
        $categoriesIds = Category::inRandomOrder()->limit($count)->get()->pluck('id')->toArray();

        $this->graphQL(/** @lang GraphQL */ '
        mutation createProduct($code: String!, $name: String!, $price: Float! $categories:[ID!]!) {
                createProduct(input: {
                    code: $code
                    name: $name
                    price: $price
                    categories: $categories
                }) {
                    name
                }
        }', [
            'code' => $code,
            'name' => $name,
            'price' => $price,
            'categories' => $categoriesIds,
        ])->assertGraphQLValidationKeys([
            'input.code',
            'input.name',
        ]);
    }
}
