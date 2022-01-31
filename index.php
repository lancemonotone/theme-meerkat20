<?php
///add the loop for BB
the_post();

//this logic is a little convoluted, because show_sidebar_on_bb_home was previously false by default, it is now opt in and a site option as well in case the home page is not a page
$hide_sidebar_front_page = (is_front_page() || is_home()) && ! get_field('show_sidebar_on_bb_home', 'options');
if ( ! is_archive() && ! is_category() && ! is_post_type_archive()) {
    $hide_sidebar_page = get_field('hide_sidebar', $post->ID);
}
$hide_sidebar_option = get_field('remove_sidebar', 'option');

$hide_sidebar = $hide_sidebar_front_page || $hide_sidebar_page || $hide_sidebar_option;

Timberizer::render_template(array(
    'hide_sidebar' => $hide_sidebar,
));
