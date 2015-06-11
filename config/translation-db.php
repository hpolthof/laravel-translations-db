<?php

return [
    /**
     * To save some time interacting with the database, you can turn
     * the storing of the viewed_at field off.
     */
    'update_viewed_at' => true,

    /**
     * This setting enables or disables the web interface and its routes.
     */
    'webinterface' => true,

    /**
     * This is the prefix for on which URI the Translations Manager will
     * be available. You can leave it just as is in most cases.
     */
    'route_prefix' => '_translations',

    /**
     * If your using the Laravel Debugbar provided by Barryvdh\Debugbar
     * you might want to disable this in the Translations Manager.
     * This interface can generate a bunch of Ajax calls that will slow
     * the translation process down.
     * You can however turn it on, the choice is yours.
     */
    'disable_debugbar' => true,
];
