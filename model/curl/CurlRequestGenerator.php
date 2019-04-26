<?php
/**
 * Created by PhpStorm.
 * User: UmbrellaCorpAgent
 * Date: 2019-04-25
 * Time: 14:52
 */

/**
 * Class RequestType Classe qui a pour fonction de baliser les types de requêtes HTTP
 */
class RequestType{
    static $GET = "GET";
    static $POST = "POST";
    static $PUT = "PUT";
    static $DELETE = "DELETE";
}

class CurlRequestGenerator
{

    private $requestType;
    private $requestData;
    private $url = "localhost/";

    /**
     * CurlRequestGenerator constructor.
     * @param String $requestType Correspond à l'une des valeur de la classe RequestType ('GET', 'POST', 'PUT', 'DELETE').
     *        Utiliser les éléments de la classe RequestType pour assurer que seules les requêtes souhaitées soient
     *        utilisées.
     * @param CurlRequestData $requestData Un object contenant un nom de model, un id (optionnel) et un tableau de datas.
     *        Cet object sera utile pour contruire l'URL pour procéder à la requête HTML sur l'API.
     */
    public function __construct(string $requestType, CurlRequestData $requestData)
    {
        $this->requestType = $requestType;
        $this->requestData = $requestData;
    }

    /**
     * Sert à appeler la méthode qui va générer la requête automatiquement
     * @return mixed|string Les résultats de la requête HTTP
     */
    public function curlRequest()
    {
        $this->url = $this->url . $this->requestData->getModel();
        if ($this->requestData->getId()) {
            $this->url = $this->url . "/" . $this->requestData->getId();
        }
        return self::generateRequest(curl_init($this->url), $this->requestType, $this->requestData->getDatas());
    }

    /**
     * Procède à la construction et l'exécution de la requête HTTP sur la base des éléments fourni dans la propriété
     * $requestData
     * @param $ch La session cURL
     * @param $requestType Le type de requête ('GET', 'POST', 'PUT', 'DELETE')
     * @param $datas Les informations pertinentes pour procéder aux modifications ou à l'ajout dans l'API. Peut être
     *        null si la requête est 'GET' ou 'DELETE'
     * @return mixed|string Les résultats de la requête
     */
    private function generateRequest($ch, $requestType, $datas)
    {
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestType);
        if ($datas) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($datas));
        }
        $info = curl_exec($ch);
        if (curl_errno($ch)) {
            $info = "Aucune information trouvée";
        }
        curl_close($ch);
        return $info;
    }
}