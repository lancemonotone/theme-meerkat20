<?php
/**
 * Class Shortcode
 */
class Shortcode {
    private static $instance;

    protected function __construct() {
        add_shortcode( 'raw_excerpt', array($this, 'wms_raw_excerpt') );
        add_shortcode( 'image_html', array($this, 'image_html') );
    }

    /**
     * Turn an image URL into Wordpress-generated <img ...> tag
     * (It would be nicer to do this by image ID, but this was developed for
     * Beaver Builder Themer and the wpbb shortcode doesn't seem to return
     * an ACF image field by ID, only by URL.)
     *
     * Shortcode params
     *
     * @param int       $image_id    Valid ID of an image attachment
     * @param string    $image_url   Valid URL of an image attachment
     * @param string    $size        Valid image size; defaults to 'thumbnail'
     *
     */
    function image_html($atts) {
        // normalize attribute keys, lowercase
        $atts = array_change_key_case( (array) $atts, CASE_LOWER );
     
        // override default attributes with user attributes
        $image_atts = shortcode_atts(
            array( 'image_id'=>0, 'image_url'=>'', 'size'=>'thumbnail'), 
            $atts
        );

        // Prioritize ID because that seems more correct
        if (is_numeric($image_atts['image_id']) && $image_atts['image_id'] > 0) {
            // check that image exists
            if ($image_html = wp_get_attachment_image($image_atts['image_id'], $image_atts['size'])) {
                return $image_html;
            }
        } else if ($image_atts['image_url']) {
            if ($image_id = attachment_url_to_postid($image_atts['image_url'])) {
                return wp_get_attachment_image($image_id, $image_atts['size']);
            }
        }
        // do nothing if a valid attachment URL is not present
        return '';
    }

    function wms_raw_excerpt() {
        global $post;
        return $post->post_excerpt;
    }

    /**
     * Returns the singleton instance of this class.
     *
     * @return Shortcode The singleton instance.
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
Shortcode::instance();
