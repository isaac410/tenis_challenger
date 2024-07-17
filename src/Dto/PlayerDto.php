<?php

namespace App\Dto;

use App\Enum\Gender;
use Symfony\Component\Serializer\Annotation\Groups;

class PlayerDto {

    #[Groups(["user", "admin"])]
    public int $id;

    #[Groups(["user", "admin"])]
    public string $name;

    #[Groups(["user", "admin"])]
    public string $lastname;

    #[Groups(["user", "admin"])]
    public Gender $gender;

    #[Groups(["user", "admin"])]
    public int $power = 50;

    #[Groups(["user", "admin"])]
    public int $speed = 50;

    #[Groups(["user", "admin"])]
    public int $reaction = 50;

    #[Groups(["admin"])]
    public string $createdAt;

    #[Groups(["admin"])]
    public string $updatedAt;
}