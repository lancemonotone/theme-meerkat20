<?php

/**
 * Contains methods for customizing the theme WP customization menus.
 *
 * @link http://codex.wordpress.org/Theme_Customization_API
 */

class M20_Customize {
    //adds new areas and fields to WP theme customizer

    public static function register($wp_customize) {
        //new panel for M20 subtheme
        $wp_customize->add_section('m20_subtheme',
            array(
                'title'    => 'Subtheme',
                'priority' => 1,
            )
        );

        // subtheme radio buttons
        $wp_customize->add_setting('m20_subtheme_radio_btns', array(
            'default'  => 'basic',
            'type'     => 'theme_mod',
            'priority' => 1,
        ));

        // find the subtheme scss files and make them options in the customizer
        $dir                = dirname(__DIR__) . '/assets/src/scss/subthemes/';
        $files              = array_values(array_diff(scandir($dir), array(".", "..")));
        $subthemes['basic'] = 'Basic';

        //Comments in scss files at /assets/src/scss/subthemes/ should include
        /*
        Subtheme Name: magazine
        Template: m20
        */
        foreach ($files as $filename) {
            $file = $dir . $filename;
            if ( ! is_file($file)) continue;
            $stylelines = explode("\n", implode('', file($file)));
            if ($stylelines) {
                foreach ($stylelines as $line) {
                    $string = "Subtheme Name:";
                    if (strpos($line, $string) !== false) {
                        list($part1, $part2) = explode($string, $line);
                        $item = trim($part2);
                        // array_push($subthemes , trim($part2));
                        $subthemes[ sanitize_title_with_dashes($item) ] = ucfirst($item);
                    }
                }
            }
        }

        $wp_customize->add_control('m20_subtheme_radio_btns', array(
                'type'    => 'radio',
                'label'   => 'Select a subtheme:',
                'section' => 'm20_subtheme',
                'choices' => $subthemes,
            )
        );

    }


} //end M20_Customize

// Setup the Theme Customizer settings and controls...
add_action('customize_register', array('M20_Customize', 'register'));