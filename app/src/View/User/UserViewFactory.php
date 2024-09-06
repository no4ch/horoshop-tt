<?php

declare(strict_types=1);

namespace App\View\User;

use App\Entity\User;

class UserViewFactory
{
    public function create(User $user): UserView
    {
        return new UserView(
            $user,
        );
    }
}
