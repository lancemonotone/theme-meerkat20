<?php
class Alerts_Module extends FLBuilderModule {
    public $name = 'Alerts Module';
    public $description = 'This shows the latest Alert CPT if any, at the top of the screen';
    public $enabled = true;
    public static $fields = array(
        'my-tab-1' => array(
            'title'    => 'Tab 1',
            'sections' => array(
                'my-section-1' => array(
                    'title'  => 'Section 1',
                    'fields' => array(

                        // 'wms_alert_field' => array(
                        //     'type'              => 'suggest',
                        //     'label'             => 'Search and pick your alert: <br/><strong>(limit 1)</strong>',
                        //     'action'           => 'fl_as_posts', // Search posts.
                        //     'data'          => 'wms-alert', // Slug of the post type to search.
                        //     'limit'         => 1, // Limits the number of selections that can be made.
                        // ),

                        'icon' => array(
                            'type'          => 'icon',
                            'label'         => 'My Icon',
                            'show_remove'   => true,
                        ),
                    )
                )
            )
        )
    );

    //
    public $slug = __DIR__;
    public $cur_alert;

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
            'icon'            => 'megaphone.svg',
            'editor_export'   => true, // Defaults to true and can be omitted.
            'enabled'         => $this->enabled, // Defaults to true and can be omitted.
            'partial_refresh' => false, // Defaults to false and can be omitted.
        ));
        $this->cur_alert = self::query_alerts();

        self::kill_empty();

    }

    public static function get_form() {
        return self::$fields;
    }

    public function query_alerts() {
        ///get latest active alert
        $args = array(
            'numberposts'   => 1,
            'post_type'     => 'wms_alert',
            'meta_key'      => 'alert_active',
            'meta_value'    => 1,
        );
        $alerts = new WP_Query( $args );
        $cur_alert = $alerts->posts ? json_encode(current($alerts->posts)): false;
 
        return  $cur_alert;
    }

    public function kill_empty(){

        add_filter('fl_builder_is_node_visible', function($is_visible, $node) { 

           if ($node->name == "Alerts Module" && $node->cur_alert == false){
                apply_filters( 'fl_builder_is_node_visible', false, $node->parent );
                return false;
            } 

             return $is_visible;
        }, 10, 2);

    }
}

FLBuilder::register_module('Alerts_Module', Alerts_Module::get_form());

