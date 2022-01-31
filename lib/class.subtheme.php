<?php

/**
 * Enable support for BB in M20
 *
 * @since 1.0
 */
class M20_BB_Sub_Theme {
    private static $instance;

    protected function __construct() {
        
        //get subtheme value
        $subtheme = get_theme_mod('m20_subtheme_radio_btns');
        //init certain things on that subtheme only
        if ($subtheme){
            //welcome subtheme
            if ($subtheme == "welcome"){
                add_action('wp_head', array(&$this, 'load_welcome_fonts'));
                add_filter('fl_builder_color_presets', array(&$this, 'wms_welcome_color_presets'));
            }
    
        }
      

    }

    public static function load_welcome_fonts(){
        ?> 
        <!-- @todo make it so these only load on their respective sites -->
        <!-- //welcome site fonts -->
         <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro&display=swap" rel="stylesheet"> 
         <link href="https://fonts.googleapis.com/css?family=Source+Code+Pro&display=swap" rel="stylesheet"> 
        <!-- //graduation 2020 fonts -- FF Providence loaded via typekit, Amatic dropped from Typekit, Knockout coming from typography.com -->
         <link rel="stylesheet" href="https://use.typekit.net/jtc0gev.css">
         <link rel="stylesheet" type="text/css" href="https://cloud.typography.com/7265312/7501612/css/fonts.css">

        <?php
    }
    
    public static function wms_welcome_color_presets($colors) {

        
        $colors[] = '9933FF'; // bright pink
        $colors[] = '9966CC'; // muted pink
        $colors[] = '330033'; // eggplant
        $colors[] = '660099'; // another purple


        return $colors;
    }



    /**
     * Returns the singleton instance of this class.
     *
     * @return M20_BB_Sub_Theme The singleton instance.
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

M20_BB_Sub_Theme::instance();