<?php
namespace ApiConsumer\Service;

interface ApiConsumerServiceInterface
{
    /**
    * Récupère les informations sur une chaîne passée en paramètre
    *
    * @param string $name Nom de la chaîne à inspecter
    *
    * @return Array Les informations de la chaîne
    */
    public function getChannelData($name = null);
}
