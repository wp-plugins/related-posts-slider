<?php
/*
Plugin Name: Related Posts Slider
Plugin URI: http://www.clickonf5.org/related-posts-slider
Description: Related posts slider creates a very attractive slider of the related posts or/and pages for a WordPress post or page. The slider is a lightweight jQuery implementation of the related post functionality. Watch Live Demo at <a href="http://www.clickonf5.org/">Internet Techies</a>.
Version: 2.0	
Author: Internet Techies
Author URI: http://www.clickonf5.org/about/tejaswini
WordPress version supported: 3.0 and above
*/

/*  Copyright 2011  Internet Techies  (email : tedeshpa@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( ! defined( 'CF5_RPS_PLUGIN_BASENAME' ) )
	define( 'CF5_RPS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
if ( ! defined( 'CF5_RPS_CSS_DIR' ) )
	define( 'CF5_RPS_CSS_DIR', WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'/css/' );
define("CF5_RPS_VER","2.0",false);
define('CF5_RPS_URLPATH', trailingslashit( WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) ) );
if ( ! defined( 'CF5_RPS_FORMAT_DIR' ) )
	define( 'CF5_RPS_FORMAT_DIR', WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'/formats/h_carousel/styles/' );
if ( ! defined( 'CF5_RPS_DEFAULT_STYLES_DIR' ) )
	define( 'CF5_RPS_DEFAULT_STYLES_DIR', WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'/styles/' );

function cf5_rps_url( $path = '' ) {
	return plugins_url( $path, __FILE__ );
}
// Create Text Domain For Translations
load_plugin_textdomain('cf5_rps', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
//on activation, your Related Posts Slider options will be populated. Here a single option is used which is actually an array of multiple options
function activate_cf5_rps() {
	$cf5_rps_opts1 = get_option('cf5_rps_options');
	$cf5_rps_opts2 =array('per_page' => '4',
	                   'height'=>'250',
					   'hwidth'=>'120',
					   'scroll'=>'1',
					   'stylesheet' => 'default',
					   'bgcolor'=>'#ffffff',
					   'fgcolor'=>'#f1f1f1',
					   'hvcolor'=>'#6d6d6d',
					   'hvtext_color'=>'#ffffff',
					   'obrwidth'=>'1',
					   'obrcolor'=>'#F1F1F1',
					   'ibrwidth'=>'1',
					   'ibrcolor'=>'#DFDFDF',
					   'img_align'=>'none',
					   'img_width'=>'100',
					   'img_pick'=>array('1','preview_thumb','1','1','1','1'), //use custom field/key, name of the key, use post featured image, pick the image attachment, attachment order,scan images
					   'img_height'=>'100',
					   'crop'=>'0',
					   'sldr_title'=>'Related Posts',
					   'stitle_font'=>'Georgia,Times New Roman,Times,serif',
					   'stitle_color'=>'#333333',
					   'stitle_size'=>'14',
					   'stitle_weight'=>'bold',
					   'stitle_style'=>'normal',
					   'ltitle_font'=>'Verdana,Geneva,sans-serif',
					   'ltitle_size'=>'12',
					   'ltitle_weight'=>'bold',
					   'ltitle_style'=>'normal',
					   'ltitle_color'=>'#444444',
					   'ltitle_words'=>'8',
					   'ptitle_font'=>'Georgia,Times New Roman,Times,serif',
					   'ptitle_size'=>'16',
					   'ptitle_weight'=>'bold',
					   'ptitle_style'=>'normal',
					   'ptitle_color'=>'#444444',
					   'pcontent_from'=>'content',
					   'pcontent_font'=>'Verdana,Geneva,sans-serif',
					   'pcontent_size'=>'12',
					   'pcontent_color'=>'#333333',
					   'pcontent_words'=>'30',
					   'show_custom_fields'=>'0',
					   'more'=>'READ MORE',
					   'no_more'=>'0',
					   'target'=>'_self',
					   'allowable_tags'=>'',
					   'insert'=>'content_down',
					   'support' => '1',
					   'format' => 'default', 
					   'plugin' => 'yarpp',
					   'format_style' => 'plain');
	if ($cf5_rps_opts1) {
	    $cf5_rps = $cf5_rps_opts1 + $cf5_rps_opts2;
		update_option('cf5_rps_options',$cf5_rps);
	}
	else {
		$cf5_rps_opts1 = array();	
		$cf5_rps = $cf5_rps_opts1 + $cf5_rps_opts2;
		add_option('cf5_rps_options',$cf5_rps);		
	}
}

register_activation_hook( __FILE__, 'activate_cf5_rps' );
global $cf5_rps,$rps_slider_shown;
$cf5_rps = get_option('cf5_rps_options');
require_once (dirname (__FILE__) . '/includes/cf5-rps-get-the-image.php');
require_once (dirname (__FILE__) . '/includes/cf5-rps-slider-formats.php');

function cf5_rps_wp_init() {
    global $cf5_rps;
    //format of the slider	
	$format = $cf5_rps['format'];
	if(!empty($format) and $format) {
	  $rps_func = 'cf5_rps_wp_init_'.$format;
	}
	else {
	  $rps_func = 'cf5_rps_wp_init_default';
	}
	if(!function_exists($rps_func)) {
	  $rps_func = 'cf5_rps_wp_init_default';
	}
	$rps_func();
}

add_action( 'wp', 'cf5_rps_wp_init' );

function cf5_rps_wp_head() {
    global $cf5_rps; 
	//format of the slider	
	$format = $cf5_rps['format'];
	if(!empty($format) and $format) {
	  $rps_func = 'cf5_rps_wp_head_'.$format;
	}
	else {
	  $rps_func = 'cf5_rps_wp_head_default';
	}
	if(!function_exists($rps_func)) {
	  $rps_func = 'cf5_rps_wp_head_default';
	}
	$rps_func();
}

add_action( 'wp_head', 'cf5_rps_wp_head' );

function cf5_rps_wp_footer() {
    global $cf5_rps; 
	//format of the slider	
	$format = $cf5_rps['format'];
	if(!empty($format) and $format) {
	  $rps_func = 'cf5_rps_wp_footer_'.$format;
	}
	else {
	  $rps_func = 'cf5_rps_wp_footer_default';
	}
	if(!function_exists($rps_func)) {
	  $rps_func = 'cf5_rps_wp_footer_default';
	}
	$rps_func(); 
}

add_action( 'wp_footer', 'cf5_rps_wp_footer' );

function get_related_posts_slider($echo=true,$type=array('post')){
    global $cf5_rps;
	$related_plugin = $cf5_rps['plugin'];
	if(empty($related_plugin) or !$related_plugin) {
	  $related_plugin = 'yarpp';
	}

//if using YARPP	
	if($related_plugin == 'yarpp') {
		if(function_exists(yarpp_related)){
		  $rps_posts=get_cf5_yarpp_related_posts($type,array(),false);
		}
	}
//if using WordPress Related Posts
    if($related_plugin == 'wp_rp') {
		if(function_exists('wp_get_related_posts')){
		  $rps_posts=get_cf5_wp_rp_related_posts();
		}
	}
	
//format of the slider	
	$format = $cf5_rps['format'];
	if(!empty($format) and $format) {
	  $rps_func = 'cf5_rps_'.$format;
	}
	else {
	  $rps_func = 'cf5_rps_default';
	}
	if(!function_exists($rps_func)) {
	  $rps_func = 'cf5_rps_default';
	}
	return $rps_func($echo,$rps_posts);
}

function cf5_rps_automatic_insertion($content){
 global $cf5_rps,$post,$wp_query;
	 if(is_singular()) {
		if($cf5_rps['insert']=='content_down'){
		   $content=$content.'&nbsp;[rps]';
		}
		if($cf5_rps['insert']=='content_up'){
		   $content='[rps]&nbsp;'.$content;
		}
	 }
	return $content;
}
if($cf5_rps['insert']=='content_down' or $cf5_rps['insert'] == 'content_up') {
   add_filter( 'the_content', 'cf5_rps_automatic_insertion', 5 );
}

function cf5_rps_shortcode($atts) {
	extract(shortcode_atts(array(
	), $atts));

	if(is_singular()){
	   return get_related_posts_slider($echo=false);
	}
	else{return '';}
}
add_shortcode('rps', 'cf5_rps_shortcode');

function get_cf5_wp_rp_related_posts() {
if(function_exists('wp_get_related_posts')):
	global $wpdb, $post;
	$wp_rp = get_option("wp_rp");
	
	$wp_rp_title = $wp_rp["wp_rp_title"];
	
	$exclude = explode(",",$wp_rp["wp_rp_exclude"]);	
	if ( $exclude != '' ) {
		$q = 'SELECT tt.term_id FROM '. $wpdb->term_taxonomy.'  tt, ' . $wpdb->term_relationships.' tr WHERE tt.taxonomy = \'category\' AND tt.term_taxonomy_id = tr.term_taxonomy_id AND tr.object_id = '.$post->ID;

		$cats = $wpdb->get_results($q);
		
		foreach(($cats) as $cat) {
			if (in_array($cat->term_id, $exclude) != false){
				return;
			}
		}
	}
		
	if(!$post->ID){return;}
	$now = current_time('mysql', 1);
	$tags = wp_get_post_tags($post->ID);
	
	$taglist = "'" . $tags[0]->term_id. "'";
	
	$tagcount = count($tags);
	if ($tagcount > 1) {
		for ($i = 1; $i < $tagcount; $i++) {
			$taglist = $taglist . ", '" . $tags[$i]->term_id . "'";
		}
	}
	
	$limit = $wp_rp["wp_rp_limit"];
	if ($limit) {
		$limitclause = "LIMIT $limit";
	}	else {
		$limitclause = "LIMIT 10";
	}
	
	$q = "SELECT p.ID, p.post_title, p.post_content,p.post_excerpt, p.post_date,  p.comment_count, count(t_r.object_id) as cnt FROM $wpdb->term_taxonomy t_t, $wpdb->term_relationships t_r, $wpdb->posts p WHERE t_t.taxonomy ='post_tag' AND t_t.term_taxonomy_id = t_r.term_taxonomy_id AND t_r.object_id  = p.ID AND (t_t.term_id IN ($taglist)) AND p.ID != $post->ID AND p.post_status = 'publish' AND p.post_date_gmt < '$now' GROUP BY t_r.object_id ORDER BY cnt DESC, p.post_date_gmt DESC $limitclause;";
	
	$related_posts = $wpdb->get_results($q);
		
	if (!$related_posts){
		$wp_no_rp = $wp_rp["wp_no_rp"];
		$wp_no_rp_text = $wp_rp["wp_no_rp_text"];
	
		if(!$wp_no_rp || ($wp_no_rp == "popularity" && !function_exists('akpc_most_popular'))) $wp_no_rp = "text";
		
		if($wp_no_rp == "text"){
		}	else{
			if($wp_no_rp == "random"){
				$related_posts = wp_get_random_posts($limitclause);
			}	elseif($wp_no_rp == "commented"){
				$related_posts = wp_get_most_commented_posts($limitclause);
			}	elseif($wp_no_rp == "popularity"){
				$related_posts = wp_get_most_popular_posts($limitclause);
			}
			$wp_rp_title = $wp_no_rp_text;
		}
	}
	
	$rps_posts = array();
	foreach ($related_posts as $related_post ){
	  $rps_posts[]=$related_post->ID;
    }
	return $rps_posts;
 endif;   
}

function get_cf5_yarpp_related_posts($type,$args,$reference_ID=false) {
if(function_exists(yarpp_related)):
	global $wpdb, $post, $userdata, $yarpp_time, $yarpp_demo_time, $wp_query, $id, $page, $pages, $authordata, $day, $currentmonth, $multipage, $more, $pagenow, $numpages, $yarpp_cache, $yarpp;
	
	get_currentuserinfo();

	// set the "domain prefix", used for all the preferences.
	$domainprefix = '';
	
if ( !$reference_ID )
			$reference_ID = get_the_ID();
	
		// if we're already in a YARPP loop, stop now.
		if ( $yarpp->cache->is_yarpp_time() || $yarpp->cache_bypass->is_yarpp_time() )
			return false;

		$options = array( 'domain', 'limit', 'use_template', 'order', 'template_file', 'promote_yarpp' );
		extract( $yarpp->parse_args( $args, $options ) );

		$cache_status = $yarpp->cache->enforce($reference_ID);
		// If cache status is YARPP_DONT_RUN, end here without returning or echoing anything.
		if ( YARPP_DONT_RUN == $cache_status )
			return;
		
		if ( YARPP_NO_RELATED == $cache_status ) {
			// There are no results, so no yarpp time for us... :'(
		} else {
			// Get ready for YARPP TIME!
			$yarpp->cache->begin_yarpp_time($reference_ID, $args);
		}
	
		// so we can return to normal later
		$current_query = $wp_query;
		$current_pagenow = $pagenow;
	
		$output = '';
		$wp_query = new WP_Query();
		if ( YARPP_NO_RELATED == $cache_status ) {
			// If there are no related posts, get no query
		} else {
			$orders = explode(' ',$order);
			$wp_query->query(array(
				'p' => $reference_ID,
				'orderby' => $orders[0],
				'order' => $orders[1],
				'showposts' => $limit,
				'post_type' => ( isset($args['post_type']) ? $args['post_type'] : $yarpp->get_post_types( 'name' ) )
			));
		}
		//$yarpp->prep_query( $current_query->is_feed );
		$related_query = $wp_query; 
	
	$rps_posts = array();
	if ($related_query->have_posts()) {
	while ($related_query->have_posts()) {
		$related_query->the_post();
		$rps_posts[]=get_the_ID();
	}}
	
	if ( YARPP_NO_RELATED == $cache_status ) {
			// Uh, do nothing. Stay very still.
		} else {
			$yarpp->cache->end_yarpp_time(); // YARPP time is over... :(
		}
	
		// restore the older wp_query.
		$wp_query = $current_query; unset($current_query); unset($related_query);
		wp_reset_postdata();
		$pagenow = $current_pagenow; unset($current_pagenow);
  
  return $rps_posts;
endif;
}

class CF5_RPS_Widget extends WP_Widget {
	function CF5_RPS_Widget() {
		$widget_options = array('classname' => 'cf5_rps_wclass', 'description' => 'Insert Related Posts Slider' );
		$this->WP_Widget('cf5_rps_wid', 'Related Posts Slider', $widget_options);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
	    extract( $args );
		
		echo $before_widget;

		if ( $title ) 
		   echo $before_title . $title . $after_title;
		get_related_posts_slider();
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
	    $instance = $old_instance;
        return $instance;
	}

	function form($instance) {
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("CF5_RPS_Widget");') );

function cf5_rps_word_limiter( $text, $limit = 40 , $display_dots = true) {
    $text = str_replace(']]>', ']]&gt;', $text);
	//Not using strip_tags as to accomodate the 'retain html tags' feature
	//$text = strip_tags($text);
	
    $explode = explode(' ',$text);
    $string  = '';

    $dots = '...';
    if(count($explode) <= $limit){
        $dots = '';
    }
    for($i=0;$i<$limit;$i++){
        $string .= $explode[$i]." ";
    }
    if ($dots) {
        $string = substr($string, 0, strlen($string));
    }
	if($display_dots)
      return $string.$dots;
	else
	  return $string;
}
require_once (dirname (__FILE__) . '/includes/settings.php');
?>