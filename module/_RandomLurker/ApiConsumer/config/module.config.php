<?php
namespace ApiConsumer;

return [
    'service_manager' => [
        'factories' => [
            Service\HitboxApi::class
                => Factory\Service\ApiConsumerFactory::class,
            Service\TwitchApi::class
                => Factory\Service\ApiConsumerFactory::class
        ],
    ]
];