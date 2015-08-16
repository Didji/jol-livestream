<?php
namespace Stream\Factory\Form;

use Stream\Form\ChannelForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ChannelFormFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        $realSm = $serviceManager->getServiceLocator();

        return new ChannelForm(
            $realSm->get('doctrine.entitymanager.orm_default')
        );
    }
}
