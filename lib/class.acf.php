<?php

/**
 * Class M20_BB_Acf
 *
 * @see https://www.advancedcustomfields.com/resources/custom-location-rules/
 * @see https://www.billerickson.net/acf-custom-location-rules/
 */
class M20_BB_Acf {

    public function __construct() {
        // Next 3 filters add a Site field to the ACF location rules dropdown.
        add_filter('acf/location/rule_types', array(__CLASS__, 'acf_rule_type_site_id'));
        add_filter('acf/location/rule_values/site_id', array(__CLASS__, 'acf_rule_values_site_id'));
        add_filter('acf/location/rule_match/site_id', array(__CLASS__, 'acf_location_rule_match_site_id'), 10, 3);
    }

    function acf_rule_type_site_id($choices) {
        $choices['Site']['site_id'] = 'Site';

        return $choices;
    }

    function acf_rule_values_site_id($choices) {
        $sites = get_sites(array(
            'public'  => 1,
            'number'  => 500,
            'orderby' => 'domain'
        ));
        foreach ($sites as $site) {
            $choices[ $site->id ] = WP_Site::get_instance($site->id)->blogname;
        }

        return $choices;
    }

    function acf_location_rule_match_site_id($match, $rule, $screen) {
        $site  = get_current_blog_id();
        $selected_site = (int) $rule['value'];

        if ($rule['operator'] == "==") {
            $match = ($site == $selected_site);
        } elseif ($rule['operator'] == "!=") {
            $match = ($site != $selected_site);
        }

        return $match;
    }
}

new M20_BB_Acf();