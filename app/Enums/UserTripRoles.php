<?php

namespace App\Enums;

/**
 * Enum UserTripRoles
 * @package App\Enums
 * @author Martin Brom
 */
class UserTripRoles
{
    const TRAVELLER = 0;
    const ORGANISER = 1;
    const OWNER     = 2;

    /**
     * @param int $id
     * @return string
     */
    public static function getString(int $id) {
        switch ($id) {
            case 0: return "traveller";
            case 1: return "organiser";
            case 2: return "owner";
            default: return "unknown";
        }
    }
}