<?php

namespace App\Enums;

class UserTripRoles
{
    const TRAVELLER = 0;
    const ORGANISER = 1;
    const OWNER     = 2;

    public static function getString(int $id) {
        switch ($id) {
            case 0: return "traveller";
            case 1: return "organiser";
            case 2: return "owner";
            default: return "unknown";
        }
    }
}