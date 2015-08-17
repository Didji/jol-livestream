<?php
namespace Stream\Factory\Controller;

use Stream\Controller\ListController;
use Stream\Service\ChannelService;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ListControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $sm
     * @return ListController
     */
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        // Le service manager injecté est celui des contrôleurs.
        // On récupère le service manager principal.
        $realSm = $serviceManager->getServiceLocator();

        return new ListController(
            $realSm->get(ChannelService::class)
        );
    }
}
