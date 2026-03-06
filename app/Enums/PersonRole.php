<?php

namespace App\Enums;

enum PersonRole: string
{
    case Admin = 'admin';
    case Trainer = 'trainer';
    case Member = 'member';
    case Beneficiary = 'beneficiary';
}
