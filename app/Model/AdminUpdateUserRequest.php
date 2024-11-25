<?php

namespace Model;

class AdminUpdateUserRequest
{
    public ?int $adminId = null;
    public ?int $userId = null;
    public ?string $username = null;
    public ?string $password = null;
    public ?array $profileImage = null;
}