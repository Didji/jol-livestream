<?php
namespace Stream\Factory\Service;

use Stream\Service\ChannelService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ChannelServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sm)
    {
        return new ChannelService(
            $sm->get('doctrine.entitymanager.orm_default')
        );
    }
}
