<?php 
/**
Plugin Name: SB Related Posts
Plugin URI: http://spellbit.com
Author: Mohammad Tajul Islam
Author URI: https://www.facebook.com/developer.tajul
Version: 1.0.2
Description: Premium Type Related Posts
License: GPLv2 or later
Text Domain: comet
*/
defined('ABSPATH') or die("Get Lost from here, you idot");


add_filter('the_content', 'sbrp_related_posts');

function sbrp_related_posts($def){
	global $post;


	if( is_single() ){

			$cats = get_the_terms($post->ID, 'category');

			$cat_ids = array();

			if( is_array($cats) ){
				foreach ($cats as $key => $value) {
					$cat_ids[] = $value->name;
				}
			}

			$q = new WP_Query(array(
				'post_type'			=> 'post',
				'posts_per_page'	=> 3,
				'category__in'		=> array($cat_ids),
				'post__not_in'		=> array($post->ID)
			));
		?>
		
				<?php 
					while( $q->have_posts() ):$q->the_post(); ?>
					
					<?php 
						$def .= "<div class='related_post_item'>";

					if(has_post_thumbnail()){
						$def .= 	"<div class='r_post_img_wrapper'>";
						$def .= 		'<a href="'.get_permalink().'">'.get_the_post_thumbnail( null, 'thumbnail', array('class' => '') ).'</a>';
						$def .= 	"</div>";
					}
						
						$def .= 	"<div class='r_post_title'>";
						$def .= 		'<h4><a href="'.get_permalink().'">'.get_the_title().'</a></h4>';
						$def .= 	"</div>";

						
						$def .= 	"<div class='r_post_text'>";
						$def .= 		'<p>'.wp_trim_words(get_the_content(), '20', '...').'</p>';
						$def .= 	"</div>";

						$def .= "</div>";
					?>

				<?php 
					endwhile; wp_reset_postdata(); ?>		
	
		<?php	return $def;	

	}

}




add_action('wp_enqueue_scripts', 'sbrp_theme_files', 12);


// plugin all css and js files
function sbrp_theme_files(){

	/**
	* css files
	*/		
	wp_register_style('sbrp-related-posts-style', Plugins_url('/css/related-posts-style.css', __FILE__), array(), '7.0.0', 'all');
	wp_enqueue_style('sbrp-related-posts-style');


	/**
	* js files
	*/
	wp_register_script('sbrp-related-posts-custom', Plugins_url('/js/related-posts-custom.js', __FILE__), array('jquery'), '6.0.1', true);
	wp_enqueue_script('sbrp-related-posts-custom');

}
