<?php

require_once(M20_BB_DIR . '/fields/index.php');

class M20_BB_Custom_Fields {
    private static $instance;
    protected static $fields;
    protected static $simplefields;

    protected function __construct() {
        add_action('init', array(&$this, 'init'));
        self::$simplefields = M20_BB_Fields_Definitions::getsimple();
        self::$fields = M20_BB_Fields_Definitions::get();
    }

    /**
     * Setup hooks if the builder is installed and activated.
     */
    static public function init() {
        if ( ! class_exists('FLBuilder')) {
            return;
        }

        // Enqueue custom field assets.
        self::enqueue_field_assets();

        // Register custom fields.
        add_filter('fl_builder_custom_fields', array(__CLASS__, 'register_fields'));
    
        //add custom field connections that don't have assets
        add_action('fl_page_data_add_properties', array(__CLASS__, 'load_simple_fields'));

    }

    //load simple fields without assets
    static public function load_simple_fields($simplefields){
        
         foreach (self::$simplefields as $simplefield) {
            require_once(M20_BB_DIR . "/fields/{$simplefield}/{$simplefield}.php");
        }
        
    }

    /**
     * Registers our custom fields.
     */
    static public function register_fields($fields) {
        foreach (self::$fields as $field) {
            $fields[ $field ] = M20_BB_DIR . "/fields/{$field}/{$field}.php";
        }

        return $fields;
    }

    /**
     * Enqueues our custom field assets only if the builder UI is active.
     */
    static public function enqueue_field_assets() {
        if ( ! FLBuilderModel::is_builder_active()) {
            return false;
        }
        foreach (self::$fields as $field) {
            wp_enqueue_style($field, M20_BB_URL . "/fields/{$field}/assets/css/style.css", array(), '');
            wp_enqueue_script($field, M20_BB_URL . "/fields/{$field}/assets/js/field.js", array(), '', true);
        }
    }

    /**
     * Returns the singleton instance of this class.
     *
     * @return M20_BB_Custom_Fields The singleton instance.
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

M20_BB_Custom_Fields::instance();