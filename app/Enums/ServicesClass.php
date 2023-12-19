<?php

namespace App\Enums;

use \Spatie\Enum\Enum;

/**
 * @method static self Exploitation()
 * @method static self Reseaux()
 * @method static self Etudes()
 */
class ServicesClass extends Enum
{
    // protected static function values()
    // {
    //     return function(string $name): string|int {

    //         $traductions = array(
    //             "Administration_Reseaux" => "Administration & Réseaux",
    //         );
    //         return strtr(str_replace("_", ": ", str($name)), $traductions);;
    //     };
    // }
}