<?php

/**
 * 1. Update name and description properties below
 * 2. Place asset files in proper places
 * 3. Run 'webpack' in submodule directory to build js and css
 */

class MS_Covid_Dashboard {

    // Start config
    public static $name = 'Covid Dashboard';
    public static $template = 'covid-dashboard';
    public static $class = '';
    public static $module_slugs = array(
        'post-carousel'
    );
    // End config

    public static $dir, $frontend, $js_frontend_file, $js_settings_file, $css_file;

    public function __construct() {
        self::$dir              = dirname(__FILE__);
        self::$frontend         = self::$dir . '/frontend.php';
        self::$js_frontend_file = self::$dir . '/js/frontend.js';
        self::$js_settings_file = self::$dir . '/js/settings.js';
        self::$css_file         = self::$dir . '/css/frontend.css';

        Multisource_Module::modify_submodule_settings_form($this);
        Multisource_Module::add_frontend_file($this);
        Multisource_Module::add_js($this, self::$js_settings_file);
        //MultisourceSlider_Modules::add_js($this, self::$js_frontend_file);
        Multisource_Module::add_css($this);
    }

    /**
     * Returns the singleton instance of this class.
     *
     * @return MS_Covid_Dashboard The singleton instance.
     */
    public static function instance() {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * singleton instance.
     *
     * @return void
     */
    private function __clone() {
    }

    /**
     * Private unserialize method to prevent unserializing of the singleton
     * instance.
     *
     * @return void
     */
    private function __wakeup() {
    }

    private static $instance;
}

MS_Covid_Dashboard::instance();
