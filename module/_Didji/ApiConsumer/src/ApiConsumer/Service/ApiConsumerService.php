<?php
namespace ApiConsumer\Service;

abstract class ApiConsumerService implements ApiConsumerServiceInterface
{
    /**
    * On construit directement une instance correspondante à la plateforme passée en paramètre
    *
    * @param String $type La plateforme à utiliser (Twitch, Hitbox, ...)
    *
    * @return mixed Une instance pouvant se connecter à la plateforme
    */
    public static function getApiConsumer(string $type = null)
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
    protected function executeQuery(string $url = null)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new \Exception("L'URL fournie est incorrecte");
        }

        // Initialisation et paramétrage de la connexion curl :
        //     - URL à requêter
        //     - On retourne directement ce que l'URL renvoie, et pas le statut de la connexion (true si réussi, false sinon)
        //     - Délai maximal d'attente que le serveur réponde. Passé ce délai, la connexion est fermée
        //     - Force la vérification du certificat
        //     - Force la vérification de la correspondance entre le serveur et le certificat
        //     - Chemin vers le certificat
        //     - Force la génération complète de logs
        //     - Détermine dans quel fichier les logs seront stockés
        $verbose = fopen('/data/logs/curl_error.log', 'w+');
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_CAINFO, '/config/cacert.pem');

        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_STDERR, $verbose);

        // Exécution
        $result = curl_exec($curl);

        // Si erreur, fermeture de la connexion et exception
        // (les logs seront dans le fichier défini dans la variable $verbose)
        if (curl_errno($curl)) {
            $exception = new \Exception('Curl Exception : ' . curl_errno($curl) . ' - ' . htmlspecialchars(curl_error($curl)));
            curl_close($curl);
            throw $exception;
        }
        
        // Connexion réussie, on ferme et on renvoie le résultat sous forme d'un tableau associatif
        curl_close($curl);
        return json_decode($result, true);
    }
}
