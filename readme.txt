=== Facebook Open Graph Scraper ===
Contributors: angrycreative, viktorfroberg
Tags: Facebook, Angry Creative, Open Graph, Social
Requires at least: 3.5
Tested up to: 4.4.2
Stable tag: 1.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

On save post, send post url to facebook and re scrape open graph information.

== Description ==

On save post, send post url to facebook to re scrape open graph information.

= Filters =
`fogs_post_types`: filter what post types should be re-scraped. Should return array of post types.
 

Example: Only re-scrape the post type page

`
add_filter('fogs_post_types', function(){
	return array('page');
});
`


== Installation ==

1. Download, unzip and upload the plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress (activate for network if multisite)

== Changelog ==

= 1.2.1 =
* Fix incompatibility with the W3 Total Cache plugin (Thanks to Ricky Rydén).
* Compatible with WordPress 4.4.2.
* Fix typo in changelog.

= 1.2.0 =
* Rewrite plugin.

= 1.1.0 =
* Changed from httprequest to wp-remote-post.
* Added admin notice on scrape error.

= 1.0.0 =
* First public release.
