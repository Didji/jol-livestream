<?php
namespace Stream;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use ZfcRbac\Guard\RouteGuard;

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
                    ]
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            'Stream\Controller\Write'
                => Factory\Controller\WriteControllerFactory::class,
            'Stream\Controller\List'
                => Factory\Controller\ListControllerFactory::class
        ]
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
    'zfc_rbac' => [
        'guards' => [
            RouteGuard::class => [
                'streams*'      => ['member'],
            ],
        ],
    ],
];
