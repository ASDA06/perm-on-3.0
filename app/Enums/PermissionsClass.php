<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/** //=================================================

 * //==================================================
 * ENGINES
 * @method static self permanences_create()
 * @method static self permanences_read()
 * @method static self permanences_update()
 * @method static self permanences_delete()
 * //==================================================
 * REPARATIONS
 * @method static self utilisateurs_create()
 * @method static self utilisateurs_read()
 * @method static self utilisateurs_update()
 * @method static self utilisateurs_delete()
 * // =================================================
 * DEPARTEMENTS
 * @method static self departements_create()
 * @method static self departements_read()
 * @method static self departements_update()
 * @method static self departements_delete()
 * // =================================================
 * MARQUES  
 * @method static self services_create()
 * @method static self services_read()
 * @method static self services_update()
 * @method static self services_delete()
 * // =================================================
 * PERMISSIONS
 * @method static self Permissions_read()
 * // =================================================
 * USERS
 * @method static self roles_create()
 * @method static self roles_read()
 * @method static self roles_update()
 * @method static self roles_delete()
 * // =================================================
 * PRESENCES
 * @method static self presences_create()
 * @method static self presences_read()
 * @method static self presences_update()
 * @method static self presences_delete()
 * // =================================================
 * PRESENCES
 * @method static self presences_create()
 * @method static self presences_read()
 * @method static self presences_update()
 * @method static self presences_delete()
 * // =================================================
 */

class PermissionsClass extends Enum
{

    protected static function values()
    {
        return function(string $name): string|int {

            $traductions = array(
                "create" => "ajouter",
                "read" => "voir",
                "update" => "modifier",
                "delete" => "supprimer",
                "services" => "Services",
                "parmanences" => "Permanence",
                "Departements" => "Départements",
                "utilisateurs" => "Utilisateurs",
            );
            return strtr(str_replace("_", ": ", str($name)), $traductions);;
        };
    }
}