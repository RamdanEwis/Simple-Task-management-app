<?php

namespace App\Constants;

use NunoMaduro\Collision\Adapters\Phpunit\State;
use PhpParser\Node\Stmt\Static_;

final class UserTypes {

    const ADMIN =  0;
    const VENDOR = 1;
    const USER = 2;


    public Static function getUserTypes()
     {

        return [

            UserTypes::ADMIN => 'admin',

            UserTypes::VENDOR => 'vendor',

            UserTypes::USER => 'user',

        ];
    }

}
