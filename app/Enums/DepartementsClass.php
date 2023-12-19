<?php

namespace App\Enums;

use \Spatie\Enum\Enum;

/**
 * @method static self Cellule_informatique()
 * @method static self DEC()
 * @method static self DCR()
 */
class DepartementsClass extends Enum
{
    protected static function values()
    {
        return function(string $name): string|int {

            $traductions = array(
                "DEC" => "Division Epargne et Crédit",
            );
            return strtr(str_replace("_", " ", str($name)), $traductions);;
        };
    }
}