<?php
return [
    'token_expires' => 86400,
    'providers' =>[
        Module\Auth\Providers\AuthModuleServiceProvider::class,
        Module\PermissionManager\Providers\PermissionManagerModuleServiceProvider::class,
    ]
];
