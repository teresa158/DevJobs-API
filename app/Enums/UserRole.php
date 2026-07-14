<?php

namespace App\Enums;

enum UserRole: string
{
    case Candidate = 'candidate';
    case Company   = 'company';
    case Admin     = 'admin';
}
