<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-12
 * Time: 13:04
 */

namespace model;

use DateTime;

/**
 * Class Legitimator
 * @package model
 *
 * Classe qui a pour fonction de s'assurer que la requête HTTP est légitime
 * pour éviter l'injection de code
 */
class Legitimator
{

    /**
     * @param $model Le nom du model à vérifier. Va assigner true à la propriété $isLegit si
     * le string correspond à un nom de model.
     * @return bool true si le string $model correspond à un nom de classe contenu dans le nameSpace,
     * false dans le cas contraire
     */
    public static function legitimate($model, $path)
    {
        return (file_exists(str_replace('\\', '\\\\', $path) . "\\\\" . ucfirst($model) . ".php")) ? true : false;
    }

    //TODO probablement pas utile finalement puisque la terchnique pour vérifier si c'est une date a changé
    /**
     * Permet de savoir si un String correspond ou non à une date SQL
     *
     * @param $aString Le String à vérifier
     * @param $format À définir quand on appelle la fonction ($format = "Y-m-Y h:m:s", par exemple)
     * @return bool Retourne true si $aString correspond à un format de Date ou de DateTime
     */
    public static function isStringADate($aString){
        $format = 'Y-m-d H:m:s';
        $d = DateTime::createFromFormat($format, $aString);
        return $d && $d->format($format) === $aString;
    }

}