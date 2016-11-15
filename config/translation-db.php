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

    /**
     * - Force translations to be cached, even in Debug Mode.
     * - And disables the collection of new keys.
     * This can be used to prevent lots of queries from
     * happening.
     */
    'minimal' => false,

    /**
     * Use locales from files as a fallback option. Be aware that
     * locales are loaded as groups. When just one locale of a group
     * exists in the database, a file will never be used.
     * To use some files, keep these groups fully out of your database.
     */
    'file_fallback' => false,
];
