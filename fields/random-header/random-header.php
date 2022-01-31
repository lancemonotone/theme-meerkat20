<?php

/**
 * Class RandomHeader
 */
class RandomHeader {
    private static $instance;

    protected function __construct() {
       self::random_header();
    }
    //load fields without assets    
    static public function random_header(){
        FLPageData::add_post_property( 'rand_img', array(
            'label'   => 'Random Customizer Header Image',
            'group'   => 'advanced',
            'type'    => 'photo',
            'getter'  => array(__CLASS__, 'rand_img_getter'),
        ) );
    }
    //return  header image as setup in the customizer
    static public function rand_img_getter() {
        return get_header_image();    
    }

    /**
     * Returns the singleton instance of this class.
     *
     * @return RandomHeader The singleton instance.
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
// RandomHeader::instance()->random_header(); 
 RandomHeader::instance();






 