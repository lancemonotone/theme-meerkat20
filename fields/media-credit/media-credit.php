<?php

/**
 * Class MediaCredit 
 */
class MediaCredit  {
    private static $instance;

    protected function __construct() {
       self::media_credit();
    }
    //load fields without assets    
    static public function media_credit(){
        FLPageData::add_post_property( 'media_credit', array(
            'label'   => 'Featured Image ACF Credit',
            'group'   => 'advanced',
            'type'    => 'string',
            'getter'  => array(__CLASS__, 'media_credit_getter'),
        ) );
    }
    //return  header image as setup in the customizer
    static public function media_credit_getter() {
        $featuredID = get_post_thumbnail_id();
     
        return get_field('photo_credit', $featuredID);   
    }

    /**
     * Returns the singleton instance of this class.
     *
     * @return MediaCredit  The singleton instance.
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
// MediaCredit ::instance()->media_credit(); 
 MediaCredit ::instance();






 