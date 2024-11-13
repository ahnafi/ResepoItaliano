<?php

namespace Model;

class UserPasswordRequest
{
    public ?int $userId = null;
    public ?string $oldPassword = null;
    public ?string $password = null;
    public ?string $password_confirmation = null;
}