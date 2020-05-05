<?php

return [
    'img' => [
        'admin_profile' => [
            'prefix' => 'admprofile',
            'default_name' => env('DEFAULT_NAME_PROFILE_PICTURE', 'profiledefault.png'),
            'path' => env('PATH_IMG_ADMIN_PROFILE', 'public/uploads/img/profile')
        ]
    ]
];
