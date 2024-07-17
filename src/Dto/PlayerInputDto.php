<?php

namespace App\Dto;

use App\Enum\Gender;
use Symfony\Component\Serializer\Annotation\Groups;

class PlayerInputDto {
    public string $name;
    public string $lastname;
    public Gender $gender;
    public int $power = 50;
    public int $speed = 50;
    public int $reaction = 50;
}