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
    * @param String $name   Nom de la chaîne à inspecter
    *
    * @return Array Les informations de la chaîne
    */
    public function getChannelData(string $name = null)
    {
        // Construction de l'URL à requêter
        $channelUrl = $this->buildUrl('channels');
        $url = $channelUrl . $name;

        // Lancement de la requête
        $channelData = $this->executeQuery($url);

        return $filteredData;
    }

    /**
    * Construit l'URL à requêter à partir de l'URL de base de l'API et de la section voulue
    *
    * @param String $section Section à utiliser
    *
    * @return String L'URL à requêter ou une chaîne vide si la section n'est pas valide
    */
    private function buildUrl(string $section)
    {
        // Si la section passée en paramètre est valide, on construit l'URL
        if (array_key_exists($section, $this->sectionsUrl)) {
            return $this->baseUrl . $this->sectionsUrl[$section];
        }

        return '';
    }
}
