<?php

 namespace App\Constants;

 final class PromoTypes{
     const GENERIC = 1;
     const EXCLUSIVE = 2;
     const ASSOCIATE = 3;

     public static function getPromoTypes()
     {
         return [
            PromoTypes::GENERIC => 'Generic',
            PromoTypes::EXCLUSIVE => 'Exclusive',
            PromoTypes::ASSOCIATE => 'Associate',
         ];
     }

     public static function getPromoType($key = '')
     {
         return !array_key_exists($key, self::getPromoTypes()) ?
          " " : self::getPromoTypes()[$key];
     }
 }
