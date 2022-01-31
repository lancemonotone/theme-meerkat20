<?php

class M20_BB {
    private static $instance;

    protected function __construct() {
        define('M20_BB_URL', get_stylesheet_directory_uri());
        define('M20_BB_DIR', get_theme_root() . '/m20');

        // load lib
        add_action('after_setup_theme', array(&$this, 'load_lib'));
        add_filter('fl_theme_builder_template_include', array(&$this, 'override_bb_themer_layout'), 999, 2);

        // load/unload plugins for this theme
        add_action('init', array(&$this, 'load_theme_plugins'));
        add_action('switch_theme', array(&$this, 'unload_theme_plugins'));

        // css and js
        add_action('wp_enqueue_scripts', array(&$this, 'enqueue_template_build'));

        // use native search for site search 
        add_shortcode('wp_search', array(&$this, 'm20_wp_search_shortcode'));

        // Allow editors and admin to save iframes
        add_filter('fl_builder_ui_js_config', function($config) {
            $config['userCaps']['unfiltered_html'] = true;

            return $config;
        }, 10
        );

        // Debug disable BB caching. This will cause modules in page partials
        // to be rendered without frontend styles (header, alert, etc).
        //add_action('wp', array(&$this, 'refresh_bb_cache'));
    }

    /**
     * Override render of themer layout and use Timberizer instead.
     * Pass rendered layout content via 'bb_themer_layout_content' filter,
     * which is picked up at the end of Timberizer::render_template().
     *
     * @param String $template
     * @param Int    $id
     *
     */
    function override_bb_themer_layout(string $template, int $id) {
        $ids = FLThemeBuilderLayoutData::get_current_page_content_ids();

        if (empty($ids)) {
            return;
        }

        if ('fl-theme-layout' == get_post_type() && count($ids) > 1) {
            $post_id = FLBuilderModel::get_post_id();
        } else {
            $post_id = $ids[0];
        }

        ob_start();
        FLBuilder::render_content_by_id($post_id, 'div', apply_filters('fl_theme_builder_content_attrs', array()));
        $bb_themer_layout_content = ob_get_clean();

        if ($bb_themer_layout_content) {
            add_filter('timberizer_before_render', function($context) use ($bb_themer_layout_content) {
                $context['bb_themer_layout_content'] = $bb_themer_layout_content;

                return $context;
            });

            // Get our original theme index.php and twig.
            $template = get_index_template();
            include($template);
            die();
        } else {
            return $template;
        }

    }

    public function load_lib() {
        require_once('lib/class.wp.php');
        require_once('lib/class.theme.php');
        require_once('lib/class.subtheme.php');
        require_once('lib/class.customizer.php');
        require_once('lib/class.shortcode.php');
        require_once('lib/class.admin.php');
        require_once('lib/class.cpt.php');
        require_once('lib/class.bb-custom-modules.php');
        require_once('lib/class.bb-custom-fields.php');
        require_once('lib/class.bb_assets_purge.php');
        require_once('lib/class.acf.php');
        require_once('lib/class.rest.php');

        /* Not working, but wouldn't this be nice?
        $dirs = glob(M20_BB_DIR . '/lib/*', GLOB_ONLYDIR);
        foreach ($dirs as $dir) {
            $file = substr($dir, strripos($dir, '/') + 1);
            if (file_exists($module = $dir . "/{$file}.php")) {
                require_once($module);
            }
        }*/
    }

    function unload_theme_plugins() {
        M16_Plugins::instance()->auto_deactivate_plugins([
            'bb-plugin/fl-builder.php',
            'bb-theme-builder/bb-theme-builder.php',
        ]);
    }

    function load_theme_plugins() {
        M16_Plugins::instance()->auto_activate_plugins([
            'bb-plugin/fl-builder.php',
            'bb-theme-builder/bb-theme-builder.php',
        ]);
    }

    public function enqueue_template_build($hook) {
        require_once('lib/class.enqueue.php');
    }


    public function refresh_bb_cache() {

        if (FLBuilderModel::is_builder_enabled()) {
            FLBuilder::render_js();
            FLBuilder::render_css();
        }

    }

    public function m20_wp_search_shortcode($atts) {
        // display native wp search with reasonable defaults
        extract(shortcode_atts(array(
            'echo'       => false,
            'aria_label' => 'search ' . get_bloginfo('name'),
        ), $atts));

        return get_search_form(array('echo' => $echo, 'aria_label' => $aria_label));
    }

    /**
     * Returns the singleton instance of this class.
     *
     * @return M20_BB The singleton instance.
     */
    public static function instance() {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * singleton instance.
     *
     * @return void
     */
    private function __clone() {
    }

    /**
     * Private unserialize method to prevent unserializing of the singleton
     * instance.
     *
     * @return void
     */
    private function __wakeup() {
    }
}

M20_BB::instance();
