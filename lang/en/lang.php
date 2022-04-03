<?php

$name = 'Sparkpost secret';

return [
    'plugin_description' => 'Sparkpost mail driver plugin',

    'fields' => [
        'sparkpost_secret' => [
            'label' => $name,
            'comment' => 'Enter your ' . $name,
        ],
    ],
];
