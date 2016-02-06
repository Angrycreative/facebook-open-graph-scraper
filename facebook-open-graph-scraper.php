<?php
/**
 * Plugin Name: Facebook Open Graph Scraper
 * Plugin URI: http://angrycreative.se
 * Description: On save post, send post url to facebook and re scrape open graph information.
 * Version: 1.2.1
 * Author: viktorfroberg
 * Author URI: http://angrycreative.se
 * License: GPLv2
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

class AC_Facebook_Open_Graph_Scraper {

    function __construct(){
        add_action( 'save_post', array($this, 'post_scraper'), 90, 2);
    }
    /**
	 * Filter what post types should be re-scraped
	 * @since    1.0.0
	 * @param post_types array
	 */
    function get_post_types() {
        return apply_filters( 'fogs_post_types', get_post_types());
    }

	 /**
	 *	On save post, update facebook post cache
	 */
	function post_scraper($post_id, $post) {

		/**
		 * Filter what post types should be re-scraped
		 * @since    1.0.0
		 * @param post_types array
		 */
		$post_types = $this->get_post_types();

		if(in_array($post->post_type, $post_types) || empty($post_types)){

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
		}
	}
}

add_action( 'plugins_loaded', 'load_ac_facebook_open_graph_scraper' );

function load_ac_facebook_open_graph_scraper() {
	if ( is_admin() ) {
  		new AC_Facebook_Open_Graph_Scraper();
	}	
}
