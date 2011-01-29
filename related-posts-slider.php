<?php
/*
Plugin Name: Related Posts Slider
Plugin URI: http://www.clickonf5.org/related-posts-slider
Description: Related posts slider creates a very attractive slider of the related posts or/and pages for a WordPress post or page. The slider is a lightweight jQuery implementation of the related post functionality. Watch Live Demo at <a href="http://www.clickonf5.org/">Internet Techies</a>.
Version: 1.1	
Author: Internet Techies
Author URI: http://www.clickonf5.org/about/tejaswini
WordPress version supported: 2.9 and above
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
define("CF5_RPS_VER","1.1",false);
define('CF5_RPS_URLPATH', trailingslashit( WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) ) );

function cf5_rps_url( $path = '' ) {
	return plugins_url( $path, __FILE__ );
}
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
					   'support' => '1');
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

function cf5_rps_wp_init() {
//	wp_register_script('jquery', false, false, false, false);
    global $cf5_rps;
	$css="styles/".$cf5_rps['stylesheet'].'/style.css';
	wp_enqueue_style( 'cf5_rps_css', cf5_rps_url( $css ),false, CF5_RPS_VER, 'all'); 
	wp_enqueue_script( 'cf5_easing', cf5_rps_url( 'js/jquery.easing.1.3.js' ),array('jquery'), CF5_RPS_VER, true); 
	wp_enqueue_script('cf5_rps', cf5_rps_url( 'js/cf5.rps.js'), array('cf5_easing'), CF5_RPS_VER, true );
}

add_action( 'wp', 'cf5_rps_wp_init' );

function cf5_rps_wp_head() {
    global $cf5_rps; 
	$cf5_options=$cf5_rps;
	extract($cf5_options);
  if($cf5_rps['stylesheet']=='default'):
	?>
<style type="text/css">.rps_sldrtitle{font-family:<?php echo $cf5_rps['stitle_font'];?>;font-size:<?php echo $cf5_rps['stitle_size'];?>px;font-weight:<?php echo $cf5_rps['stitle_weight'];?>;font-style:<?php echo $cf5_rps['stitle_style'];?>;<?php if($stitle_color and !empty($stitle_color)){?>color:<?php echo $cf5_rps['stitle_color'];?>;<?php } ?>}.rps_wrapper{height:<?php echo $cf5_rps['height'];?>px;<?php if($bgcolor and !empty($bgcolor)){?>background:<?php echo $cf5_rps['bgcolor'];?>;<?php } ?><?php if($pcontent_color and !empty($pcontent_color)){?>color:<?php echo $cf5_rps['pcontent_color'];?>;<?php } ?>border:<?php echo $cf5_rps['obrwidth'];?>px solid <?php echo $cf5_rps['obrcolor'];?>;font-family:<?php echo $cf5_rps['pcontent_font'];?>;font-size:<?php echo $cf5_rps['pcontent_size'];?>px;line-height:<?php echo ($cf5_rps['pcontent_size']+4);?>px;}.rps_wrapper div.h1div{font-size:<?php echo $cf5_rps['ptitle_size'];?>px;line-height:<?php echo ($cf5_rps['ptitle_size']+4);?>px;font-family:<?php echo $cf5_rps['ptitle_font'];?>;font-weight:<?php echo $cf5_rps['ptitle_weight'];?>;font-style:<?php echo $cf5_rps['ptitle_style'];?>;border-bottom:<?php echo $cf5_rps['ibrwidth'];?>px solid <?php echo $cf5_rps['ibrcolor'];?>;}.rps_wrapper div.h1div a{<?php if($ptitle_color and !empty($ptitle_color)){?>color:<?php echo $cf5_rps['ptitle_color'];?> !important;<?php } ?>}.rps_wrapper div.h2div{font-family:<?php echo $cf5_rps['ltitle_font'];?>;font-size:<?php echo $cf5_rps['ltitle_size'];?>px;font-weight:<?php echo $cf5_rps['ltitle_weight'];?>;font-style:<?php echo $cf5_rps['ltitle_style'];?>;<?php if($ltitle_color and !empty($ltitle_color)){?>color:<?php echo $cf5_rps['ltitle_color'];?>;<?php } ?>line-height:<?php echo ($cf5_rps['ltitle_size']+4);?>px;}.rps_content{border:<?php echo $cf5_rps['ibrwidth'];?>px solid <?php echo $cf5_rps['ibrcolor'];?>;top:<?php echo ($cf5_rps['height']+10);?>px;<?php if($fgcolor and !empty($fgcolor)){?>background-color:<?php echo $cf5_rps['fgcolor'];?>;<?php } ?>}img.rps_thumb{width:<?php echo $cf5_rps['img_width'];?>%;max-height:<?php echo $cf5_rps['img_height'];?>px;<?php if($cf5_rps['img_align']=='left') {echo 'float:left;margin:0 5px 5px 0 !important;';}if($cf5_rps['img_align']=='right') {echo 'float:right;margin:0 0 5px 5px !important;';}	?>}.rps_content div.pdiv{border-top:<?php echo $cf5_rps['obrwidth'];?>px solid <?php echo $cf5_rps['obrcolor'];?>;}a.rps_more{<?php if($hvtext_color and !empty($hvtext_color)){?>color:<?php echo $cf5_rps['hvtext_color'];?> !important;<?php } ?>border:<?php echo $cf5_rps['ibrwidth'];?>px solid <?php echo $cf5_rps['ibrcolor'];?>;<?php if($hvcolor and !empty($hvcolor)){?>background-color: <?php echo $cf5_rps['hvcolor'];?>;<?php } ?>}.rps_item{border:<?php echo $cf5_rps['ibrwidth'];?>px solid <?php echo $cf5_rps['ibrcolor'];?>;<?php if($fgcolor and !empty($fgcolor)){?>background:<?php echo $cf5_rps['fgcolor'];?>;<?php } ?>}.rps_item:hover div.h2div,.rps_list .selected div.h2div,.rps_item:active div.h2div{<?php if($hvtext_color and !empty($hvtext_color)){?>color:<?php echo $cf5_rps['hvtext_color'];?>;<?php } ?>}.rps_item:hover, .selected{<?php if($hvcolor and !empty($hvcolor)){?>border-color:<?php echo $cf5_rps['hvcolor'];?>;background-color: <?php echo $cf5_rps['hvcolor'];?>;<?php } ?>}</style>
<?php endif;
}

add_action( 'wp_head', 'cf5_rps_wp_head' );

function cf5_rps_wp_footer() {
    global $cf5_rps; ?>
	<script type="text/javascript">
	  var rps_ht = <?php echo $cf5_rps['height']; ?>
	</script>
<?php }

add_action( 'wp_footer', 'cf5_rps_wp_footer' );

function get_related_posts_slider($echo=true,$type=array('post')){
    global $post,$cf5_rps,$rps_slider_shown;
	global $wpdb, $table_prefix;
	$slider='';
	if(function_exists(yarpp_related)){
	  $rps_posts=get_cf5_yarpp_related_posts($type,array(),false);
	}
if($rps_posts and !$rps_slider_shown):
	$slider='<div class="rps_wrapper">
			 <div id="rps_preview" class="rps_preview">';
		
	if($cf5_rps['img_pick'][0] == '1'){
	 $custom_key = array($cf5_rps['img_pick'][1]);
	}
	else {
	 $custom_key = '';
	}
	
	if($cf5_rps['img_pick'][2] == '1'){
	 $the_post_thumbnail = true;
	}
	else {
	 $the_post_thumbnail = false;
	}
	
	if($cf5_rps['img_pick'][3] == '1'){
	 $attachment = true;
	 $order_of_image = $cf5_rps['img_pick'][4];
	}
	else{
	 $attachment = false;
	 $order_of_image = '1';
	}
	
	if($cf5_rps['img_pick'][5] == '1'){
		 $image_scan = true;
	}
	else {
		 $image_scan = false;
	}
	
	$gti_width = false;
	
	if($cf5_rps['crop'] == '0'){
	 $extract_size = 'full';
	}
	elseif($cf5_rps['crop'] == '1'){
	 $extract_size = 'large';
	}
	elseif($cf5_rps['crop'] == '2'){
	 $extract_size = 'medium';
	}
	else{
	 $extract_size = 'thumbnail';
	}
	
	$i=0;
	foreach($rps_posts as $post_id) {
		if($i==0){$topstyle='style="top:3px;"';}
		else {$topstyle='';}
		
		$posts_table = $table_prefix.'posts'; 
		$result = $wpdb->get_results("SELECT * FROM ".$posts_table." WHERE ID = ".$post_id, OBJECT);
		$rps_post = $result[0];
		
		$permalink=get_permalink($post_id);
		
		$img_args = array(
			'custom_key' => $custom_key,
			'post_id' => $post_id,
			'attachment' => $attachment,
			'size' => $extract_size,
			'the_post_thumbnail' => $the_post_thumbnail,
			'default_image' => false,
			'order_of_image' => $order_of_image,
			'link_to_post' => false,
			'image_class' => 'rps_thumb',
			'image_scan' => $image_scan,
			'width' => $gti_width,
			'height' => false,
			'echo' => false,
			'permalink' => ''
		);
		
		$pcontent = $rps_post->post_content;
		
		if ($cf5_rps['pcontent_from'] == "preview_content") {
		    $pcontent = get_post_meta($post_id, 'preview_content', true);
		}
		if ($cf5_rps['pcontent_from'] == "excerpt") {
		    $pcontent = $rps_post->post_excerpt;
		}
		
		$pcontent = stripslashes($pcontent);
		$pcontent = str_replace(']]>', ']]&gt;', $pcontent);

		$pcontent = str_replace("\n","<br />",$pcontent);
        $pcontent = strip_tags($pcontent, $cf5_rps['allowable_tags']);
		
		$content_limit=$cf5_rps['pcontent_words'];
		if(empty($content_limit) or $content_limit==''){$flag=0;}else{$flag=1;}
		if($flag==1){$pcontent = cf5_rps_word_limiter( $pcontent, $limit = $content_limit );}
		if($cf5_rps['no_more']==0) {
		   $more='<a href="'.$permalink.'" target="'.$cf5_rps['target'].'" class="rps_more">'.$cf5_rps['more'].'</a>';
		}
		else{
		   $more='';
		}
			 
	$slider=$slider.'		<div class="rps_content" '.$topstyle.'>
					'.cf5_rps_get_the_image($img_args).'
					<div class="h1div"><a href="'.$permalink.'" target="'.$cf5_rps['target'].'" >'.get_the_title($post_id).'</a></div>
					
					<div class="pdiv">'.$pcontent.'</div>
					
					<div class="cf5_rps_cl"></div>
					<div class="cf5_rps_cr"></div>'.
					$more
				.'</div>';
	$i++;} //end foreach

    $slider=$slider.'		</div>
			<div id="rps_list" class="rps_list">';
	$i=0;
	foreach($rps_posts as $post_id) {
	    $page_html='';$page_close='';$selected='';
		
		$ltitle=get_the_title($post_id);
		$content_limit=$cf5_rps['ltitle_words'];
		if(empty($content_limit) or $content_limit==''){$flag=0;}else{$flag=1;}
		if($flag==1){$ltitle = cf5_rps_word_limiter( $ltitle, $limit = $content_limit, $display_dots = false );}
		
		if($i%$cf5_rps['per_page'] == 0){
			if($i==0){$page_html='<div class="rps_page" style="display:block;">';$selected='selected';}
			else{$page_html='<div class="rps_page">';}
		}
		if($i%$cf5_rps['per_page'] == ($cf5_rps['per_page']-1)){$page_close='</div>';}
			
	    $slider=$slider.$page_html.'<div class="rps_item '.$selected.'">
						<div class="h2div">'.$ltitle.'</div>
					</div>'.$page_close;
     $i++;}
    
	if($page_close=='' or empty($page_close)){$slider=$slider.'</div>';}
	
	$slider=$slider.'<div class="rps_nav">
						<a id="rps_prev" class="rps_prev disabled"></a>
						<a id="rps_next" class="rps_next"></a>
				    </div>
			</div>
		</div>';
		if($cf5_rps['support']=='0'){$support='';}
		else{$support='<span class="rps_support"><a href="http://www.clickonf5.org/related-posts-slider" target="_blank" title="Related Posts Slider - Free WordPress Plugin">Related Posts Slider</a></span>';}
		$sldr_title='<div class="rps_sldrtitle">'.$support.$cf5_rps['sldr_title'].'</div><div class="cf5_rps_cr"></div>';
		$rpsslider='<div class="cf5_rps">'.$sldr_title.$slider.'<div class="cf5_rps_cl"></div><div class="cf5_rps_cr"></div></div>';
      if($echo){
		echo $rpsslider;
		$rps_slider_shown = true;
	  }
	  else {
	    $rps_slider_shown = true;
	    return $rpsslider;
	  }
endif;
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

	return get_related_posts_slider($echo=false);
}
add_shortcode('rps', 'cf5_rps_shortcode');

function get_cf5_yarpp_related_posts($type,$args,$reference_ID=false) {
if(function_exists(yarpp_related)):
	global $wpdb, $post, $userdata, $yarpp_time, $yarpp_demo_time, $wp_query, $id, $page, $pages, $authordata, $day, $currentmonth, $multipage, $more, $numpages;
	
	get_currentuserinfo();

	// set the "domain prefix", used for all the preferences.
	$domainprefix = '';
	
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
	
	$yarpp_time = true; 
	
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
	$orders = split(' ',$order);
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
	$yarpp_time = false; // YARPP time is over... :(
	
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
<h2>Overall Slider Settings</h2> 
<table class="form-table">

<tr valign="top">
<th scope="row"><label for="cf5_rps_options[stylesheet]">Select the style for your Slider</label></th> 
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
</select><small>The CSS settings below are only applicable and visible in case you select 'default' stylesheet.</small></td></tr>

<tr valign="top">
<th scope="row">No. of Posts in one group of List Section</th>
<td><input type="text" name="cf5_rps_options[per_page]" id="cf5_rps_no_posts" class="small-text" value="<?php echo $cf5_rps['per_page']; ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">Slider Height</th>
<td><input type="text" name="cf5_rps_options[height]" id="cf5_rps_height" class="small-text" value="<?php echo $cf5_rps['height']; ?>" />&nbsp;px</td>
</tr>

    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Slider Background Color</th>
    <td><input type="text" name="cf5_rps_options[bgcolor]" id="color_value_1" value="<?php echo $cf5_rps['bgcolor']; ?>" />&nbsp; <img id="color_picker_1" src="<?php echo cf5_rps_url( 'images/color_picker.png' ); ?>" alt="Pick the color of your choice" /><div class="color-picker-wrap" id="colorbox_1"></div> <small>(If left empty, will pick inherited color)</small></td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Slider Foregound Color</th>
    <td><input type="text" name="cf5_rps_options[fgcolor]" id="color_value_2" value="<?php echo $cf5_rps['fgcolor']; ?>" />&nbsp; <img id="color_picker_2" src="<?php echo cf5_rps_url( 'images/color_picker.png' ); ?>" alt="Pick the color of your choice" /><div class="color-picker-wrap" id="colorbox_2"></div> </td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Background Color for Hover Section</th>
    <td><input type="text" name="cf5_rps_options[hvcolor]" id="color_value_3" value="<?php echo $cf5_rps['hvcolor']; ?>" />&nbsp; <img id="color_picker_3" src="<?php echo cf5_rps_url( 'images/color_picker.png' ); ?>" alt="Pick the color of your choice" /><div class="color-picker-wrap" id="colorbox_3"></div> </td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Text Color For Hover Section</th>
    <td><input type="text" name="cf5_rps_options[hvtext_color]" id="color_value_9" value="<?php echo $cf5_rps['hvtext_color']; ?>" />&nbsp; <img id="color_picker_9" src="<?php echo cf5_rps_url( 'images/color_picker.png' ); ?>" alt="Pick the color of your choice" /><div class="color-picker-wrap" id="colorbox_9"></div> </td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Outer Border Thickness</th>
    <td><input type="text" name="cf5_rps_options[obrwidth]" id="cf5_rps_obrwidth" class="small-text" value="<?php echo $cf5_rps['obrwidth']; ?>" />&nbsp;px &nbsp;(put 0 if no border is required)</td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Outer Border Color</th>
    <td><input type="text" name="cf5_rps_options[obrcolor]" id="color_value_4" value="<?php echo $cf5_rps['obrcolor']; ?>" />&nbsp; <img id="color_picker_4" src="<?php echo cf5_rps_url( 'images/color_picker.png' ); ?>" alt="Pick the color of your choice" /><div class="color-picker-wrap" id="colorbox_4"></div></td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Inner Border Thickness</th>
    <td><input type="text" name="cf5_rps_options[ibrwidth]" id="cf5_rps_obrwidth" class="small-text" value="<?php echo $cf5_rps['ibrwidth']; ?>" />&nbsp;px &nbsp;(put 0 if no border is required)</td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Inner Border Color</th>
    <td><input type="text" name="cf5_rps_options[ibrcolor]" id="color_value_5" value="<?php echo $cf5_rps['ibrcolor']; ?>" />&nbsp; <img id="color_picker_5" src="<?php echo cf5_rps_url( 'images/color_picker.png' ); ?>" alt="Pick the color of your choice" /><div class="color-picker-wrap" id="colorbox_5"></div></td>
    </tr>

</table> 

<h2>Slider Title</h2> 
<table class="form-table">

<tr valign="top">
<th scope="row">Slider Title Text</th>
<td><input type="text" name="cf5_rps_options[sldr_title]" class="regular-text code" value="<?php echo $cf5_rps['sldr_title']; ?>" /></td>
</tr>

    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Title Font</th>
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
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Slider Title Font Color</th>
    <td><input type="text" name="cf5_rps_options[stitle_color]" id="color_value_10" value="<?php echo $cf5_rps['stitle_color']; ?>" />&nbsp; <img id="color_picker_10" src="<?php echo cf5_rps_url( 'images/color_picker.png' ); ?>" alt="Pick the color of your choice" /><div class="color-picker-wrap" id="colorbox_10"></div></td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Slider Title Font Size</th>
    <td><input type="text" name="cf5_rps_options[stitle_size]" id="cf5_rps_stitle_size" class="small-text" value="<?php echo $cf5_rps['stitle_size']; ?>" />&nbsp;px</td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Slider Title Font Weight</th>
    <td><select name="cf5_rps_options[stitle_weight]" id="cf5_rps_stitle_weight" >
    <option value="bold" <?php if ($cf5_rps['stitle_weight'] == "bold"){ echo "selected";}?> >Bold</option>
    <option value="normal" <?php if ($cf5_rps['stitle_weight'] == "normal"){ echo "selected";}?> >Normal</option>
    </select>
    </td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Slider Title Font Style</th>
    <td><select name="cf5_rps_options[stitle_style]" id="cf5_rps_stitle_style" >
    <option value="italic" <?php if ($cf5_rps['stitle_style'] == "italic"){ echo "selected";}?> >Italic</option>
    <option value="normal" <?php if ($cf5_rps['stitle_style'] == "normal"){ echo "selected";}?> >Normal</option>
    </select>
    </td>
    </tr>
</table>

<h2>Thumbnail Image</h2> 
<p>Settings for the thumbnail image in Preview Section</p> 
<table class="form-table">

<tr valign="top"> 
<th scope="row">Image Pick Preferences <small>(The first one is having priority over second, the second on third and so on. Atleast select one option!)</small></th> 
<td><fieldset><legend class="screen-reader-text"><span>Image Pick Sequence <small>(The first one is having priority over second, the second having priority on third and so on)</small> </span></legend> 
<input name="cf5_rps_options[img_pick][0]" type="checkbox" value="1" <?php checked('1', $cf5_rps['img_pick'][0]); ?>  /> Use Custom Field/Key &nbsp; &nbsp; 
<input type="text" name="cf5_rps_options[img_pick][1]" class="text" value="<?php echo $cf5_rps['img_pick'][1]; ?>" /> Name of the Custom Field/Key
<br />
<input name="cf5_rps_options[img_pick][2]" type="checkbox" value="1" <?php checked('1', $cf5_rps['img_pick'][2]); ?>  /> Use Featured Post/Thumbnail (Wordpress 3.0 +  feature)&nbsp; <br />
<input name="cf5_rps_options[img_pick][3]" type="checkbox" value="1" <?php checked('1', $cf5_rps['img_pick'][3]); ?>  /> Consider Images attached to the post &nbsp; &nbsp; 
<input type="text" name="cf5_rps_options[img_pick][4]" class="small-text" value="<?php echo $cf5_rps['img_pick'][4]; ?>" /> Order of the Image attachment to pick &nbsp; <br />
<input name="cf5_rps_options[img_pick][5]" type="checkbox" value="1" <?php checked('1', $cf5_rps['img_pick'][5]); ?>  /> Scan images from the post, in case there is no attached image to the post&nbsp; 
</fieldset></td> 
</tr> 

<tr valign="top">
<th scope="row">Wordpress Image Extract Size</th>
<td><select name="cf5_rps_options[crop]" id="cf5_rps_img_crop" >
<option value="0" <?php if ($cf5_rps['crop'] == "0"){ echo "selected";}?> >Full</option>
<option value="1" <?php if ($cf5_rps['crop'] == "1"){ echo "selected";}?> >Large</option>
<option value="2" <?php if ($cf5_rps['crop'] == "2"){ echo "selected";}?> >Medium</option>
<option value="3" <?php if ($cf5_rps['crop'] == "3"){ echo "selected";}?> >Thumbnail</option>
</select>
<small>This is because, for every image upload to the media gallery WordPress creates four sizes of the same image. So you can choose which to load in the slider and then specify the actual size.</small>
</td>
</tr>

    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Align to</th>
    <td><select name="cf5_rps_options[img_align]" id="cf5_rps_img_align" >
    <option value="left" <?php if ($cf5_rps['img_align'] == "left"){ echo "selected";}?> >Left</option>
    <option value="right" <?php if ($cf5_rps['img_align'] == "right"){ echo "selected";}?> >Right</option>
    <option value="none" <?php if ($cf5_rps['img_align'] == "none"){ echo "selected";}?> >Center</option>
    </select>
    </td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>> 
    <th scope="row"><label for="cf5_rps_options[img_width]">Image Width</label></th> 
    <td><input type="text" name="cf5_rps_options[img_width]" class="small-text" value="<?php echo $cf5_rps['img_width']; ?>" />&nbsp;%&nbsp;&nbsp; </td> 
    </tr> 
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Maximum Height of the Image</th>
    <td><input type="text" name="cf5_rps_options[img_height]" class="small-text" value="<?php echo $cf5_rps['img_height']; ?>" />&nbsp;px &nbsp;&nbsp; (This is necessary in order to keep the maximum image height in control)</td>
    </tr>

</table>

<h2>List Section</h2> 
<table class="form-table">

    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Font</th>
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
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Font Color</th>
    <td><input type="text" name="cf5_rps_options[ltitle_color]" id="color_value_6" value="<?php echo $cf5_rps['ltitle_color']; ?>" />&nbsp; <img id="color_picker_6" src="<?php echo cf5_rps_url( 'images/color_picker.png' ); ?>" alt="Pick the color of your choice" /><div class="color-picker-wrap" id="colorbox_6"></div></td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Font Size</th>
    <td><input type="text" name="cf5_rps_options[ltitle_size]" id="cf5_rps_ltitle_size" class="small-text" value="<?php echo $cf5_rps['ltitle_size']; ?>" />&nbsp;px</td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Font Weight</th>
    <td><select name="cf5_rps_options[ltitle_weight]" id="cf5_rps_ltitle_weight" >
    <option value="bold" <?php if ($cf5_rps['ltitle_weight'] == "bold"){ echo "selected";}?> >Bold</option>
    <option value="normal" <?php if ($cf5_rps['ltitle_weight'] == "normal"){ echo "selected";}?> >Normal</option>
    </select>
    </td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Font Style</th>
    <td><select name="cf5_rps_options[ltitle_style]" id="cf5_rps_ltitle_style" >
    <option value="italic" <?php if ($cf5_rps['ltitle_style'] == "italic"){ echo "selected";}?> >Italic</option>
    <option value="normal" <?php if ($cf5_rps['ltitle_style'] == "normal"){ echo "selected";}?> >Normal</option>
    </select>
    </td>
    </tr>

<tr valign="top">
<th scope="row">Max words in List Title</th>
<td><input type="text" name="cf5_rps_options[ltitle_words]" id="cf5_rps_ltitle_words" class="small-text" value="<?php echo $cf5_rps['ltitle_words']; ?>" />&nbsp;words</td>
</tr>

</table>

<h2>Preview Section</h2> 
<table class="form-table">

    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Title Font</th>
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
    <th scope="row">Title Font Color</th>
    <td><input type="text" name="cf5_rps_options[ptitle_color]" id="color_value_7" value="<?php echo $cf5_rps['ptitle_color']; ?>" />&nbsp; <img id="color_picker_7" src="<?php echo cf5_rps_url( 'images/color_picker.png' ); ?>" alt="Pick the color of your choice" /><div class="color-picker-wrap" id="colorbox_7"></div></td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Title Font Size</th>
    <td><input type="text" name="cf5_rps_options[ptitle_size]" id="cf5_rps_ptitle_size" class="small-text" value="<?php echo $cf5_rps['ptitle_size']; ?>" />&nbsp;px</td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Title Font Weight</th>
    <td><select name="cf5_rps_options[ptitle_weight]" id="cf5_rps_ptitle_weight" >
    <option value="bold" <?php if ($cf5_rps['ptitle_weight'] == "bold"){ echo "selected";}?> >Bold</option>
    <option value="normal" <?php if ($cf5_rps['ptitle_weight'] == "normal"){ echo "selected";}?> >Normal</option>
    </select>
    </td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Title Font Style</th>
    <td><select name="cf5_rps_options[ptitle_style]" id="cf5_rps_ptitle_style" >
    <option value="italic" <?php if ($cf5_rps['ptitle_style'] == "italic"){ echo "selected";}?> >Italic</option>
    <option value="normal" <?php if ($cf5_rps['ptitle_style'] == "normal"){ echo "selected";}?> >Normal</option>
    </select>
    </td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Content Font</th>
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
    <th scope="row">Content Font Color</th>
    <td><input type="text" name="cf5_rps_options[pcontent_color]" id="color_value_8" value="<?php echo $cf5_rps['pcontent_color']; ?>" />&nbsp; <img id="color_picker_8" src="<?php echo cf5_rps_url( 'images/color_picker.png' ); ?>" alt="Pick the color of your choice" /><div class="color-picker-wrap" id="colorbox_8"></div></td>
    </tr>
    
    <tr valign="top" <?php if($cf5_rps['stylesheet']!='default') echo 'style="display:none;"';?>>
    <th scope="row">Content Font Size</th>
    <td><input type="text" name="cf5_rps_options[pcontent_size]" id="cf5_rps_pcontent_size" class="small-text" value="<?php echo $cf5_rps['pcontent_size']; ?>" />&nbsp;px</td>
    </tr>
    
<tr valign="top">
<th scope="row">Pick content From</th>
<td><select name="cf5_rps_options[pcontent_from]" id="cf5_rps_content_from" >
<option value="preview_content" <?php if ($cf5_rps['pcontent_from'] == "preview_content"){ echo "selected";}?> >preview_content Custom field</option>
<option value="excerpt" <?php if ($cf5_rps['pcontent_from'] == "excerpt"){ echo "selected";}?> >Post Excerpt</option>
<option value="content" <?php if ($cf5_rps['pcontent_from'] == "content"){ echo "selected";}?> >From Content</option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row">Max words in Preview Content</th>
<td><input type="text" name="cf5_rps_options[pcontent_words]" id="cf5_rps_pcontent_words" class="small-text" value="<?php echo $cf5_rps['pcontent_words']; ?>" />&nbsp;words</td>
</tr>

<tr valign="top">
<th scope="row">Show read more (continue reading) section</th>
<td><select name="cf5_rps_options[no_more]" id="target" >
<option value="0" <?php if ($cf5_rps['no_more'] == "0"){ echo "selected";}?> >Show</option>
<option value="1" <?php if ($cf5_rps['no_more'] == "1"){ echo "selected";}?> >Do not show</option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row">Continue Reading Text</th>
<td><input type="text" name="cf5_rps_options[more]" class="regular-text code" value="<?php echo $cf5_rps['more']; ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">Target attribute for the continue reading link</th>
<td><select name="cf5_rps_options[target]" id="target" >
<option value="_self" <?php if ($cf5_rps['target'] == "_self"){ echo "selected";}?> >_self</option>
<option value="_blank" <?php if ($cf5_rps['target'] == "_blank"){ echo "selected";}?> >_blank</option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row">Retain these html tags</th>
<td><input type="text" name="cf5_rps_options[allowable_tags]" class="regular-text code" value="<?php echo $cf5_rps['allowable_tags']; ?>" />&nbsp;(read <a href="http://www.clickonf5.org/related-posts-slider" title="how to retain html like line breaks and links in the Related Posts Slider" target="_blank">Usage section of the plugin page</a> to know more)</td>
</tr>

</table>

<h2>Manual/Automatic Insertion</h2> 
<small>By default the related posts slider is inserted automatically below the content area of the post. But you can select manual insertion (either using templte tag or shortcode or widget) or can select to insert it automatically above the content area of the post.</small>
<table class="form-table">
    <tr valign="top">
    <th scope="row">Insert the slider</th>
    <td><select name="cf5_rps_options[insert]" id="cf5_rps_insert" >
    <option value="content_down" <?php if ($cf5_rps['insert'] == "content_down"){ echo "selected";}?> >Below the Content</option>
    <option value="content_up" <?php if ($cf5_rps['insert'] == "content_up"){ echo "selected";}?> >Above the Content</option>
    <option value="manual" <?php if ($cf5_rps['insert'] == "manual"){ echo "selected";}?> >Manually</option>
    </select>
    </td>
    </tr>
    
    <tr valign="top">
    <th scope="row">Support 'Related Posts Slider'</th>
    <td><select name="cf5_rps_options[support]" id="support" >
    <option value="0" <?php if ($cf5_rps['support'] == "0"){ echo "selected";}?> >No</option>
    <option value="1" <?php if ($cf5_rps['support'] == "1"){ echo "selected";}?> >Yes</option>
    </select><small>Share the word, in case you select 'No', please consider donating and help the development!</small>
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
			  <h3 class="hndle"><span>About this Plugin:</span></h3> 
			  <div class="inside">
                <ul>
                <li><a href="http://www.clickonf5.org/related-posts-slider" title="Related Posts Slider WP Plugin Homepage" >Plugin Homepage</a></li>
                <li><a href="http://www.clickonf5.org/" title="Visit Internet Techies" >Plugin Parent Site</a></li>
                <li><a href="http://www.clickonf5.org/about/tejaswini" title="Related Posts Slider WP Plugin Author Page" >About the Author</a></li>
                <li><a href="http://www.clickonf5.org/go/donate-wp-plugins/" title="Donate if you liked the plugin and support in enhancing this plugin and creating new plugins" >Donate with Paypal</a></li>
                </ul> 
              </div> 
			</div> 
     </div>
     <div id="side-info-column" class="inner-sidebar"> 
			<div class="postbox"> 
			  <h3 class="hndle"><span></span>Our Facebook Fan Page</h3> 
			  <div class="inside">
                <script type="text/javascript" src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/en_GB"></script><script type="text/javascript">FB.init("2aeebe9fb014836a6810ec4426d26f7e");</script><fb:fan profile_id="127760528543" stream="" connections="8" width="270" height="250"></fb:fan>
              </div> 
			</div> 
     </div>
     
     <div id="side-info-column" class="inner-sidebar"> 
			<div class="postbox"> 
			  <h3 class="hndle"><span>Latest on Internet Techies</span></h3> 
			  <div class="inside">
                <?php $postsarr = cf5_rps_parse_rss_rand('http://www.clickonf5.org/feed','8'); 
		        if($postsarr) {?>
                <ul>
                <?php foreach($postsarr as $itpost) { ?>
                <li>&raquo; <a href="<?php echo $itpost['link'];?>" title="Read more about <?php echo $itpost['title'];?>" ><?php echo $itpost['title'];?></a></li>
                <?php } ?>
                </ul> 
                <?php } ?>
              </div> 
			</div> 
     </div>

     <div id="side-info-column" class="inner-sidebar"> 
			<div class="postbox"> 
			  <h3 class="hndle"><span>Credits:</span></h3> 
			  <div class="inside">
                <ul>
                <li><a href="http://tympanus.net/codrops/2010/10/03/compact-news-previewer/" title="Compact News Previewer with jQuery" >Compact News Previewer</a></li>
                <li><a href="http://acko.net/dev/farbtastic" title="Farbtastic Color Picker by Steven Wittens" >Farbtastic Color Picker</a></li>
                <li><a href="http://jquery.com/" title="jQuery JavaScript Library - John Resig" >jQuery JavaScript Library</a></li>
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