<?php
namespace Stream\Service;

use Jol\Doctrine\Service\AbstractDoctrineObjectService;

class ChannelService extends AbstractDoctrineObjectService
    implements ChannelServiceInterface
{
    protected $objectClassName = 'Stream\Entity\Channel';
}