<?php

namespace Tests\Feature\Grocer;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useUserPassport('grocer');
    }

    public function testGetCategories(): void
    {
        $count = 10;
        $namesCategoriesDB = Category::all()->take($count)->pluck('name');

        $response = $this->graphQL(/** @lang GraphQL */ '
        query getCategories($page: Int!) {
        categories(first: $page) {
            data {
                id
                name
            }
        }
}
    ', [
            'page' => $count
        ]);

        $namesJSON = $response->json("data.categories.data.*.name");

        $this->assertSame(
            $namesCategoriesDB->toArray(),
            $namesJSON
        );
    }

    public function testCreateCategory(): void
    {
        $name = $this->faker->name;
        $description = $this->faker->sentence;

        $this->graphQL(/** @lang GraphQL */ '
        mutation createCategory($name: String! $description: String!) {
                createCategory(input: {
                    name: $name
                    description: $description
                }) {
                    name
                    description
                }
        }', [
            'name' => $name,
            'description' => $description,
        ])->assertJson([
            'data' => [
                'createCategory' => [
                    'name' => $name,
                    'description' => $description,
                ]
            ]
        ]);
    }

    public function testCanNotCreateCategoryWhenHaveParametersVoid(): void
    {
        $name = '';
        $description = '';

        $this->graphQL(/** @lang GraphQL */ '
        mutation createCategory($name: String! $description: String!) {
                createCategory(input: {
                    name: $name
                    description: $description
                }) {
                    name
                    description
                }
        }', [
            'name' => $name,
            'description' => $description,
        ])->assertGraphQLValidationKeys([
            'input.name',
            'input.description',
        ]);

    }
}
