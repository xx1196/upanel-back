<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase
{

    public function testAdminCanLogin()
    {
        $this->useUserPassport();

        $this->graphQL(/** @lang GraphQL */ '{
            me{
                user {
                    id
                    email
                }
            }
        }')->assertJson([
            'data' => [
                'me' => [
                    'user' => [
                        "id" => $this->userAdmin->id,
                        "email" => $this->userAdmin->email
                    ]
                ]
            ]
        ]);
    }

    public function testSellerCanLogin()
    {
        $this->useUserPassport('seller');

        $this->graphQL(/** @lang GraphQL */ '{
            me{
                user {
                    id
                    email
                }
            }
        }')->assertJson([
            'data' => [
                'me' => [
                    'user' => [
                        "id" => $this->userSeller->id,
                        "email" => $this->userSeller->email
                    ]
                ]
            ]
        ]);
    }

    public function testGrocerCanLogin()
    {
        $this->useUserPassport('grocer');

        $this->graphQL(/** @lang GraphQL */ '{
            me{
                user {
                    id
                    email
                }
            }
        }')->assertJson([
            'data' => [
                'me' => [
                    'user' => [
                        "id" => $this->userGrocer->id,
                        "email" => $this->userGrocer->email
                    ]
                ]
            ]
        ]);
    }

    public function testGetRolesAdmin()
    {
        $this->useUserPassport();

        $response = $this->graphQL(/** @lang GraphQL */ '{
            me {
                permissions {
                    name
                }
            }
        }');

        $namesPermissionsJSON = $response->json("data.me.permissions.*.name");

        $permissionsUserAdminDB = $this->userAdmin->getAllPermissions()->pluck('name');

        $this->assertSame(
            $permissionsUserAdminDB->toArray(),
            $namesPermissionsJSON
        );

    }

    public function testGetRolesSeller()
    {
        $this->useUserPassport('seller');

        $response = $this->graphQL(/** @lang GraphQL */ '{
            me {
                permissions {
                    name
                }
            }
        }');

        $namesPermissionsJSON = $response->json("data.me.permissions.*.name");

        $permissionsUserAdminDB = $this->userSeller->getAllPermissions()->pluck('name');

        $this->assertSame(
            $permissionsUserAdminDB->toArray(),
            $namesPermissionsJSON
        );

    }

    public function testGetRolesGrocer()
    {
        $this->useUserPassport('grocer');

        $response = $this->graphQL(/** @lang GraphQL */ '{
            me {
                permissions {
                    name
                }
            }
        }');

        $namesPermissionsJSON = $response->json("data.me.permissions.*.name");

        $permissionsUserAdminDB = $this->userGrocer->getAllPermissions()->pluck('name');

        $this->assertSame(
            $permissionsUserAdminDB->toArray(),
            $namesPermissionsJSON
        );

    }
}
