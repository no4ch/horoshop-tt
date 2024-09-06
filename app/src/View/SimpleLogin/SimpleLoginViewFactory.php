<?php

declare(strict_types=1);

namespace App\View\SimpleLogin;

readonly class SimpleLoginViewFactory
{
    public function create(string $token): SimpleLoginView
    {
        return new SimpleLoginView(
            $token,
        );
    }
}
