<?php

/**
 * Enable support for BB in M20
 *
 * @since 1.0
 */
class M20_BB_Theme {
    private static $instance;

    private static $global_settings = array(
        'responsive_breakpoint'     => '910',
        'row_width'                 => '1200',
        'row_content_width_default' => 'fixed'
    );

    protected function __construct() {

        // Set up BB support
        add_theme_support('fl-theme-builder-headers');
        add_theme_support('fl-theme-builder-parts');
        add_filter('fl_theme_builder_part_hooks', array(&$this, 'register_part_hooks'));
        add_action('wp', array(&$this, 'setup_headers_and_footers'));

        // Uncomment this to import all layouts when theme is activated.
        //add_action('switch_theme', array(&$this, 'delete_themer_layouts'));

        // Uncomment this to delete all layouts when theme is deactivated.
        add_action('after_switch_theme', array(&$this, 'import_themer_layouts'));

        // add presets
        add_filter('fl_builder_color_presets', array(&$this, 'wms_builder_color_presets'));

        // set BB global settings
        add_action('init', array(&$this, 'save_global_settings'));

        // Change location of frontend.php to allow Timber to find views dir
        add_filter('fl_builder_module_frontend_file', array(&$this, 'set_frontend_path'), 10, 2);
        add_filter('fl_builder_render_module_html', array(&$this, 'render_frontend_file'), 10, 4);

    }

    /**
     * Setup parts.
     *
     * @return array
     * @since 1.0
     */

    function register_part_hooks() {
        return array(
            array(
                'label' => __('Header', 'fl-theme-builder'),
                'hooks' => array(
                    'meerkat_site_header'  => 'Header',
                    'M20_BB_before_header' => 'Alert',
                    'M20_BB_sidebar'       => 'Sidebar',
                    'M20_BB_entry_footer'  => 'Entry Footer'
                ),
            ),
        );
    }

    /**
     * Setup headers and footers.
     *
     * @return void
     * @since 1.0
     */
    static public function setup_headers_and_footers() {
        add_action('meerkat_site_header', 'FLThemeBuilderLayoutRenderer::render_header', 999);
    }

    /**
     * Modify path to module controller to allow us to more easily integrate twig view directory.
     *
     * @param $file
     * @param $module
     *
     * @return string
     */
    public function set_frontend_path($file, $module) {

        if (file_exists($file)) return $file;

        // Probably a Williams module
        $file = $module->dir . 'frontend.php';

        return $file;
    }

    public function render_frontend_file($file, $type, $settings, $module) {
        return $this->set_frontend_path($file, $module);
    }

    static public function save_global_settings() {
        $old_settings = FLBuilderModel::get_global_settings();
        $settings     = FLBuilderModel::sanitize_global(self::$global_settings);
        $new_settings = (object) array_merge((array) $old_settings, (array) $settings);

        update_option('_fl_builder_settings', $new_settings);
    }

    /**
     * Add color presets to Beaver Builder
     *
     * @return array
     * @since 1.0
     */

    function wms_builder_color_presets($colors) {
        $colors = array();

        $colors[] = '500082'; // wms purple
        $colors[] = '280050'; // dark purple
        $colors[] = 'FFBE0A'; // marigold
        $colors[] = 'B1008E'; // magenta
        $colors[] = 'C86914'; // ochre
        $colors[] = 'FF7800'; // orange
        $colors[] = '000000'; // black 
        $colors[] = 'ffffff'; // white

        return $colors;
    }

    /**
     * Deletes all layouts when theme is deactivated.
     * Not currently used.
     */
    function delete_themer_layouts() {
        require_once(M20_BB_DIR . '/includes/class.cpt-delete.php');
        CPT_Delete::instance()->delete_posts('fl-theme-layout');
    }

    /**
     * Auto-import standard themer layouts when theme is activated.
     */
    function import_themer_layouts() {
        define('WP_LOAD_IMPORTERS', true);
        require_once(M20_BB_DIR . '/includes/importer/class.importer.php');
        //console_log('import_layout: ' . WP_LOAD_IMPORTERS);

        if (class_exists('WP_Import')) {
            //console_log('doing import');
            $importer = new WP_Import();
            $file     = get_stylesheet_directory() . '/import/import.xml';
            if (file_exists($file)) {
                $importer->import($file);
            }
        }

    }

    /**
     * Returns the singleton instance of this class.
     *
     * @return M20_BB_Theme The singleton instance.
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

M20_BB_Theme::instance();
