<?php
namespace Stream\Controller;

use Stream\Service\ChannelServiceInterface;
use Stream\Entity\Channel;

use ApiConsumer\Service\ApiConsumerService;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ListController extends AbstractActionController
{
    /**
     * @var ChannelServiceInterface
     */
    protected $channelService;

    /**
     * @param ChannelServiceInterface $channelService
     */
    public function __construct(ChannelServiceInterface $channelService)
    {
        $this->channelService = $channelService;
    }

    /**
    * L'action menant à la page d'accueil
    */
    public function indexAction()
    {
        // Retourne toutes les chaînes présentes dans la base de données
        $channels = $this->channelService->findAll();

        // Tri des chaînes en fonction de leur type de plateforme
        // TODO: faire ça directement par requête (DQL ?)
        $channels = $this->orderChannelsByType($channels);

        // Récupère les informations sur les streams si les chaînes sont en live
        $streams = $this->getChannelStreams($channels);

        return new viewModel([
            'streams' => $streams
        ]);
    }

    /**
    * Trie les chaînes par plateforme utilisée
    *
    * @param array $channels Les chaînes présentes dans la base de données
    *
    * @return array Les chaînes triées
    */
    private function orderChannelsByType(array $channels)
    {
        $orderedChannels = [];
        foreach ($channels as $channel) {
            $type = $channel->getType();

            if (!isset($orderedChannels[$type])) {
                $orderedChannels[$type] = [];
            }

            $orderedChannels[$type][] = $channel;
        }

        return $orderedChannels;
    }

    /**
    * Récupère les informations des streams des chaînes
    *
    * @param array $channels Les chaînes précédemment triées
    *
    * @return array Les streams actifs
    */
    private function getChannelStreams(array $channels)
    {
        $streams = [];
        foreach ($channels as $type => $channels) {
            // On créée une connexion pour la plateforme
            $apiConsumer = ApiConsumerService::getApiConsumer($type);
            $channelNames = [];
            // On concatène tous les noms de chaîne de cette plateforme
            foreach ($channels as $channel) {
                $channelNames[] = $channel->getName();
            }
            $requestChannels = implode(',', $channelNames);

            // On requête tout d'un coup
            $rawStreamData = $apiConsumer->getStreams($requestChannels);

            // Si le résultat est un tableau, il y a au moins un stream actif, on parse les données
            if (is_array($rawStreamData)) {
                $streams = array_merge($streams, $this->processRawStreamData($rawStreamData));
            }
        }

        return $streams;
    }

    /**
    * Parse les données des streams pour récupérer celles qui importent
    *
    * @param array $streamData Un tableau associatif des données retournées par les API
    *
    * @return array Les informations importantes des streams
    */
    private function processRawStreamData(array $streamData)
    {
        $streamsInfos = [];
        foreach ($streamData['streams'] as $stream) {
            $streamId = $stream['_id'];
            $streamsInfos[$streamId] = [
                'game' => $stream['game'],
                'title' => $stream['channel']['status'],
                'preview' => $stream['preview']['medium'],
                'channel' => $stream['channel']['url']
            ];
        }

        return $streamsInfos;
    }
}
