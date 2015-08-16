<?php
namespace Stream;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
            'streams' => [
                'type' => 'literal',
                'options' => [
                    'route'    => '/streams',
                    'defaults' => [
                        '__NAMESPACE__' => Controller::class,
                        'controller' => 'List',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'add' => [
                        'type' => 'literal',
                        'options' => [
                            'route' => '/ajouter',
                            'defaults' => [
                                'controller' => 'Write',
                                'action' => 'add',
                            ],
                        ],
                    ],
                    'test-mind' => [
                        'type' => 'literal',
                        'options' => [
                            'route' => '/test-mind',
                            'defaults' => [
                                'controller' => 'Write',
                                'action' => 'test',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            'Stream\Controller\Write'
                => Factory\Controller\WriteControllerFactory::class,
        ],
        'invokables' => [
            'Stream\Controller\List'
                => Controller\ListController::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            Form\ChannelForm::class
                => Factory\Form\ChannelFormFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\ChannelService::class
                => Factory\Service\ChannelServiceFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [__DIR__ . '/../view'],
    ],
    'doctrine' => [
        'driver' => [
            'ChannelAnnotationDriver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Stream/Entity'],
            ],
            'orm_default' => [
                'drivers' => [
                    Entity::class => 'ChannelAnnotationDriver',
                ],
            ]
        ],
    ],
];