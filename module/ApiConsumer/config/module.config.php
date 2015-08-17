<?php
namespace ApiConsumer;

return [
    'service_manager' => [
        'invokables' => [
            'ApiConsumer\Service\ApiConsumer'
                => Service\ApiConsumerService::class
        ]
    ]
];
