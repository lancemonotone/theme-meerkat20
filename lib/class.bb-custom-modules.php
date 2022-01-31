<?php
// Load custom modules.
//require_once(M20_BB_DIR . '/modules/index.php');

class M20_BB_Custom_Modules {
    private static $instance;
    protected static $modules = array();

    protected function __construct() {
        add_action('init', array(&$this, 'load_modules'), 1, 1);
    }

    /**
     * Setup hooks if the builder is installed and activated.
     */
    public static function init() {

        //self::load_modules();
    }

    public static function load_modules($plugin) {
        if ( ! class_exists('FLBuilder')) {
            return;
        }
        $dirs = glob(M20_BB_DIR . '/modules/*', GLOB_ONLYDIR);
        foreach ($dirs as $dir) {
            $file = substr($dir, strripos($dir, '/') + 1);
            if (file_exists($module = $dir . "/{$file}.php")) {
                require_once($module);
            }
        }
    }


    /**
     * Returns the singleton instance of this class.
     *
     * @return M20_BB_Custom_Modules The singleton instance.
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
}

M20_BB_Custom_Modules::instance();