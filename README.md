# Facebook Open Graph Scraper
On save post, send post url to facebook to re scrape open graph information.

## Filters
`fogs_post_types`: filter what post types should be re-scraped. Should return array of post types.
 

Example: Only re-scrape the post type page

```
add_filter('fogs_post_types', function(){
	return array('page');
});
```