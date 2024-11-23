<?php

namespace Model;

class UserSearchParams
{
    public ?string $username = null;
    public ?string $email = null;
    public ?string $role = null;
    public int $page = 1;
}