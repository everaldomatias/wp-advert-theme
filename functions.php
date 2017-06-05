<?php

/**
 * Load site scripts.
 *
 * @since 2.2.0
 */
function adv_enqueue_scripts() {

	/* CSS do tema pai Twenty Seventeen */
	wp_enqueue_style( 'twentyseventeen', get_template_directory_uri() . '/style.css' );

}

add_action( 'wp_enqueue_scripts', 'adv_enqueue_scripts', 1 );


/**
 * Remove the slug from published post permalinks. Only affect our custom post type, though.
 */
function adv_custom_parse_request( $query ) {

    // Only noop the main query
    if ( ! $query->is_main_query() )
        return;

    // Only noop our very specific rewrite rule match
    if ( 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
        return;
    }

    // 'name' will be set if post permalinks are just post_name, otherwise the page rule will match
    if ( ! empty( $query->query['name'] ) ) {
        $query->set( 'post_type', array( 'advert', 'post', 'page' ) );
    }
}
add_action( 'pre_get_posts', 'adv_custom_parse_request' );
function adv_remove_cpt_slug( $post_link, $post, $leavename ) {

    if ( 'advert' != $post->post_type  || 'publish' != $post->post_status ) {
        return $post_link;
    }

    $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );

    return $post_link;
}
add_filter( 'post_type_link', 'adv_remove_cpt_slug', 10, 3 );


function mfields_test_remove_actions() {
    remove_action( 'the_content', 'adverts_the_content' );
}
add_action( 'wp_head', 'mfields_test_remove_actions' );


/**
 * Dynamically replace post content with Advert template.
 * 
 * This function is applied to the_content filter.
 * 
 * @global WP_Query $wp_query
 * @param string $content
 * @since 0.1
 * @return string
 */
function adv_adverts_the_content($content) {
    global $wp_query;
    
    if (is_singular('advert') && in_the_loop() ) {
        ob_start();
        $post_id = get_the_ID();
        $post_content = $content;
        include apply_filters( "adverts_template_load", get_stylesheet_directory() . '/templates/single-advert.php' );
        $content = ob_get_clean();
    } elseif( is_tax( 'advert_category' ) && in_the_loop() ) {
        add_action( 'adverts_sh_list_before', 'adverts_list_show_term_description' );
        $content = shortcode_adverts_list(array(
            "category" => $wp_query->get_queried_object_id()
        ));
        remove_action( 'adverts_sh_list_before', 'adverts_list_show_term_description' );
    }

    return $content;
}

add_filter( 'the_content', 'adv_adverts_the_content' );
