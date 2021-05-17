<?php

namespace App\GraphQL\Queries;

class Me
{
    /**
     * @param null $_
     * @param array<string, mixed> $args
     */
    public function __invoke($_, array $args): array
    {
        $user = auth()->user();
        $permissions = $user->getAllPermissions();
        return compact('user', 'permissions');
    }
}
