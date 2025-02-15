<?php
return [
    /*
    |--------------------------------------------------------------------------
    | DEFAULT SETTINGS
    |--------------------------------------------------------------------------
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Show Detailed Error Message
    |--------------------------------------------------------------------------
    |
    | When this is set to true, the admin users will see a more detailed error message
    | e.g. detailed SQL errors
    | This is not recommended as it could display more information than you require to your admin users
    | But it can be useful when it is hard for admin users to access logs
    | All detailed error message are hidden from non-admins
    |
    | true or false
    */

    'show_detailed_error_messages' => env('SHOW_DETAILED_ADMIN_ERRORS', false),

    /*
    |--------------------------------------------------------------------------
    | Avatar URL
    |--------------------------------------------------------------------------
    |
    | Displayed in the bot admin
    |
    | true or false
    */

    'lemurtar_url' => env('LEMUR_TAR_URL', 'https://lemurtar.com'),
    /*
    |--------------------------------------------------------------------------
    | Max User Input
    |--------------------------------------------------------------------------
    |
    | Set a maximum amount of chars that the user is allowed to send in a single input
    |
    */

    'max_user_char_input' => env('MAX_USER_INPUT_CHARS', 255),

    /*
    |--------------------------------------------------------------------------
    | Default Bot Image
    |--------------------------------------------------------------------------
    |
    | Location of the images used in the widget gui
    |
    */

    'default_bot_image' => 'widgets/robot.png',

    /*
    |--------------------------------------------------------------------------
    | Default Client Image
    |--------------------------------------------------------------------------
    |
    | Location of the images used in the widget gui
    |
    */

    'default_client_image' => 'widgets/user.png',
];
