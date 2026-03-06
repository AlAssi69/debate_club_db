<?php

namespace App\Enums;

enum SessionRole: string
{
    case Trainer = 'trainer';
    case Trainee = 'trainee';
}
