<?php
namespace Stream\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class ChannelForm extends Form
{
    public function __construct(
        ObjectManager $objectManager
    ) {
        parent::__construct('channel-form');

        // On utilise l'hydrateur Doctrine pour hydrater un objet Greeting
        $this->setHydrator(new DoctrineHydrator($objectManager));

        $this->add([
            'name' => 'id',
            'type' => Hidden::class,
        ]);

        $this->add([
            'name' => 'jolUser',
            'type' => Hidden::class
        ]);

        $this->add([
            'name' => 'url',
            'type' => Text::class,
            'options' => [
                'label' => 'Lien : '
            ]
        ]);

        $this->add([
            'name' => 'description',
            'type' => Text::class,
            'options' => [
                'label' => 'Description : ',
            ],
        ]);

        // Protection CSRF
        $this->add([
            'name' => 'security',
            'type' => Csrf::class,
            'options' => [
                'csrf_options' => [
                    'timeout'   => 600,
                ],
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => Submit::class,
            'attributes' => [
                 'value' => 'Valider',
            ],
        ]);
    }
}
