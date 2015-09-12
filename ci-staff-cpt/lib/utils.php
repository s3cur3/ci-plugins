<?php




/**
 * A wrapper for getting settings created by the Meta Box plugin.
 * @param $fieldID string The ID (sans our theme's prefix) of the meta value you want to retrieve.
 *                         E.g., to get the "show page title" setting, pass in "show_page_title", not
 *                         "mlf_show_page_title".
 * @param $valueIfNotSet mixed Whatever you want to use as the default (in the event the
 *                             requested key is not set for the current post/page)
 * @param int $overridePostID Force us to look up the meta for a post with a specific ID
 * @return mixed The stored meta value, or $valueIfNotSet
 */
if( !function_exists('ciGetNormalizedMeta') ) {
    /**
     * @param string $fieldID The string identifier for the meta field you're looking for
     * @param mixed $valueIfNotSet The default value, in the event this meta value isn't set for the current post
     * @param {int|null} $overridePostID The post ID to search for. If empty, we will use whatever get_the_id() returns.
     * @return mixed The current post's value for the field you requested
     */
    function ciGetNormalizedMeta( $fieldID, $valueIfNotSet, $overridePostID=null ) {
        if( function_exists('rwmb_meta') ) {
            $field = rwmb_meta( CI_THEME_PREFIX . "_{$fieldID}", array(), $overridePostID );
            if( $field === "" ) {
                $field = $valueIfNotSet;
            }
            return $field;
        } else {
            return $valueIfNotSet;
        }

    }
}






 