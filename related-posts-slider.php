<?php
/*
Plugin Name: Related Posts Slider
Plugin URI: http://www.clickonf5.org/related-posts-slider
Description: Related posts slider creates a very attractive slider of the related posts or/and pages for a WordPress post or page. The slider is a lightweight jQuery implementation of the related post functionality. Watch Live Demo at <a href="http://www.clickonf5.org/">Internet Techies</a>.
Version: 1.3	
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
define("CF5_RPS_VER","1.3",false);
define('CF5_RPS_URLPATH', trailingslashit( WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) ) );

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
	global $wpdb, $post, $userdata, $yarpp_time, $yarpp_demo_time, $wp_query, $id, $page, $pages, $authordata, $day, $currentmonth, $multipage, $more, $pagenow, $numpages, $yarpp_cache;
	
	get_currentuserinfo();

	// set the "domain prefix", used for all the preferences.
	$domainprefix = '';
	
	if ($yarpp_cache->yarpp_time) // if we're already in a YARPP loop, stop now.
			return false;
	if (is_object($post) and !$reference_ID)
			$reference_ID = $post->ID;

	// get options
	// note the 2.1 change... the options array changed from what you might call a "list" to a "hash"... this changes the structure of the $args to something which is, in the long term, much more useful
	$options = array(
        'limit'=>"${domainprefix}limit",
		'order'=>"${domainprefix}order");
	$optvals = array();
	foreach (array_keys($options) as $option) {
		if (isset($args[$option])) {
			$optvals[$option] = stripslashes($args[$option]);
		} else {
			$optvals[$option] = stripslashes(stripslashes(yarpp_get_option($options[$option])));
		}
	}
	extract($optvals);
	
    yarpp_cache_enforce($type,$reference_ID);
	
	//$yarpp_time = true; 
	$yarpp_cache->begin_yarpp_time($reference_ID);
	
	// just so we can return to normal later
	$current_query = $wp_query;
	$current_post = $post;
	$current_id = $id;
	$current_page = $page;
	$current_pages = $pages;
	$current_authordata = $authordata;
	$current_numpages = $numpages;
	$current_multipage = $multipage;
	$current_more = $more;
	$current_pagenow = $pagenow;
	$current_day = $day;
	$current_currentmonth = $currentmonth;

	$related_query = new WP_Query();
	$orders = explode(' ',$order);
		$related_query->query(array('p'=>$reference_ID,'orderby'=>$orders[0],'order'=>$orders[1],'showposts'=>$limit,'post_type'=>$type));

	$wp_query = $related_query;
	$wp_query->in_the_loop = true;
    $wp_query->is_feed = $current_query->is_feed;
  // make sure we get the right is_single value
  // (see http://wordpress.org/support/topic/288230)
	$wp_query->is_single = false;
	
	$rps_posts = array();
	if ($related_query->have_posts()) {
	while ($related_query->have_posts()) {
		$related_query->the_post();
		$rps_posts[]=get_the_ID();
	}}
	
	unset($related_query);
	//$yarpp_time = false; // YARPP time is over... :(
	$yarpp_cache->end_yarpp_time(); // YARPP time is over... :(
	
	// restore the older wp_query.
	$wp_query = null; $wp_query = $current_query; unset($current_query);
	$post = null; $post = $current_post; unset($current_post);
  $authordata = null; $authordata = $current_authordata; unset($current_authordata);
	$pages = null; $pages = $current_pages; unset($current_pages);
	$id = $current_id; unset($current_id);
	$page = $current_page; unset($current_page);
	$numpages = null; $numpages = $current_numpages; unset($current_numpages);
	$multipage = null; $multipage = $current_multipage; unset($current_multipage);
	$more = null; $more = $current_more; unset($current_more);
	$pagenow = null; $pagenow = $current_pagenow; unset($current_pagenow);
  $day = null; $day = $current_day; unset($current_day);
  $currentmonth = null; $currentmonth = $current_currentmonth; unset($current_currentmonth);
  
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

// function for adding settings page to wp-admin
function cf5_rps_settings() {
    // Add a new submenu under Options:
    add_options_page('Related Posts Slider', 'Related Posts Slider', 9, basename(__FILE__), 'cf5_rps_settings_page');
}

function cf5_rps_admin_head() {
  if ( isset($_GET['page']) && 'related-posts-slider.php' == $_GET['page']  ) {
		wp_print_scripts( 'farbtastic' );
		wp_print_styles( 'farbtastic' );
?>

<script type="text/javascript">
	// <![CDATA[
jQuery(document).ready(function() { 
<?php   for($i=1;$i<=10;$i++) {?>
		jQuery('#colorbox_<?php echo $i;?>').farbtastic('#color_value_<?php echo $i;?>');
		jQuery('#color_picker_<?php echo $i;?>').click(function () {
           if (jQuery('#colorbox_<?php echo $i;?>').css('display') == "block") {
		      jQuery('#colorbox_<?php echo $i;?>').fadeOut("slow"); }
		   else {
		      jQuery('#colorbox_<?php echo $i;?>').fadeIn("slow"); }
        });
		var colorpick_<?php echo $i;?> = false;
		jQuery(document).mousedown(function(){
		    if (colorpick_<?php echo $i;?> == true) {
    			return; }
				jQuery('#colorbox_<?php echo $i;?>').fadeOut("slow");
		});
		jQuery(document).mouseup(function(){
		    colorpick_<?php echo $i;?> = false;
		});
	<?php   } ?>
});
</script>

<style type="text/css">
.color-picker-wrap {
		position: absolute;
 		display: none; 
		background: #fff;
		border: 3px solid #ccc;
		padding: 3px;
		z-index: 1000;
	}
</style>
<?php 
  }
}
if($cf5_rps['stylesheet']=='default'){
	add_action('admin_head', 'cf5_rps_admin_head');
}
// This function displays the page content for the Iframe Embed For YouTube Options submenu
function cf5_rps_settings_page() {
?>
<div class="wrap">
<h2>Related Posts Slider</h2>
<form  method="post" action="options.php">
<div id="poststuff" class="metabox-holder has-right-sidebar"> 

<div style="float:left;width:55%;">
<?php
settings_fields('cf5_rps-group');
$cf5_rps = get_option('cf5_rps_options');
?>
<h2><?php _e('Overall Slider Settings','cf5_rps'); ?></h2> 
<table class="form-table">

<tr valign="top">
    <th scope="row"><?php _e('Related Posts Plugin to use','cf5_rps'); ?><small><?php _e('(You need to install and activate the selected plugin in order to make RPS run.)','cf5_rps'); ?></small></th>
    <td><select name="cf5_rps_options[plugin]" id="cf5_rps_plugin" >
    <option value="yarpp" <?php if ($cf5_rps['plugin'] == "yarpp"){ echo "selected";}?> >YARPP</option>
    <option value="wp_rp" <?php if ($cf5_rps['plugin'] == "wp_rp"){ echo "selected";}?> >WordPress Related Posts</option>
    </td>
</tr>

<tr valign="top">
    <th scope="row"><?php _e('Related Slider Format','cf5_rps'); ?><small><?php _e('(If you select other than "default", click save to see correct options.)','cf5_rps'); ?></small></th>
    <td><select name="cf5_rps_options[format]" id="cf5_rps_format" >
    <option value="default" <?php if ($cf5_rps['format'] == "default"){ echo "selected";}?> >Default</option>
    <option value="h_carousel" <?php if ($cf5_rps['format'] == "h_carousel"){ echo "selected";}?> >Horizontal Carousel</option>
    </select> &nbsp; &nbsp; <input type="submit" class="button-primary" value="<?php _e('Save') ?>" />
    </td>
</tr>

<tr valign="top" <?php if($cf5_rps['format']!='default') {echo 'style="display:none;"';} ?>>
<th scope="row"><label for="cf5_rps_options[stylesheet]"><?php _e('Select the style for your Slider','cf5_rps'); ?></label></th> 
<td><select name="cf5_rps_options[stylesheet]" id="cf5_rps_stylesheet" >
<?php
$directory = WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'/styles/';
if ($handle = opendir($directory)) {
    while (false !== ($file = readdir($handle))) { 
     if($file != '.' and $file != '..') { ?>
      <option value="<?php echo $file;?>" <?php if ($cf5_rps['stylesheet'] == $file){ echo "selected";}?> ><?php echo $file;?></option>
 <?php  } }
    closedir($handle);
}
?>
</select><small><?php _e('The CSS settings below are only applicable and visible in case you select "default" stylesheet.','cf5_rps'); ?></small></td></tr>

<tr valign="top" <?php if($cf5_rps['format']!='h_carousel') {echo 'style="display:none;"';} ?>>
<th scope="row"><label for="cf5_rps_options[format_style]"><?php _e('Select the style for your Slider','cf5_rps'); ?></label></th> 
<td>
<?php $format_directory = WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'/formats/h_carousel/styles/'; ?>
<select name="cf5_rps_options[format_style]" id="cf5_rps_format_style" >
<?php
if ($handle = opendir($format_directory)) {
    while (false !== ($file = readdir($handle))) { 
     if($file != '.' and $file != '..') { ?>
      <option value="<?php echo $file;?>" <?php if ($cf5_rps['format_style'] == $file){ echo "selected";}?> ><?php echo $file;?></option>
 <?php  } }
    closedir($handle);
}
?>
</select><small><?php _e('The CSS settings below are only applicable and visible in case you select "default" style.','cf5_rps'); ?></small></td></tr>

<tr valign="top">
<th scope="row"><?php _e('No. of Posts in one group of List Section/Visible Posts','cf5_rps'); ?></th>
<td><input type="text" name="cf5_rps_options[per_page]" id="cf5_rps_no_posts" class="small-text" value="<?php echo $cf5_rps['per_page']; ?>" /></td>
</tr>

<tr valign="top" <?php if($cf5_rps['format_style']!='default' and $cf5_rps['format']!='default' ) echo 'style="display:none;"';?>>
<th scope="row"><?php _e('Slider Height','cf5_rps'); ?></th>
<td><input type="text" name="cf5_rps_options[height]" id="cf5_rps_height" class="small-text" value="<?php echo $cf5_rps['height']; ?>" />&nbsp;px</td>
</tr>

    <tr valign="top" <?php if(($cf5_rps['stylesheet']!='default' and $cf5_rps['format']=='default') or ($cf5_rps['format_style']!='default' and $cf5_rps['format']!='default') ) echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Slider Background Color','cf5_rps'); ?></th>
    <td><input type="text" name="cf5_rps_options[bgcolor]" id="color_value_1" value="<?php echo $cf5_rps['bgcolor']; ?>" />&nbsp; <img id="color_picker_1" src="<?php echo cf5_rps_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','cf5_rps'); ?>" /><div class="color-picker-wrap" id="colorbox_1"></div> <small><?php _e('(If left empty, will pick inherited color)','cf5_rps'); ?></small></td>
    </tr>
    
    <tr valign="top" <?php if(($cf5_rps['stylesheet']!='default' and $cf5_rps['format']=='default') or ($cf5_rps['format_style']!='default' and $cf5_rps['format']!='default') ) echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Slider Foregound Color','cf5_rps'); ?></th>
    <td><input type="text" name="cf5_rps_options[fgcolor]" id="color_value_2" value="<?php echo $cf5_rps['fgcolor']; ?>" />&nbsp; <img id="color_picker_2" src="<?php echo cf5_rps_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','cf5_rps'); ?>" /><div class="color-picker-wrap" id="colorbox_2"></div> </td>
    </tr>
    
    <tr valign="top" <?php if(($cf5_rps['stylesheet']!='default' and $cf5_rps['format']=='default') or ($cf5_rps['format_style']!='default' and $cf5_rps['format']!='default') ) echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Background Color for Hover Section','cf5_rps'); ?></th>
    <td><input type="text" name="cf5_rps_options[hvcolor]" id="color_value_3" value="<?php echo $cf5_rps['hvcolor']; ?>" />&nbsp; <img id="color_picker_3" src="<?php echo cf5_rps_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','cf5_rps'); ?>" /><div class="color-picker-wrap" id="colorbox_3"></div> </td>
    </tr>
    
    <tr valign="top" <?php if(($cf5_rps['stylesheet']!='default' and $cf5_rps['format']=='default') or ($cf5_rps['format_style']!='default' and $cf5_rps['format']!='default') ) echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Text Color For Hover Section','cf5_rps'); ?></th>
    <td><input type="text" name="cf5_rps_options[hvtext_color]" id="color_value_9" value="<?php echo $cf5_rps['hvtext_color']; ?>" />&nbsp; <img id="color_picker_9" src="<?php echo cf5_rps_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','cf5_rps'); ?>" /><div class="color-picker-wrap" id="colorbox_9"></div> </td>
    </tr>
    
    <tr valign="top" <?php if(($cf5_rps['stylesheet']!='default' and $cf5_rps['format']=='default') or ($cf5_rps['format_style']!='default' and $cf5_rps['format']!='default') ) echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Outer Border Thickness','cf5_rps'); ?></th>
    <td><input type="text" name="cf5_rps_options[obrwidth]" id="cf5_rps_obrwidth" class="small-text" value="<?php echo $cf5_rps['obrwidth']; ?>" />&nbsp;px &nbsp;(put 0 if no border is required)</td>
    </tr>
    
    <tr valign="top" <?php if(($cf5_rps['stylesheet']!='default' and $cf5_rps['format']=='default') or ($cf5_rps['format_style']!='default' and $cf5_rps['format']!='default') ) echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Outer Border Color','cf5_rps'); ?></th>
    <td><input type="text" name="cf5_rps_options[obrcolor]" id="color_value_4" value="<?php echo $cf5_rps['obrcolor']; ?>" />&nbsp; <img id="color_picker_4" src="<?php echo cf5_rps_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','cf5_rps'); ?>" /><div class="color-picker-wrap" id="colorbox_4"></div></td>
    </tr>
    
    <tr valign="top" <?php if(($cf5_rps['stylesheet']!='default' and $cf5_rps['format']=='default') or ($cf5_rps['format_style']!='default' and $cf5_rps['format']!='default') ) echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Inner Border Thickness','cf5_rps'); ?></th>
    <td><input type="text" name="cf5_rps_options[ibrwidth]" id="cf5_rps_obrwidth" class="small-text" value="<?php echo $cf5_rps['ibrwidth']; ?>" />&nbsp;px &nbsp;(put 0 if no border is required)</td>
    </tr>
    
    <tr valign="top" <?php if(($cf5_rps['stylesheet']!='default' and $cf5_rps['format']=='default') or ($cf5_rps['format_style']!='default' and $cf5_rps['format']!='default') ) echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Inner Border Color','cf5_rps'); ?></th>
    <td><input type="text" name="cf5_rps_options[ibrcolor]" id="color_value_5" value="<?php echo $cf5_rps['ibrcolor']; ?>" />&nbsp; <img id="color_picker_5" src="<?php echo cf5_rps_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','cf5_rps'); ?>" /><div class="color-picker-wrap" id="colorbox_5"></div></td>
    </tr>

</table> 

<h2><?php _e('Slider Title','cf5_rps'); ?></h2> 
<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('Slider Title Text','cf5_rps'); ?></th>
<td><input type="text" name="cf5_rps_options[sldr_title]" class="regular-text code" value="<?php echo $cf5_rps['sldr_title']; ?>" /></td>
</tr>

    <tr valign="top" <?php if(($cf5_rps['stylesheet']!='default' and $cf5_rps['format']=='default') or ($cf5_rps['format_style']!='default' and $cf5_rps['format']!='default') ) echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Title Font','cf5_rps'); ?></th>
    <td><select name="cf5_rps_options[stitle_font]" id="cf5_rps_stitle_font" >
    <option value="Arial,Helvetica,sans-serif" <?php if ($cf5_rps['stitle_font'] == "Arial,Helvetica,sans-serif"){ echo "selected";}?> >Arial,Helvetica,sans-serif</option>
    <option value="Calibri,Times,serif" <?php if ($cf5_rps['stitle_font'] == "Calibri,Times,serif"){ echo "selected";}?> >Calibri,Times,serif</option>
    <option value="Century Schoolbook,Times,serif" <?php if ($cf5_rps['stitle_font'] == "Century Schoolbook,Times,serif"){ echo "selected";}?> >Century Schoolbook,Times,serif</option>
    <option value="Courier New,Courier,monospace" <?php if ($cf5_rps['stitle_font'] == "Courier New,Courier,monospace"){ echo "selected";}?> >Courier New,Courier,monospace</option>
    <option value="Geneva,Verdana,sans-serif" <?php if ($cf5_rps['stitle_font'] == "Geneva,Verdana,sans-serif"){ echo "selected";}?> >Geneva,Verdana,sans-serif</option>
    <option value="Georgia,Times New Roman,Times,serif" <?php if ($cf5_rps['stitle_font'] == "Georgia,Times New Roman,Times,serif"){ echo "selected";} ?> >Georgia,Times New Roman,Times,serif</option>
    <option value="Helvetica,Arial,sans-serif" <?php if ($cf5_rps['stitle_font'] == "Helvetica,Arial,sans-serif"){ echo "selected";}?> >Helvetica,Arial,sans-serif</option>
    <option value="Times New Roman,Times,serif" <?php if ($cf5_rps['stitle_font'] == "Times New Roman,Times,serif"){ echo "selected";}?> >Times New Roman,Times,serif</option>
    <option value="Trebuchet MS,Times,serif" <?php if ($cf5_rps['ptitle_font'] == "Trebuchet MS,Times,serif"){ echo "selected";}?> >Trebuchet MS,Times,serif</option>
    <option value="Verdana,Geneva,sans-serif" <?php if ($cf5_rps['stitle_font'] == "Verdana,Geneva,sans-serif"){ echo "selected";}?> >Verdana,Geneva,sans-serif</option>
    </select>
    </td>
    </tr>
    
    <tr valign="top" <?php if(($cf5_rps['stylesheet']!='default' and $cf5_rps['format']=='default') or ($cf5_rps['format_style']!='default' and $cf5_rps['format']!='default') ) echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Slider Title Font Color','cf5_rps'); ?></th>
    <td><input type="text" name="cf5_rps_options[stitle_color]" id="color_value_10" value="<?php echo $cf5_rps['stitle_color']; ?>" />&nbsp; <img id="color_picker_10" src="<?php echo cf5_rps_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','cf5_rps'); ?>" /><div class="color-picker-wrap" id="colorbox_10"></div></td>
    </tr>
    
    <tr valign="top" <?php if(($cf5_rps['stylesheet']!='default' and $cf5_rps['format']=='default') or ($cf5_rps['format_style']!='default' and $cf5_rps['format']!='default') ) echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Slider Title Font Size','cf5_rps'); ?></th>
    <td><input type="text" name="cf5_rps_options[stitle_size]" id="cf5_rps_stitle_size" class="small-text" value="<?php echo $cf5_rps['stitle_size']; ?>" />&nbsp;px</td>
    </tr>
    
    <tr valign="top" <?php if(($cf5_rps['stylesheet']!='default' and $cf5_rps['format']=='default') or ($cf5_rps['format_style']!='default' and $cf5_rps['format']!='default') ) echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Slider Title Font Weight','cf5_rps'); ?></th>
    <td><select name="cf5_rps_options[stitle_weight]" id="cf5_rps_stitle_weight" >
    <option value="bold" <?php if ($cf5_rps['stitle_weight'] == "bold"){ echo "selected";}?> >Bold</option>
    <option value="normal" <?php if ($cf5_rps['stitle_weight'] == "normal"){ echo "selected";}?> >Normal</option>
    </select>
    </td>
    </tr>
    
    <tr valign="top" <?php if(($cf5_rps['stylesheet']!='default' and $cf5_rps['format']=='default') or ($cf5_rps['format_style']!='default' and $cf5_rps['format']!='default') ) echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Slider Title Font Style','cf5_rps'); ?></th>
    <td><select name="cf5_rps_options[stitle_style]" id="cf5_rps_stitle_style" >
    <option value="italic" <?php if ($cf5_rps['stitle_style'] == "italic"){ echo "selected";}?> >Italic</option>
    <option value="normal" <?php if ($cf5_rps['stitle_style'] == "normal"){ echo "selected";}?> >Normal</option>
    </select>
    </td>
    </tr>
</table>

<h2><?php _e('Thumbnail Image','cf5_rps'); ?></h2> 
<p><?php _e('Settings for the thumbnail image in Preview Section','cf5_rps'); ?></p> 
<table class="form-table">

<tr valign="top"> 
<th scope="row"><?php _e('Image Pick Preferences','cf5_rps'); ?> <small><?php _e('(The first one is having priority over second, the second on third and so on. Atleast select one option!)','cf5_rps'); ?></small></th> 
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Image Pick Sequence','cf5_rps'); ?> <small><?php _e('(The first one is having priority over second, the second having priority on third and so on)','cf5_rps'); ?></small> </span></legend> 
<input name="cf5_rps_options[img_pick][0]" type="checkbox" value="1" <?php checked('1', $cf5_rps['img_pick'][0]); ?>  /> <?php _e('Use Custom Field/Key','cf5_rps'); ?> &nbsp; &nbsp; 
<input type="text" name="cf5_rps_options[img_pick][1]" class="text" value="<?php echo $cf5_rps['img_pick'][1]; ?>" /> <?php _e('Name of the Custom Field/Key','cf5_rps'); ?> 
<br />
<input name="cf5_rps_options[img_pick][2]" type="checkbox" value="1" <?php checked('1', $cf5_rps['img_pick'][2]); ?>  /> <?php _e('Use Featured Post/Thumbnail (Wordpress 3.0 +  feature)','cf5_rps'); ?> &nbsp; <br />
<input name="cf5_rps_options[img_pick][3]" type="checkbox" value="1" <?php checked('1', $cf5_rps['img_pick'][3]); ?>  /> <?php _e('Consider Images attached to the post','cf5_rps'); ?>  &nbsp; &nbsp; 
<input type="text" name="cf5_rps_options[img_pick][4]" class="small-text" value="<?php echo $cf5_rps['img_pick'][4]; ?>" /> <?php _e('Order of the Image attachment to pick','cf5_rps'); ?>  &nbsp; <br />
<input name="cf5_rps_options[img_pick][5]" type="checkbox" value="1" <?php checked('1', $cf5_rps['img_pick'][5]); ?>  /> <?php _e('Scan images from the post, in case there is no attached image to the post','cf5_rps'); ?> &nbsp; 
</fieldset></td> 
</tr> 

<tr valign="top">
<th scope="row"><?php _e('Wordpress Image Extract Size','cf5_rps'); ?> </th>
<td><select name="cf5_rps_options[crop]" id="cf5_rps_img_crop" >
<option value="0" <?php if ($cf5_rps['crop'] == "0"){ echo "selected";}?> ><?php _e('Full','cf5_rps'); ?></option>
<option value="1" <?php if ($cf5_rps['crop'] == "1"){ echo "selected";}?> ><?php _e('Large','cf5_rps'); ?></option>
<option value="2" <?php if ($cf5_rps['crop'] == "2"){ echo "selected";}?> ><?php _e('Medium','cf5_rps'); ?></option>
<option value="3" <?php if ($cf5_rps['crop'] == "3"){ echo "selected";}?> ><?php _e('Thumbnail','cf5_rps'); ?></option>
</select>
<small><?php _e('This is because, for every image upload to the media gallery WordPress creates four sizes of the same image. So you can choose which to load in the slider and then specify the actual size.','cf5_rps'); ?></small>
</td>
</tr>

    <tr valign="top" <?php if(($cf5_rps['stylesheet']!='default' and $cf5_rps['format']=='default') or ($cf5_rps['format_style']!='default' and $cf5_rps['format']!='default') ) echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Align to','cf5_rps'); ?></th>
    <td><select name="cf5_rps_options[img_align]" id="cf5_rps_img_align" >
    <option value="left" <?php if ($cf5_rps['img_align'] == "left"){ echo "selected";}?> ><?php _e('Left','cf5_rps'); ?></option>
    <option value="right" <?php if ($cf5_rps['img_align'] == "right"){ echo "selected";}?> ><?php _e('Right','cf5_rps'); ?></option>
    <option value="none" <?php if ($cf5_rps['img_align'] == "none"){ echo "selected";}?> ><?php _e('Center','cf5_rps'); ?></option>
    </select>
    </td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default' and $cf5_rps['format']=='default' ) echo 'style="display:none;"';?>> 
    <th scope="row"><label for="cf5_rps_options[img_width]"><?php _e('Image Width','cf5_rps'); ?></label></th> 
    <td><input type="text" name="cf5_rps_options[img_width]" class="small-text" value="<?php echo $cf5_rps['img_width']; ?>" />&nbsp;<?php _e('(% for "default" format and "px" for other formats of slider)','cf5_rps'); ?>&nbsp;&nbsp; </td> 
    </tr> 
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default' and $cf5_rps['format']=='default' ) echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Maximum Height/Height of the Image','cf5_rps'); ?></th>
    <td><input type="text" name="cf5_rps_options[img_height]" class="small-text" value="<?php echo $cf5_rps['img_height']; ?>" />&nbsp;px &nbsp;&nbsp; <?php _e('(This is necessary in order to keep the maximum image height in control)','cf5_rps'); ?></td>
    </tr>

</table>

<h2><?php _e('List Section','cf5_rps'); ?></h2> 
<table class="form-table">

    <tr valign="top" <?php if(($cf5_rps['stylesheet']!='default' and $cf5_rps['format']=='default') or ($cf5_rps['format_style']!='default' and $cf5_rps['format']!='default') ) echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Font','cf5_rps'); ?></th>
    <td><select name="cf5_rps_options[ltitle_font]" id="cf5_rps_ltitle_font" >
    <option value="Arial,Helvetica,sans-serif" <?php if ($cf5_rps['ltitle_font'] == "Arial,Helvetica,sans-serif"){ echo "selected";}?> >Arial,Helvetica,sans-serif</option>
    <option value="Calibri,Times,serif" <?php if ($cf5_rps['ltitle_font'] == "Calibri,Times,serif"){ echo "selected";}?> >Calibri,Times,serif</option>
    <option value="Century Schoolbook,Times,serif" <?php if ($cf5_rps['ltitle_font'] == "Century Schoolbook,Times,serif"){ echo "selected";}?> >Century Schoolbook,Times,serif</option>
    <option value="Courier New,Courier,monospace" <?php if ($cf5_rps['ltitle_font'] == "Courier New,Courier,monospace"){ echo "selected";}?> >Courier New,Courier,monospace</option>
    <option value="Geneva,Verdana,sans-serif" <?php if ($cf5_rps['ltitle_font'] == "Geneva,Verdana,sans-serif"){ echo "selected";}?> >Geneva,Verdana,sans-serif</option>
    <option value="Georgia,Times New Roman,Times,serif" <?php if ($cf5_rps['ltitle_font'] == "Georgia,Times New Roman,Times,serif"){ echo "selected";} ?> >Georgia,Times New Roman,Times,serif</option>
    <option value="Helvetica,Arial,sans-serif" <?php if ($cf5_rps['ltitle_font'] == "Helvetica,Arial,sans-serif"){ echo "selected";}?> >Helvetica,Arial,sans-serif</option>
    <option value="Times New Roman,Times,serif" <?php if ($cf5_rps['ltitle_font'] == "Times New Roman,Times,serif"){ echo "selected";}?> >Times New Roman,Times,serif</option>
    <option value="Trebuchet MS,Times,serif" <?php if ($cf5_rps['ltitle_font'] == "Trebuchet MS,Times,serif"){ echo "selected";}?> >Trebuchet MS,Times,serif</option>
    <option value="Verdana,Geneva,sans-serif" <?php if ($cf5_rps['ltitle_font'] == "Verdana,Geneva,sans-serif"){ echo "selected";}?> >Verdana,Geneva,sans-serif</option>
    </select>
    </td>
    </tr>
    
    <tr valign="top" <?php if(($cf5_rps['stylesheet']!='default' and $cf5_rps['format']=='default') or ($cf5_rps['format_style']!='default' and $cf5_rps['format']!='default') ) echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Font Color','cf5_rps'); ?></th>
    <td><input type="text" name="cf5_rps_options[ltitle_color]" id="color_value_6" value="<?php echo $cf5_rps['ltitle_color']; ?>" />&nbsp; <img id="color_picker_6" src="<?php echo cf5_rps_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','cf5_rps'); ?>" /><div class="color-picker-wrap" id="colorbox_6"></div></td>
    </tr>
    
    <tr valign="top" <?php if(($cf5_rps['stylesheet']!='default' and $cf5_rps['format']=='default') or ($cf5_rps['format_style']!='default' and $cf5_rps['format']!='default') ) echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Font Size','cf5_rps'); ?></th>
    <td><input type="text" name="cf5_rps_options[ltitle_size]" id="cf5_rps_ltitle_size" class="small-text" value="<?php echo $cf5_rps['ltitle_size']; ?>" />&nbsp;px</td>
    </tr>
    
    <tr valign="top" <?php if(($cf5_rps['stylesheet']!='default' and $cf5_rps['format']=='default') or ($cf5_rps['format_style']!='default' and $cf5_rps['format']!='default') ) echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Font Weight','cf5_rps'); ?></th>
    <td><select name="cf5_rps_options[ltitle_weight]" id="cf5_rps_ltitle_weight" >
    <option value="bold" <?php if ($cf5_rps['ltitle_weight'] == "bold"){ echo "selected";}?> ><?php _e('Bold','cf5_rps'); ?></option>
    <option value="normal" <?php if ($cf5_rps['ltitle_weight'] == "normal"){ echo "selected";}?> ><?php _e('Normal','cf5_rps'); ?></option>
    </select>
    </td>
    </tr>
    
    <tr valign="top" <?php if(($cf5_rps['stylesheet']!='default' and $cf5_rps['format']=='default') or ($cf5_rps['format_style']!='default' and $cf5_rps['format']!='default') ) echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Font Style','cf5_rps'); ?></th>
    <td><select name="cf5_rps_options[ltitle_style]" id="cf5_rps_ltitle_style" >
    <option value="italic" <?php if ($cf5_rps['ltitle_style'] == "italic"){ echo "selected";}?> ><?php _e('Italic','cf5_rps'); ?></option>
    <option value="normal" <?php if ($cf5_rps['ltitle_style'] == "normal"){ echo "selected";}?> ><?php _e('Normal','cf5_rps'); ?></option>
    </select>
    </td>
    </tr>

<tr valign="top">
<th scope="row"><?php _e('Max words in List Title','cf5_rps'); ?></th>
<td><input type="text" name="cf5_rps_options[ltitle_words]" id="cf5_rps_ltitle_words" class="small-text" value="<?php echo $cf5_rps['ltitle_words']; ?>" />&nbsp;<?php _e('words','cf5_rps'); ?></td>
</tr>

</table>

<h2 <?php if($cf5_rps['format']!='default' ) echo 'style="display:none;"';?>><?php _e('Preview Section','cf5_rps'); ?></h2> 
<table class="form-table" <?php if($cf5_rps['format']!='default' ) echo 'style="display:none;"';?>>

    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Title Font','cf5_rps'); ?></th>
    <td><select name="cf5_rps_options[ptitle_font]" id="cf5_rps_ptitle_font" >
    <option value="Arial,Helvetica,sans-serif" <?php if ($cf5_rps['ptitle_font'] == "Arial,Helvetica,sans-serif"){ echo "selected";}?> >Arial,Helvetica,sans-serif</option>
    <option value="Calibri,Times,serif" <?php if ($cf5_rps['ptitle_font'] == "Calibri,Times,serif"){ echo "selected";}?> >Calibri,Times,serif</option>
    <option value="Century Schoolbook,Times,serif" <?php if ($cf5_rps['ptitle_font'] == "Century Schoolbook,Times,serif"){ echo "selected";}?> >Century Schoolbook,Times,serif</option>
    <option value="Courier New,Courier,monospace" <?php if ($cf5_rps['ptitle_font'] == "Courier New,Courier,monospace"){ echo "selected";}?> >Courier New,Courier,monospace</option>
    <option value="Geneva,Verdana,sans-serif" <?php if ($cf5_rps['ptitle_font'] == "Geneva,Verdana,sans-serif"){ echo "selected";}?> >Geneva,Verdana,sans-serif</option>
    <option value="Georgia,Times New Roman,Times,serif" <?php if ($cf5_rps['ptitle_font'] == "Georgia,Times New Roman,Times,serif"){ echo "selected";} ?> >Georgia,Times New Roman,Times,serif</option>
    <option value="Helvetica,Arial,sans-serif" <?php if ($cf5_rps['ptitle_font'] == "Helvetica,Arial,sans-serif"){ echo "selected";}?> >Helvetica,Arial,sans-serif</option>
    <option value="Times New Roman,Times,serif" <?php if ($cf5_rps['ptitle_font'] == "Times New Roman,Times,serif"){ echo "selected";}?> >Times New Roman,Times,serif</option>
    <option value="Trebuchet MS,Times,serif" <?php if ($cf5_rps['ptitle_font'] == "Trebuchet MS,Times,serif"){ echo "selected";}?> >Trebuchet MS,Times,serif</option>
    <option value="Verdana,Geneva,sans-serif" <?php if ($cf5_rps['ptitle_font'] == "Verdana,Geneva,sans-serif"){ echo "selected";}?> >Verdana,Geneva,sans-serif</option>
    </select>
    </td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Title Font Color','cf5_rps'); ?></th>
    <td><input type="text" name="cf5_rps_options[ptitle_color]" id="color_value_7" value="<?php echo $cf5_rps['ptitle_color']; ?>" />&nbsp; <img id="color_picker_7" src="<?php echo cf5_rps_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','cf5_rps'); ?>" /><div class="color-picker-wrap" id="colorbox_7"></div></td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Title Font Size','cf5_rps'); ?></th>
    <td><input type="text" name="cf5_rps_options[ptitle_size]" id="cf5_rps_ptitle_size" class="small-text" value="<?php echo $cf5_rps['ptitle_size']; ?>" />&nbsp;px</td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Title Font Weight','cf5_rps'); ?></th>
    <td><select name="cf5_rps_options[ptitle_weight]" id="cf5_rps_ptitle_weight" >
    <option value="bold" <?php if ($cf5_rps['ptitle_weight'] == "bold"){ echo "selected";}?> ><?php _e('Bold','cf5_rps'); ?></option>
    <option value="normal" <?php if ($cf5_rps['ptitle_weight'] == "normal"){ echo "selected";}?> ><?php _e('Normal','cf5_rps'); ?></option>
    </select>
    </td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Title Font Style','cf5_rps'); ?></th>
    <td><select name="cf5_rps_options[ptitle_style]" id="cf5_rps_ptitle_style" >
    <option value="italic" <?php if ($cf5_rps['ptitle_style'] == "italic"){ echo "selected";}?> ><?php _e('Italic','cf5_rps'); ?></option>
    <option value="normal" <?php if ($cf5_rps['ptitle_style'] == "normal"){ echo "selected";}?> ><?php _e('Normal','cf5_rps'); ?></option>
    </select>
    </td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Content Font','cf5_rps'); ?></th>
    <td><select name="cf5_rps_options[pcontent_font]" id="cf5_rps_pcontent_font" >
    <option value="Arial,Helvetica,sans-serif" <?php if ($cf5_rps['pcontent_font'] == "Arial,Helvetica,sans-serif"){ echo "selected";}?> >Arial,Helvetica,sans-serif</option>
    <option value="Calibri,Times,serif" <?php if ($cf5_rps['pcontent_font'] == "Calibri,Times,serif"){ echo "selected";}?> >Calibri,Times,serif</option>
    <option value="Century Schoolbook,Times,serif" <?php if ($cf5_rps['pcontent_font'] == "Century Schoolbook,Times,serif"){ echo "selected";}?> >Century Schoolbook,Times,serif</option>
    <option value="Courier New,Courier,monospace" <?php if ($cf5_rps['pcontent_font'] == "Courier New,Courier,monospace"){ echo "selected";}?> >Courier New,Courier,monospace</option>
    <option value="Geneva,Verdana,sans-serif" <?php if ($cf5_rps['pcontent_font'] == "Geneva,Verdana,sans-serif"){ echo "selected";}?> >Geneva,Verdana,sans-serif</option>
    <option value="Georgia,Times New Roman,Times,serif" <?php if ($cf5_rps['pcontent_font'] == "Georgia,Times New Roman,Times,serif"){ echo "selected";} ?> >Georgia,Times New Roman,Times,serif</option>
    <option value="Helvetica,Arial,sans-serif" <?php if ($cf5_rps['pcontent_font'] == "Helvetica,Arial,sans-serif"){ echo "selected";}?> >Helvetica,Arial,sans-serif</option>
    <option value="Times New Roman,Times,serif" <?php if ($cf5_rps['pcontent_font'] == "Times New Roman,Times,serif"){ echo "selected";}?> >Times New Roman,Times,serif</option>
    <option value="Trebuchet MS,Times,serif" <?php if ($cf5_rps['pcontent_font'] == "Trebuchet MS,Times,serif"){ echo "selected";}?> >Trebuchet MS,Times,serif</option>
    <option value="Verdana,Geneva,sans-serif" <?php if ($cf5_rps['pcontent_font'] == "Verdana,Geneva,sans-serif"){ echo "selected";}?> >Verdana,Geneva,sans-serif</option>
    </select>
    </td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Content Font Color','cf5_rps'); ?></th>
    <td><input type="text" name="cf5_rps_options[pcontent_color]" id="color_value_8" value="<?php echo $cf5_rps['pcontent_color']; ?>" />&nbsp; <img id="color_picker_8" src="<?php echo cf5_rps_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','cf5_rps'); ?>" /><div class="color-picker-wrap" id="colorbox_8"></div></td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row"><?php _e('Content Font Size','cf5_rps'); ?></th>
    <td><input type="text" name="cf5_rps_options[pcontent_size]" id="cf5_rps_pcontent_size" class="small-text" value="<?php echo $cf5_rps['pcontent_size']; ?>" />&nbsp;px</td>
    </tr>
    
<tr valign="top">
<th scope="row"><?php _e('Pick content From','cf5_rps'); ?></th>
<td><select name="cf5_rps_options[pcontent_from]" id="cf5_rps_content_from" >
<option value="preview_content" <?php if ($cf5_rps['pcontent_from'] == "preview_content"){ echo "selected";}?> ><?php _e('preview_content Custom field','cf5_rps'); ?></option>
<option value="excerpt" <?php if ($cf5_rps['pcontent_from'] == "excerpt"){ echo "selected";}?> ><?php _e('Post Excerpt','cf5_rps'); ?></option>
<option value="content" <?php if ($cf5_rps['pcontent_from'] == "content"){ echo "selected";}?> ><?php _e('From Content','cf5_rps'); ?></option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Max words in Preview Content','cf5_rps'); ?></th>
<td><input type="text" name="cf5_rps_options[pcontent_words]" id="cf5_rps_pcontent_words" class="small-text" value="<?php echo $cf5_rps['pcontent_words']; ?>" />&nbsp;<?php _e('words','cf5_rps'); ?></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Show read more (continue reading) section','cf5_rps'); ?></th>
<td><select name="cf5_rps_options[no_more]" id="target" >
<option value="0" <?php if ($cf5_rps['no_more'] == "0"){ echo "selected";}?> ><?php _e('Show','cf5_rps'); ?></option>
<option value="1" <?php if ($cf5_rps['no_more'] == "1"){ echo "selected";}?> ><?php _e('Do not show','cf5_rps'); ?></option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Continue Reading Text','cf5_rps'); ?></th>
<td><input type="text" name="cf5_rps_options[more]" class="regular-text code" value="<?php echo $cf5_rps['more']; ?>" /></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e('Retain these html tags','cf5_rps'); ?></th>
<td><input type="text" name="cf5_rps_options[allowable_tags]" class="regular-text code" value="<?php echo $cf5_rps['allowable_tags']; ?>" />&nbsp;<?php _e('(read','cf5_rps'); ?> <a href="http://www.clickonf5.org/related-posts-slider" title="<?php _e('how to retain html like line breaks and links in the Related Posts Slider','cf5_rps'); ?>" target="_blank"><?php _e('Usage section of the plugin page','cf5_rps'); ?></a> <?php _e('to know more)','cf5_rps'); ?></td>
</tr>

</table>

<table class="form-table">
    <tr valign="top">
    <th scope="row"><?php _e('Target attribute for the continue reading link/post permalink','cf5_rps'); ?></th>
    <td><select name="cf5_rps_options[target]" id="target" >
    <option value="_self" <?php if ($cf5_rps['target'] == "_self"){ echo "selected";}?> >_self</option>
    <option value="_blank" <?php if ($cf5_rps['target'] == "_blank"){ echo "selected";}?> >_blank</option>
    </select>
    </td>
    </tr>
</table>

<h2><?php _e('Manual/Automatic Insertion','cf5_rps'); ?></h2> 
<small><?php _e('By default the related posts slider is inserted automatically below the content area of the post. But you can select manual insertion (either using templte tag or shortcode or widget) or can select to insert it automatically above the content area of the post.','cf5_rps'); ?></small>
<table class="form-table">
    <tr valign="top">
    <th scope="row"><?php _e('Insert the slider','cf5_rps'); ?></th>
    <td><select name="cf5_rps_options[insert]" id="cf5_rps_insert" >
    <option value="content_down" <?php if ($cf5_rps['insert'] == "content_down"){ echo "selected";}?> ><?php _e('Below the Content','cf5_rps'); ?></option>
    <option value="content_up" <?php if ($cf5_rps['insert'] == "content_up"){ echo "selected";}?> ><?php _e('Above the Content','cf5_rps'); ?></option>
    <option value="manual" <?php if ($cf5_rps['insert'] == "manual"){ echo "selected";}?> ><?php _e('Manually','cf5_rps'); ?></option>
    </select>
    </td>
    </tr>
    
    <tr valign="top">
    <th scope="row"><?php _e('Support "Related Posts Slider"','cf5_rps'); ?></th>
    <td><select name="cf5_rps_options[support]" id="support" >
    <option value="1" <?php if ($cf5_rps['support'] == "1"){ echo "selected";}?> ><?php _e('Yes','cf5_rps'); ?></option>
    <option value="0" <?php if ($cf5_rps['support'] == "0"){ echo "selected";}?> ><?php _e('No','cf5_rps'); ?></option>
    </select><small><?php _e('Share the word, in case you select "No", please consider donating and help the development!','cf5_rps'); ?></small>
    </td>
    </tr>
    
</table>

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

<a href="http://www.clickonf5.org/go/donate-wp-plugins/" target="_blank" rel="nofollow"><img src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" /></a>
<div style="clear:both;"></div>

</div>

   <div id="side-info-column" class="inner-sidebar"> 
			<div class="postbox"> 
			  <h3 class="hndle"><span><?php _e('About this Plugin:','cf5_rps'); ?></span></h3> 
			  <div class="inside">
                <ul>
                <li><a href="http://www.clickonf5.org/related-posts-slider" title="Related Posts Slider WP Plugin Homepage" ><?php _e('Plugin Homepage','cf5_rps'); ?></a></li>
                <li><a href="http://www.clickonf5.org/" title="Visit Internet Techies" ><?php _e('Plugin Parent Site','cf5_rps'); ?></a></li>
                <li><a href="http://www.clickonf5.org/about/tejaswini" title="Related Posts Slider WP Plugin Author Page" ><?php _e('About the Author','cf5_rps'); ?></a></li>
                <li><a href="http://www.clickonf5.org/go/donate-wp-plugins/" title="<?php _e('Donate if you liked the plugin and support in enhancing this plugin and creating new plugins','cf5_rps'); ?>" ><?php _e('Donate with Paypal','cf5_rps'); ?></a></li>
                </ul> 
              </div> 
			</div> 
     </div>
     <div id="side-info-column" class="inner-sidebar"> 
			<div class="postbox"> 
			  <h3 class="hndle"><span></span><?php _e('Our Facebook Fan Page','cf5_rps'); ?></h3> 
			  <div class="inside">
                <script type="text/javascript" src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/en_GB"></script><script type="text/javascript">FB.init("2aeebe9fb014836a6810ec4426d26f7e");</script><fb:fan profile_id="127760528543" stream="" connections="8" width="270" height="250"></fb:fan>
              </div> 
			</div> 
     </div>
     
     <div id="side-info-column" class="inner-sidebar"> 
			<div class="postbox"> 
			  <h3 class="hndle"><span><?php _e('Latest on Internet Techies','cf5_rps'); ?></span></h3> 
			  <div class="inside">
                <?php $postsarr = cf5_rps_parse_rss_rand('http://www.clickonf5.org/feed','5'); 
		        if($postsarr) {?>
                <ul>
                <?php foreach($postsarr as $itpost) { ?>
                <li>&raquo; <a href="<?php echo $itpost['link'];?>" title="<?php _e('Read more about','cf5_rps'); ?> <?php echo $itpost['title'];?>" ><?php echo $itpost['title'];?></a></li>
                <?php } ?>
                </ul> 
                <?php } ?>
              </div> 
			</div> 
     </div>
     <div id="side-info-column" class="inner-sidebar"> 
			<div class="postbox"> 
			  <h3 class="hndle"><span><?php _e('Latest on SWS','cf5_rps'); ?></span></h3> 
			  <div class="inside">
                <?php $postsarr = cf5_rps_parse_rss_rand('http://www.staenzwebsolutions.com/feed','4'); 
		        if($postsarr) {?>
                <ul>
                <?php foreach($postsarr as $itpost) { ?>
                <li>&raquo; <a href="<?php echo $itpost['link'];?>" title="<?php _e('Read more about','cf5_rps'); ?> <?php echo $itpost['title'];?>" ><?php echo $itpost['title'];?></a></li>
                <?php } ?>
                </ul> 
                <?php } ?>
              </div> 
			</div> 
     </div>


     <div id="side-info-column" class="inner-sidebar"> 
			<div class="postbox"> 
			  <h3 class="hndle"><span><?php _e('Credits:','cf5_rps'); ?></span></h3> 
			  <div class="inside">
                <ul>
                <li><a href="http://tympanus.net/codrops/2010/10/03/compact-news-previewer/" title="Compact News Previewer with jQuery" >Compact News Previewer</a></li>
                <li><a href="http://acko.net/dev/farbtastic" title="Farbtastic Color Picker by Steven Wittens" >Farbtastic Color Picker</a></li>
                <li><a href="http://jquery.com/" title="jQuery JavaScript Library - John Resig" >jQuery JavaScript Library</a></li>
                <li><a href="http://sorgalla.com/jcarousel/" title="Riding carousels with jQuery" >jCarousel</a></li>
                <li><a href="http://codex.wordpress.org/Main_Page" title="WordPress Codex" >WordPress Codex</a></li>
                </ul> 
              </div> 
			</div> 
     </div>

</div> <!--end of poststuff -->

</form>

</div> <!--end of float wrap -->

<?php	
}
// Hook for adding admin menus
if ( is_admin() ){ // admin actions
  add_action('admin_menu', 'cf5_rps_settings');
  add_action( 'admin_init', 'register_cf5_rps_settings' ); 
} 
function register_cf5_rps_settings() { // whitelist options
  register_setting( 'cf5_rps-group', 'cf5_rps_options' );
}
function cf5_rps_admin_url( $query = array() ) {
	global $plugin_page;
	if ( ! isset( $query['page'] ) )
		$query['page'] = $plugin_page;
	$path = 'admin.php';
	if ( $query = build_query( $query ) )
		$path .= '?' . $query;
	$url = admin_url( $path );
	return esc_url_raw( $url );
}
function cf5_rps_plugin_action_links( $links, $file ) {
	if ( $file != CF5_RPS_PLUGIN_BASENAME )
		return $links;
	$url = cf5_rps_admin_url( array( 'page' => 'related-posts-slider.php' ) );
	$settings_link = '<a href="' . esc_attr( $url ) . '">'
		. esc_html( __( 'Settings') ) . '</a>';

	array_unshift( $links, $settings_link );
	return $links;
}
add_filter( 'plugin_action_links', 'cf5_rps_plugin_action_links', 10, 2 );//adds the link to the settings page on main Plugins admin page
function cf5_rps_parse_rss_rand($url,$count=0){
    $doc = new DOMDocument();
	$doc->load($url);
	$arrFeeds = array();
	foreach ($doc->getElementsByTagName('item') as $node) {
		$itemRSS = array ( 
			'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
			'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
			'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
			'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue
			);
		array_push($arrFeeds, $itemRSS);
	}
	$outarr=array();
	if($count==0 or empty($count) or !isset($count)){
	   $count=count($arrFeeds);
	}
	for($i=0;$i<$count;$i++) {
	 $outarr[$i]=$arrFeeds[$i];
	}
	return $outarr;
}
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

?>