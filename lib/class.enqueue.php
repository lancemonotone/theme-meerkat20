<?php

/**
 * include app js and other styles/scripts
 */

class M20_BB_Enqueue {
    private static $instance;

    protected function __construct() {
        if (is_front_page() && get_current_blog_id()== 26){  //dequeue searchui on admission home
            add_action( 'wp_print_scripts', array($this, 'wms_wpdocs_dequeue_script'), 100 );
        }
        $ver_js = file_exists( get_stylesheet_directory() . '/assets/js/build/main.js' ) ? filemtime( get_stylesheet_directory() . '/assets/js/build/main.js' ) : time();
        wp_enqueue_script( 'brochure-js', get_stylesheet_directory_uri()  . '/assets/build/js/main.js', array('jquery'), $ver_js );
    }

    // Async load
    public static function wms_async_scripts($url){
        if ( strpos( $url, '#asyncload') === false )
            return $url;
        else if ( is_admin() )
            return str_replace( '#asyncload', '', $url );
        else
            return str_replace( '#asyncload', '', $url )."' async='async";
    }

    /**
     * dequeue
     */
    public static function wms_wpdocs_dequeue_script() {
         wp_dequeue_script( 'theme_uisearch' );
    }

    /**
     * Returns the singleton instance of this class.
     *
     * @return  The singleton instance.
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

M20_BB_Enqueue::instance();