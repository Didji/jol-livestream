<?php
namespace Stream\Factory\Controller;

use Stream\Controller\WriteController;
use Stream\Form\ChannelForm;
use Stream\Service\ChannelService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class WriteControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $sm
     * @return WriteController
     */
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        // Le service manager injecté est celui des contrôleurs.
        // On récupère le service manager principal.
        $realSm = $serviceManager->getServiceLocator();

        return new WriteController(
            $realSm->get('FormElementManager')->get(ChannelForm::class),
            $realSm->get(ChannelService::class)
        );
    }
}
