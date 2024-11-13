<?php

namespace Domain;

class User
{
    public ?int $id = null;
    public string $username;
    public string $email;
    public string $password;
    public ?string $profileImage = null;
}