<?php

namespace App\Constants;

final class Roles
{
    const SUPER_ADMIN = 1;

    const ADMIN =2;
    const EDITOR = 3;
    const EMPLOYER = 4;
    const USER = 5;

    public static function getAllRoles()
    {
        return [
            Roles::SUPER_ADMIN  => 'super_admin',
            Roles::ADMIN     => 'admin',
            Roles::EDITOR     => 'editor',
            Roles::EMPLOYER     => 'employer',
            Roles::USER     => 'user',
        ];
    }

    public static function getConstant($key = '')
    {
        return !array_key_exists($key, self::getAllRoles()) ? " " : self::getAllRoles()[$key];
    }
}
