<?php

/**
 * This is an example module with only the basic
 * setup necessary to get it working.
 *
 * @class FLBasicExampleModule
 */
class M20_BB_Breadcrumb_Bar_Module extends FLBuilderModule {
    /**
     * Constructor function for the module. You must pass the
     * name, description, dir and url in an array to the parent class.
     *
     * @method __construct
     */
    public function __construct() {
        parent::__construct(array(
            'name'          => __('Breadcrumb Bar', 'fl-builder'),
            'description'   => __('New Full Width Breadcrumb Bar.', 'fl-builder'),
            'category'      => __('Williams Modules', 'fl-builder'),
            'dir'           => M20_BB_DIR . '/modules/breadcrumb-bar/',
            'url'           => M20_BB_URL . '/modules/breadcrumb-bar/',
            'editor_export' => true, // Defaults to true and can be omitted.
            'enabled'       => true, // Defaults to true and can be omitted.
            'group'         => 'Williams',
        ));
    }
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('M20_BB_Breadcrumb_Bar_Module', array(
    'general' => array( // Tab
        'title'    => __('General', 'fl-builder'), // Tab title
        'sections' => array( // Tab Sections
            'general' => array( // Section
                'title'  => __('Breadcrumb Settings', 'fl-builder'), // Section Title
                'fields' => array( // Section Fields
                  'wms_home_crumb' => array(
                    'type'    => 'button-group',
                    'label'   => 'Hide the Williams home crumb',
                    'default' => 'false',
                    'options' => array(
                        'false'    => 'No',
                        'true'   => 'Yes',
                    ),
                ),
                 'wms_dept_crumb' => array(
                    'type'    => 'button-group',
                    'label'   => 'Hide the Williams department crumb',
                    'default' => 'false',
                    'options' => array(
                         'false'    => 'No',
                        'true'   => 'Yes',
                    ),
                ),
                )
            )
        )
    )
));