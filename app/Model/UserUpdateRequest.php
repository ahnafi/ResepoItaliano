<?php

namespace Model;

class UserUpdateRequest
{
    public ?int $userId = null;
    public ?string $username = null;
    public ?array $photo = null;
}