<?php
$userId = isset($_COOKIE['bbuserid']) ? (int) $_COOKIE['bbuserid'] : 0;
$modulePaths = [
    77355 => 'RandomLurker',
    103704 => 'Hyr',
    426756 => 'Didji'
];
$modulePath = isset($modulePaths[$userId]) ? '_' . $modulePaths[$userId] . '/' : '';

return [
    'modules' => [
        'Stream',
        //'ApiConsumer'
    ],
    'module_listener_options' => [
        'module_paths' => [
            'Stream' => __DIR__ . '/../module/' . $modulePath . 'Stream',
            'ApiConsumer' => __DIR__ . '/../module/' . $modulePath . 'ApiConsumer'
        ]
    ]
];
