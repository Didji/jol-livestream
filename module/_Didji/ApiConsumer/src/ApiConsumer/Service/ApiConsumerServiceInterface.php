<?php
namespace ApiConsumer\Service;

interface ApiConsumerServiceInterface
{
    /**
    * Récupère les informations sur une chaîne passée en paramètre
    *
    * @param String $name Nom de la chaîne à inspecter
    *
    * @return Array Les informations de la chaîne
    */
    public function getChannelData(string $name = null);
}
