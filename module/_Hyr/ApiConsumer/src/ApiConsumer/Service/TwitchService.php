<?php
namespace ApiConsumer\Service;

/**
* Echange avec l'API Twitch
*/
class TwitchService extends ApiConsumerService
{
    /**
     * URL de base de l'API Twitch
     */
    protected $baseUrl;

    /**
    * Sections utilisées par l'API Twitch
    */
    protected $sectionsUrl;
    
    public function __construct()
    {
        $this->baseUrl = "https://api.twitch.tv/kraken/";

        $this->sectionsUrl = [
            "teams" => "teams/",
            "channels" => "channels/",
            "games" => "games/",
            "users" => "users/",
            "streams" => "streams/",
            "search" => "search/"
        ];
    }

    /**
    * Récupère les informations sur une chaîne passée en paramètre
    *
    * @param string $name Nom de la chaîne à inspecter
    *
    * @return Array Les informations de la chaîne
    */
    public function getChannelData($name = null)
    {
        // Construction de l'URL à requêter
        $channelUrl = $this->buildUrl('channels');
        $url = $channelUrl . $name;

        // Lancement de la requête
        $channelData = $this->executeQuery($url);

        return $channelData;
    }

    /**
    * Retourne les informations des streams des chaînes passées en paramètre, si elles sont en live
    *
    * @param string $channels Liste des chaînes séparées par une virgule à requêter
    *
    */
    public function getStreams($channels)
    {
        // Construction de l'URL à requêter
        $channelUrl = $this->buildUrl('streams');
        $url = $channelUrl . '?channel=' . $channels;

        // Lancement de la requête
        $streamsData = $this->executeQuery($url);

        // Si le paramètre _total vaut 0, aucun stream n'est actif
        if ($streamsData['_total'] == 0) {
            return '';
        }

        return $streamsData;
    }

    /**
    * Construit l'URL à requêter à partir de l'URL de base de l'API et de la section voulue
    *
    * @param string $section Section à utiliser
    *
    * @return string L'URL à requêter ou une chaîne vide si la section n'est pas valide
    */
    private function buildUrl($section)
    {
        // Si la section passée en paramètre est valide, on construit l'URL
        if (array_key_exists($section, $this->sectionsUrl)) {
            return $this->baseUrl . $this->sectionsUrl[$section];
        }

        return '';
    }
}
