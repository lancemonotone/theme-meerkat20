<?php

class CPT_Delete {
    private static $instance;
    public $unused_cpts;

    protected function __construct() {

    }

    /**
     * Delete posts from the database.
     *
     * @since 1.0.0
     * @param string $post_type Post type to delete posts from.
     * @param int    $limit     Number of posts to delete. Default 100;
     * @return string Admin notices.
     */
    public static function delete_posts( $post_type, $limit = 100 ) {
        global $wpdb;

        $msg       = '';

        if ( empty( $post_type ) || ! post_type_exists( $post_type ) ) {
            $msg = __( 'Error: invalid post type', 'custom-post-type-cleanup' );
            return '<div class="error"><p>' . $msg . '</p></div>';
        }

        // Get post ids for this post type in batches.
        $db_post_ids = self::cptc_get_post_ids( $post_type, $limit );

        if ( empty( $db_post_ids ) ) {
            /* translators: %s: post type name */
            $no_posts_msg = __( 'Notice: No posts found for the post type: %s', 'custom-post-type-cleanup' );
            $msg = sprintf( $no_posts_msg , $post_type );
            return '<div class="notice"><p>' . $msg . '</p></div>';
        }

        $deleted = 0;
        foreach ( $db_post_ids as $post_id ) {
            $del = wp_delete_post( $post_id );
            if ( false !== $del ) {
                ++$deleted;
            }
        }

        if ( $deleted ) {
            /* translators: 1: deleted posts count, 2: post type name  */
            $updated = _n(
                'Deleted %1$d post from the post type: %2$s',
                'Deleted %1$d posts from the post type: %2$s',
                $deleted,
                'custom-post-type-cleanup'
            );

            $updated = sprintf( $updated, $deleted, $post_type );
            $msg = '<div class="updated"><p>' . $updated . '</p></div>';
        }

        // Check if there more posts from this post type to delete.
        $count = absint( self::cptc_get_posts_count( $post_type ) );

        if ( $count ) {

            /* translators: 1: posts count, 2: post type name  */
            $notice = _n(
                'Still %1$d post left in the database from the post type: %2$s',
                'Still %1$d posts left in the database from the post type: %2$s',
                $count,
                'custom-post-type-cleanup'
            );

            $notice = sprintf( $notice , $count, $post_type );

            $msg .= '<div class="notice"><p>' . $notice . '</p></div>';
        } else {
            /* No more posts from this post type left in the database. */

            $key = array_search( $post_type, self::instance()->unused_cpts );
            if ( false !== $key ) {
                unset( self::instance()->unused_cpts[ $key ] );

                /* translators: %s: post type name */
                $notice = __( 'No more posts left in the database from the post type: %s', 'custom-post-type-cleanup' );
                $notice = sprintf( $notice, $post_type );
                $msg .= '<div class="notice"><p>' . $notice . '</p></div>';
            }
        }

        return $msg;
    }

    /**
     * Get post types no longer in use.
     *
     * @since  1.2.0
     * @return array Array with unused post type names.
     */
    static function cptc_get_unused_post_types() {
        self::instance()->unused_cpts   = array();
        $post_types    = array_keys( get_post_types() );
        $db_post_types = self::cptc_db_post_types();

        if ( ! empty( $db_post_types ) ) {
            self::instance()->unused_cpts = array_diff( $db_post_types, $post_types );
        }
        return self::instance()->unused_cpts;
    }

    /**
     * Returns post types in the database.
     *
     * @since 1.2.0
     * @return array Array with post types in the database.
     */
    static function cptc_db_post_types() {
        global $wpdb;
        $query = "SELECT DISTINCT post_type FROM $wpdb->posts";
        return $wpdb->get_col( $query );
    }

    /**
     * Returns post type posts count for a post type.
     * Todo: check if wp_count_posts can be used for this.
     *
     * @since 1.2.0
     * @param string $post_type Post type.
     * @return integer Post count for a post type.
     */
    static function cptc_get_posts_count( $post_type ) {
        global $wpdb;
        $query = "SELECT COUNT(p.ID) FROM $wpdb->posts AS p WHERE p.post_type = %s";
        return $wpdb->get_var( $wpdb->prepare( $query, $post_type ) );
    }

    /**
     * Returns post ids from a post type.
     *
     * @since 1.2.0
     * @param string  $post_type Post type.
     * @param integer $limit     Limit how many ids are returned. Default 100.
     * @return array Array with post ids.
     */
    static function cptc_get_post_ids( $post_type, $limit = 100 ) {
        global $wpdb;

        if ( ! absint( $limit ) ) {
            return array();
        }

        $query = "SELECT p.ID FROM $wpdb->posts AS p WHERE p.post_type IN (%s) LIMIT %d";

        return $wpdb->get_col( $wpdb->prepare( $query, $post_type, absint( $limit ) ) );
    }

    /**
     * Get post types from plugin transient.
     *
     * @since  1.2.0
     * @return array Array with unused post type names or empty array.
     */
    static function cptc_get_transient_post_types() {
        $post_types = get_transient( 'custom_post_type_cleanup_unused_post_types' );
        if ( ! ( is_array( $post_types ) && ! empty( $post_types ) ) ) {
            return array();
        }

        return $post_types;
    }

    /**
     * Get time left for the plugin transient.
     *
     * @since  1.2.0
     * @return int Minutes left.
     */
    static function cptc_get_transient_time() {
        $transient = 'custom_post_type_cleanup_unused_post_types';
        $time      = get_option( "_transient_timeout_{$transient}" );
        return $time ? self::cptc_get_time_diff_in_minutes( $time ) : 0;
    }

    /**
     * Returns minutes left from two time stamps.
     *
     * @since  1.2.0
     *
     * @param int $from Unix timestamp from which the difference begins.
     * @param int $to   Unix timestamp to end the time difference. Default becomes time() if not set.
     * @return int   Minutes left.
     */
    static function cptc_get_time_diff_in_minutes( $from, $to = '' ) {
        if ( empty( $to ) ) {
            $to = time();
        }

        $diff = (int) abs( $to - $from );

        if ( ! $diff ) {
            return 0;
        }

        $mins = round( $diff / MINUTE_IN_SECONDS );

        if ( 1 >= $mins ) {
            $mins = 1;
        }

        return $mins;
    }

    /**
     * Returns the singleton instance of this class.
     *
     * @return CPT_Delete The singleton instance.
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

CPT_Delete::instance();