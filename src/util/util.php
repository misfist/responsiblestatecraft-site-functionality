<?php
/**
 * Utilies
 */
namespace Site_Functionality\Util;

/**
 * Update `_links_to` meta
 *
 * @return void
 */
function update_links() {
    $args = array( 
        'post_type'         =>  'expert',
        'posts_per_page'    => -1,
        'fields'            => 'ids'
    );

    $has_run = get_option( 'update_links_run' );

    if( !$has_run ) {
        $posts = get_posts( $args );

        foreach( $posts as $post_id ) {
            if( $link = get_post_meta( $post_id, 'link_to_article', true ) ) {
                update_post_meta( $post_id, '_links_to', $link );
                update_post_meta( $post_id, '_links_to_target', '_blank' );
            }
        }

        update_option( 'update_links_run', true );
    }

}
