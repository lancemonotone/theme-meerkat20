<?php

/**
 * 1. Update name and description properties below
 * 2. Customize the array of fields for FLBuilder::register_module
 * 3. Run 'webpack' in module directory to build js and css.
 */

include('lib/class.example.php');

class Elastic_Module extends FLBuilderModule {
    public $name = 'Elastic Module';
    public $description = 'This is a sample module.';
    public $enabled = true;

    // You shouldn't need to change anything past this point.
    public $slug = __DIR__;

    public function __construct() {
        parent::__construct(array(
            // Shouldn't need to change these...
            'name'            => __($this->name, 'fl-builder'),
            'description'     => __($this->description, 'fl-builder'),
            'group'           => 'Williams',
            'category'        => __('Williams Modules', 'fl-builder'),
            'dir'             => M20_BB_DIR . '/modules/' . $this->slug . '/',
            'url'             => M20_BB_URL . '/modules/' . $this->slug . '/',
            //
            'icon'            => 'button.svg',
            'editor_export'   => true, // Defaults to true and can be omitted.
            'enabled'         => $this->enabled, // Defaults to true and can be omitted.
            'partial_refresh' => false, // Defaults to false and can be omitted.
        ));

        // Use this if needed.
        $this->add_styles_scripts();
    }

    /**
     * Do not enqueue frontend.js, frontend.css or frontend.responsive.css as that will be enqueued automatically.
     * @method add_styles_scripts()
     */
    public function add_styles_scripts() {
     
        $this->add_js('autocomplete', $this->url . 'js/lib/auto-complete.min.js');
        
   
    }

    public static function get_form(){
        $fields = array(
            'my-tab-1' => array(
                'title'    => 'Settings',
                'sections' => array(
                    'my-section-1' => array(
                        'title'  => 'Section 1',
                        'fields' => array(
                            'my-field-1' => array(
                                'type'  => 'text',
                                'label' => 'Text Field 1',
                            ),
                            'my-field-2' => array(
                                'type'  => 'text',
                                'label' => 'Text Field 2',
                            )
                        )
                    )
                )
            )
        );
        return $fields;
    }

}

FLBuilder::register_module('Elastic_Module', Elastic_Module::get_form());