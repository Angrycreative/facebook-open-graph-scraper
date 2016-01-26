<?php
/**
 * Plugin Name: Facebook Open Graph Scraper.
 * Plugin URI: http://angrycreative.se
 * Description: On save post, send post url to facebook and re scrape open graph information.
 * Version: 1.2.0
 * Author: viktorfroberg
 * Author URI: http://angrycreative.se
 * License: GPLv2
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

class AC_Facebook_Open_Graph_Scraper {
	/**
     * Plugin version, used for autoatic updates and for cache-busting of style and script file references.
     *
     * @since    1.2.0
     * @var     string
     */
    const VERSION = '1.2.0';

    /**
     * Unique identifier for the plugin.
     * This value is used as the text domain when internationalizing strings of text. It should
     * match the Text Domain file header in the main plugin file.
     *
     * @since    1.2.0
     * @var      string
     */
    public $plugin_slug = 'fogs';

    /**
     * Holds the global `$error_message` variable's value.
     *
     * @since    1.2.0
     * @var string
     */
    private $error_message = '';

    function __construct(){
        add_action( 'save_post', array($this, 'post_scraper'), 10, 2);
        add_action( 'admin_notices', array($this, 'admin_notices') );
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
			
			if ( is_wp_error( $response ) ) {
			   $this->error_message = $response->get_error_message();

			   add_filter( 'redirect_post_location', array($this, 'add_notice_query_var'), 99 );

			}
		}

	}

	function add_notice_query_var( $location ) {
	   remove_filter( 'redirect_post_location', 'add_notice_query_var', 99 );
	   return add_query_arg( array( 'SCRAPE_ERROR' => 'ID' ), $location );
	  }

	function admin_notices() {
	   if ( ! isset( $_GET['SCRAPE_ERROR'] ) ) {
	     return;
	   }
	   ?>
	   <div class="error">
	      <p><?php esc_html_e( 'Could not re-scrape Facebook meta data', $this->plugin_slug ); ?>
	      </br>
	      	<?php echo $this->error_message; ?>
	      </p>
	   </div>
	   <?php
	}

  }

  new AC_Facebook_Open_Graph_Scraper();