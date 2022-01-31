<?php

/**
 * This is an example module with only the basic
 * setup necessary to get it working.
 *
 * @class FLBasicExampleModule
 */

class M20_BB_Site_Masthead_Module extends FLBuilderModule {
    /**
     * Constructor function for the module. You must pass the
     * name, description, dir and url in an array to the parent class.
     *
     * @method __construct
     */
    public function __construct() {
        parent::__construct(array(
            'name'          => __('Site Masthead', 'fl-builder'),
            'description'   => __('M16 Site Title and Breadcrumbs.', 'fl-builder'),
            'category'      => __('Williams Modules', 'fl-builder'),
            'dir'           => M20_BB_DIR . '/modules/site-masthead/',
            'url'           => M20_BB_URL . '/modules/site-masthead/',
            'editor_export' => true, // Defaults to true and can be omitted.
            'enabled'       => true, // Defaults to true and can be omitted.
            'group'         => 'Williams',
        ));
    }
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('M20_BB_Site_Masthead_Module', array(
    'general' => array( // Tab
        'title'    => __('General', 'fl-builder'), // Tab title
        'sections' => array( // Tab Sections
            'general' => array( // Section
                'title'  => __('Custom Site Breadcrumb (not active)', 'fl-builder'), // Section Title
                'fields' => array( // Section Fields
                    // 'bc_link' => array(
                    //     'type'          => 'link',
                    //     'label'         => 'BC Link',
                    //     'show_target'   => true,
                    //     'show_nofollow' => true,
                    // ),
                    // 'bc_textarea_field' => array(
                    //     'type'          => 'textarea',
                    //     'label'         => __( 'BC Text', 'fl-builder' ),
                    //     'default'       => '',
                    //     'placeholder'   => __( 'Something Shorter?', 'fl-builder' ),
                    //     'rows'          => '6'
                    // ),
                )
            )
        )
    )
));
