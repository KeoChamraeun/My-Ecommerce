<?php

return [
    'class_namespace' => 'App\\Http\\Livewire',
    'view_path' => resource_path('views/livewire'),
    'layout' => 'layouts.app',
    'asset_url' => null,
    'app_url' => null,
    'middleware_group' => 'web',
    'temporary_file_upload' => [
        'disk' => 'public',
        'rules' => ['nullable', 'image', 'max:2048'],
        'directory' => 'livewire-tmp',
        'middleware' => 'throttle:60,1',
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a',
            'jpg', 'jpeg', 'mpga', 'webp', 'wma',
        ],
        'max_upload_time' => 5,
    ],
    'manifest_path' => null,
    'back_button_cache' => false,
    'render_on_redirect' => false,
];
