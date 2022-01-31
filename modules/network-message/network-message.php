<?php



class Network_Message_Module extends FLBuilderModule {
    public $name = 'Network_Message Module';
    public $description = 'This module displays the network that is set in the network dashboard.';
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

 

    public static function get_form(){
        $fields = array(
            'my-tab-1' => array(
                'title'    => 'Settings',
                'sections' => array(
                    'my-section-1' => array(
                        'title'  => 'Section 1',
                        'fields' => array(
                          'module_message' => array(
                                'type'    => 'raw',
                                'label'   => 'Note:',
                                'content' => 'The content of this module is updated automatically from the network dashboard >> <a href="https://network.williams.edu/wp-admin/network/admin.php?page=site_options" >site options</a>.',
                            ),
                        )
                    )
                )
            )
        );
        return $fields;
    }

}

FLBuilder::register_module('Network_Message_Module', Network_Message_Module::get_form());