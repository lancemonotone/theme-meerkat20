<?php
class Career_Path_Module extends FLBuilderModule {
    public $name = 'Career_Path Module';
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

    }
}

FLBuilder::register_module('Career_Path_Module',
    array(
        'my-tab-1' => array(
            'title'    => 'Tab 1',
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
    )
);
