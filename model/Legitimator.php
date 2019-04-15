<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-12
 * Time: 13:04
 */

namespace model;

/**
 * Class Legitimator
 * @package model
 *
 * Classe qui a pour fonction de s'assurer que la requête HTTP est légitime
 * pour éviter l'injection de code
 */
class Legitimator {

    /**
     * @param $model Le nom du model à vérifier. Va assigner true à la propriété $isLegit si
     * le string correspond à un nom de model.
     * @return bool true si le string $model correspond à un nom de classe contenu dans le nameSpace,
     * false dans le cas contraire
     */
    public static function legitimate($model){
        return (file_exists(str_replace('\\', '\\\\',__DIR__ ) . "\\\\" . ucfirst($model) . ".php")) ? true : false;
    }
}