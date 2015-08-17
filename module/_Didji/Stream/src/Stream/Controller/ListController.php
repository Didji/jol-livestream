<?php
namespace Stream\Controller;

use Stream\Service\ChannelServiceInterface;
use Stream\Entity\Channel;

use ApiConsumer\Service\ApiConsumerService;

use Zend\Mvc\Controller\AbstractActionController;

class ListController extends AbstractActionController
{

    /**
     * @var ChannelServiceInterface
     */
    protected $channelService;

    public function __construct(ChannelServiceInterface $channelService)
    {
        $this->channelService = $channelService;
    }

    public function indexAction()
    {

    }
}
