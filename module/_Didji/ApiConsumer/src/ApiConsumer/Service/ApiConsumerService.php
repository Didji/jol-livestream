<?php
namespace ApiConsumer\Service;

class ApiConsumerService implements ApiConsumerServiceInterface
{
    public function __constructor($type = '')
    {
        switch ($type) {
            case 'twitch':
                return new TwitchService();
                break;
            case 'hitbox':
                return new HitboxService();
                break;
            default:
                throw new \Exception("La plateforme " . ucfirst($type) . " n'est pas supportée");
                break;
        }
    }

    /**
    * Construit un objet CURL et requête l'URL passée en paramètre, si elle est valide
    *
    * @param String $url URL à requêter
    *
    * @return Array Le résultat de la requête
    * @throws Exception Si l'URL est invalide
    */
    private function executeQuery($url = '')
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new \Exception("L'URL fournie est incorrecte");
            
        }

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_CAINFO, '/config/cacert.pem');

        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_STDERR, $verbose);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            $exception = new \Exception('Curl Exception : ' . curl_errno($curl) . ' - ' . htmlspecialchars(curl_error($curl)));
            curl_close($curl);
            throw $exception;
        }
           
        curl_close($curl);

        return json_decode($result, true);
    }
}
