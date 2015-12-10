<?php
/**
 * Plugin Name: Facebook Open Graph Scraper.
 * Plugin URI: http://angrycreative.se
 * Description: On save post, send post url to facebook and re scrape open graph information.
 * Version: 1.1.0
 * Author: viktorfroberg
 * Author URI: http://angrycreative.se
 * License: GPLv2
 */


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 *	On save post, update facebook post cache
 */
function fogs_post_scraper($post_id, $post) {
	/**
	 * Filter what post types should be re-scraped
	 * @param post_types array
	 */
	$post_types = apply_filters( 'fogs_post_types', get_post_types());

	if(in_array($post->post_type, $post_types)){
		$url = get_permalink($post_id);
		$response = wp_remote_post( 'https://graph.facebook.com/', array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'blocking' => false,
			'headers' => array(),
			'body' => array( 'id' => $url, 'scrape' => 'true' )
		    )
		);

		if ( is_wp_error( $response ) ) {
		   $error_message = $response->get_error_message();

		   add_filter( 'redirect_post_location', 'fogs_add_notice_query_var', 99 );

		}
	}

}
add_action( 'save_post', 'fogs_post_scraper', 10, 2);


function fogs_add_notice_query_var( $location ) {
   remove_filter( 'redirect_post_location', 'add_notice_query_var', 99 );
   return add_query_arg( array( 'SCRAPE_ERROR' => 'ID' ), $location );
  }

function fogs_admin_notices() {
   if ( ! isset( $_GET['SCRAPE_ERROR'] ) ) {
     return;
   }
   ?>
   <div class="error">
      <p><?php esc_html_e( 'Could not re-scrape Facebook meta data', 'fogs' ); ?></p>
   </div>
   <?php
  }
  add_action( 'admin_notices', 'fogs_admin_notices' );