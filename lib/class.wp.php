<?php //basic wordpress modifications

/**
 * add body classes
 *
 * m20 to use in child theme styling
 * page-template-template-home to use home page styles
 *
 */

add_filter( 'body_class','my_body_classes' );
function my_body_classes( $classes ) {

    $classes[] = 'm20';
    // @todo rename the white background with purple wordmark class
    $classes[] = 'page-template-template-home'; //white with purple wms wordmark class
    $subtheme = get_theme_mod('m20_subtheme_radio_btns');
    $classes[] = 'm20-subtheme-' . ($subtheme ? $subtheme : "basic");


    return $classes;

}