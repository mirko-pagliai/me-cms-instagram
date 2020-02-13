<?php
declare(strict_types=1);

/**
 * Before using the plugin, you have to get the API access token:
 * https://www.instagram.com/developer/clients/manage
 */

return [
    'MeCmsInstagram' => [
        //Default layout
        'default' => [
            //Number of photos to show per page. This must be a multiple of 3
            'photos' => 12,
            //Show the "follow me button in the user's profile
            'follow_me' => true,
            //Open the photos on Instagram, rather than on the site
            'open_on_instagram' => false,
            //Show the user's profile
            'user_profile' => true,
        ],
    ],
    'Instagram' => [
        //API access token
        'key' => 'your-key-here',
    ],
];
