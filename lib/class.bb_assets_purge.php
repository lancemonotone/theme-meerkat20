<?php

namespace m21;

class BB_Assets_Purge {
    public $action = 'bb-assets-purge';
    public $nonce;

    public function __construct() {
        add_action('admin_bar_menu', [&$this, 'add_toolbar_items'], 100);
        add_action('wp_enqueue_scripts', [&$this, 'add_scripts']);
        add_action('admin_footer', [&$this, 'add_scripts']);
        add_action('wp_ajax_' . $this->action, [&$this, 'purge_bb_callback']);

        $this->nonce = wp_create_nonce($this->action);
    }

    function add_toolbar_items(\WP_Admin_Bar $admin_bar) {
        if (is_super_admin()) {
            $admin_bar->add_menu(array(
                'id'    => $this->action,
                //'parent' => 'cache-purge',
                'title' => 'Purge BB Assets',
                'href'  => 'javascript:void(0)',
                'meta'  => array(
                    'title' => __('Purge Beaver Builder cached assets'),
                ),
            ));
        }
    }

    function add_scripts() {
        $script = function() {
            /** @lang ECMAScript 6 */
            return <<<EOD
  //add a click event for the purge assets btn in the admin bar
jQuery( '#wp-admin-bar-{$this->action} .ab-item' ).on( "click", function (e) {
  e.preventDefault;

    var data = {
      action: '{$this->action}',
      security: '{$this->nonce}'
    };
    
    // localized ajaxurl
    $.post( 
        MyAjax.ajaxurl, 
        data, 
        function ( response ) {
          alert( response );
          location.reload( true );
    });
});

EOD;
        };

        \Meerkat16_Js::instance()->do_load('admin-bar', array('inline' => $script));
    }

    function purge_bb_callback() {
        check_ajax_referer($this->action, 'security');

        if ( ! \FLBuilderAdmin::current_user_can_access_settings()) {
            return;
        } else {
            // Clear builder cache.
            \FLBuilderModel::delete_asset_cache_for_all_posts();

            echo 'BB cache purged';
            wp_die();
        }
    }
}

new BB_Assets_Purge();