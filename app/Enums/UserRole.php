<?php

namespace App\Enums;

enum UserRole: string{
    case Creator = 'creator';
    case Redactor = 'redactor';
    case Viewer = 'viewer';
}