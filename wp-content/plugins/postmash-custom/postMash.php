<?php
/*
Plugin Name: PostMash Custom
Plugin URI: http://opperud.com/postmash_custom
Description: PostMash Custom > Reorder Posts (now also custom post types and custom taxonomies) by drag'n'drop. Allow admin panel to filter by category/date. Allow Next/Previous posts by postMash order.
Author: Torstein Opperud
Version: 1.0.3
Author URI: http://opperud.com
	
*/
#########CONFIG OPTIONS############################################
$minlevel = 7;  /*[deafult=7]*/
/* Minimum user level to access page order */

$switchDraftToPublishFeature = true;  /*[deafult=true]*/
/* Allows you to set pages not to be listed */

$ShowDegubInfo = false;  /*[deafult=false]*/
/* Show server response debug info */

###################################################################
/*
INSPIRATIONS/CREDITS:
Torstein Opperud - This updated version - [http://opperud.com/postmash_custom]
Selwyn Nogood - This plugin is a further adaption of Selwyn Nogoods postMash (filtered) [http://postmashfiltered.wordpress.com]
Joel Starnes - This plugin is a modification of Joel Starnes great postMash plugin [http://joelstarnes.co.uk/postMash/]
Valerio Proietti - Mootools JS Framework [http://mootools.net/]
Stefan Lange-Hegermann - Mootools AJAX timeout class extension [http://www.blackmac.de/archives/44-Mootools-AJAX-timeout.html]
vladimir - Mootools Sortables class extension [http://vladimir.akilles.cl/scripts/sortables/]
ShiftThis - WP Page Order Plugin [http://www.shiftthis.net/wordpress-order-pages-plugin/]
Garrett Murphey - Page Link Manager [http://gmurphey.com/2006/10/05/wordpress-plugin-page-link-manager/]
*/

/*  Copyright 2010  Torstein Opperud  (email : post@opperud.com)

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

// Pre-2.6 compatibility
if ( !defined('WP_CONTENT_URL') )
	define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
// Guess the location
$codeWord_path = WP_CONTENT_DIR.'/plugins/'.plugin_basename(dirname(__FILE__));
$postMash_url = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__));

function postMash_getPages(){
	global $wpdb, $wp_version, $switchDraftToPublishFeature;
	
	$postType = array();
	$postTypeDb = array();
	//$postType[] = 'post';
	$postType[] = 'page';
	$postType[] = 'mediapage';
	$postType[] = 'attachment';
	$postType[] = 'revision';
	$postType[] = 'nav_menu_item';
	
	
	$query_post_type = "SELECT post_type FROM $wpdb->posts GROUP BY post_type ";
	$posttypes = $wpdb->get_results("$query_post_type");
	
	//print_r($posttypes);
	
	foreach ($posttypes as $ptype)
	{
		//echo $ptype->post_type;
		$postTypeDb[] = $ptype->post_type;
	}
	//print_r($postType);
	//print_r($postTypeDb);
	
	$res = array_diff($postTypeDb,$postType);
	
	$psql = "";
	//print_r($res);
	$c = 1;
	foreach($res as $r)
	{
		$psql .= " post_type ='$r' ";
		if ($c < count($res))
			$psql .= " OR ";
		$c++;
	}
	$psql = " ( $psql )  AND post_status = 'publish'";
	//get pages from database
	$date = $_GET['m'];
	$mmonth = substr($date, -2); 
	$yyear = substr($date, 0, 4); 
	$query_post .= "SELECT * FROM $wpdb->posts ";
	
	
	if (isset($_GET['cat']) && $_GET['cat'] != '0'){
									$query_post .=	"INNER JOIN $wpdb->term_relationships ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
													INNER JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
													WHERE $wpdb->term_taxonomy.term_id = " . $_GET['cat'] ;
		if (isset($_GET['m']) && $_GET['m'] != '0' ) {$query_post .= " AND YEAR($wpdb->posts.post_date) = " . $yyear ." AND MONTH($wpdb->posts.post_date) = " . $mmonth ;}		
			//$query_post .=	" AND $wpdb->posts.post_type = 'post' ORDER BY menu_order ";
			$query_post .=	" AND $psql ORDER BY menu_order ";
		}
	else {
	$query_post .= "WHERE $psql ";
	if (isset($_GET['m']) && $_GET['m'] != '0') {$query_post .= " AND YEAR(post_date) = " . $yyear ." AND MONTH(post_date) = " . $mmonth ;}	
	$query_post .= " ORDER BY menu_order " ;}
	
	//echo $query_post;

	$pageposts = $wpdb->get_results("$query_post");

	if ($pageposts == true){
		echo '<ul id="postMash_pages">';
		foreach ($pageposts as $page): //list pages, [the 'li' ID must be pm_'page ID'] ?>
			<li id="pm_<?php echo $page->ID; ?>"<?php if($page->post_status == "draft"){ echo ' class="remove"'; } //if page is draft, add class remove ?>>
				<span style="float:right;">
				<?php
			$tags = get_the_tags($page->ID);
			if ( !empty( $tags ) ) {
				$out = array();
				foreach ( $tags as $c )
					$out[] = wp_specialchars(sanitize_term_field('name', $c->name, $c->term_id, 'post_tag', 'display'));
				echo join( ', ', $out );
			} else {
				_e('');
			} ?></span><span class="title"><?php echo $page->post_title;?></span>
				<span class="postMash_box">
					<span class="postMash_more">&raquo;</span>
					<span class="postMash_pageFunctions">
						id:<?php echo $page->ID;?>
						[<a href="<?php echo get_bloginfo('wpurl').'/wp-admin/post.php?action=edit&post='.$page->ID; ?>" title="Edit This Post">edit</a>]
						<?php if($switchDraftToPublishFeature): ?>
							[<a href="#" title="Draft|Publish" class="excludeLink" onclick="toggleRemove(this); return false">toggle-draft</a>]
						<?php endif; ?>
					</span>
				</span>
			</li>
		<?php endforeach;
		echo '</ul>';
		return true;
	} else {
		echo '<h3 style="margin-top:30px;" >Sorry, there is nothing for this month!</h3>';
		return false;
		
	}
	
}

function my_wp_dropdown_categories( $args = '' ) {
	
	global $wpdb;
	$defaults = array(
		'show_option_all' => '', 'show_option_none' => '',
		'orderby' => 'id', 'order' => 'ASC',
		'show_last_update' => 0, 'show_count' => 0,
		'hide_empty' => 1, 'child_of' => 0,
		'exclude' => '', 'echo' => 1,
		'selected' => 0, 'hierarchical' => 0,
		'name' => 'cat', 'id' => '',
		'class' => 'postform', 'depth' => 0,
		'tab_index' => 0, 'taxonomy' => 'category',
		'hide_if_empty' => false
	);
	
	$taxonomy = array();
	$taxonomy[] = 'category';
	$taxonomy[] = 'post_tag';
	$taxonomy[] = 'link_category';
	$taxonomy[] = 'nav_menu';
	
	$query_taxo = "SELECT taxonomy FROM $wpdb->term_taxonomy GROUP BY taxonomy ";
	$taxonomyList = $wpdb->get_results("$query_taxo");
	foreach ($taxonomyList as $txo)
	{
		$taxoDb[] = $txo->taxonomy;
	}
	$resTexo = array_diff($taxoDb,$taxonomy);
	//print_r($resTexo);

	$defaults['selected'] = ( is_category() ) ? get_query_var( 'cat' ) : 0;

	// Back compat.
	if ( isset( $args['type'] ) && 'link' == $args['type'] ) {
		_deprecated_argument( __FUNCTION__, '3.0', '' );
		$args['taxonomy'] = 'link_category';
	}
	//$args['taxonomy'] = 'Skills';

	$r = wp_parse_args( $args, $defaults );

	if ( !isset( $r['pad_counts'] ) && $r['show_count'] && $r['hierarchical'] ) {
		$r['pad_counts'] = true;
	}

	$r['include_last_update_time'] = $r['show_last_update'];
	extract( $r );

	$tab_index_attribute = '';
	if ( (int) $tab_index > 0 )
		$tab_index_attribute = " tabindex=\"$tab_index\"";
		
	//echo $order;

	$categories = get_terms( $taxonomy, $r );
	$name = esc_attr( $name );
	$class = esc_attr( $class );
	$id = $id ? esc_attr( $id ) : $name;

	if ( ! $r['hide_if_empty'] || ! empty($categories) )
		$output = "<select name='$name' id='$id' class='$class' $tab_index_attribute>\n";
	else
		$output = '';

	if ( empty($categories) && ! $r['hide_if_empty'] && !empty($show_option_none) ) {
		$show_option_none = apply_filters( 'list_cats', $show_option_none );
		$output .= "\t<option value='-1' selected='selected'>$show_option_none</option>\n";
	}

	if ( ! empty( $categories ) ) {

		if ( $show_option_all ) {
			$show_option_all = apply_filters( 'list_cats', $show_option_all );
			$selected = ( '0' === strval($r['selected']) ) ? " selected='selected'" : '';
			$output .= "\t<option value='0'$selected>$show_option_all</option>\n";
		}

		if ( $show_option_none ) {
			$show_option_none = apply_filters( 'list_cats', $show_option_none );
			$selected = ( '-1' === strval($r['selected']) ) ? " selected='selected'" : '';
			$output .= "\t<option value='-1'$selected>$show_option_none</option>\n";
		}

		if ( $hierarchical )
			$depth = $r['depth'];  // Walk the full depth.
		else
			$depth = -1; // Flat.

		
		$output .= walk_category_dropdown_tree( $categories, $depth, $r );
		
		//$r['taxonomy'] = 'Skills';
		//print_r($r);
		
		foreach($resTexo as $rt)
		{
			//echo $rt;
			$categories = get_terms( $rt, $r );
			$output .= walk_category_dropdown_tree( $categories, $depth, $r );
		}
	}
	if ( ! $r['hide_if_empty'] || ! empty($categories) )
		$output .= "</select>\n";


	$output = apply_filters( 'wp_dropdown_cats', $output );

	if ( $echo )
		echo $output;

	return $output;
}


function postMash_main(){
	global $switchDraftToPublishFeature, $ShowDegubInfo;
	?>
	<div id="debug_list"<?php if(false==$ShowDegubInfo) echo' style="display:none;"'; ?>></div>
	<div id="postMash" class="wrap">
		<div id="postMash_checkVersion" style="float:right; font-size:.7em; margin-top:5px;">
		    version 1.1.0
		</div>
		<h2 style="margin-bottom:0; clear:none;">postMash: Post Ordering</h2>
		<p style="margin-top:4px;">
			Just drag the posts <strong>up</strong> or <strong>down</strong> to change their order. <?php echo "The draft button will toggle the page between draft and published states."; ?>
		</p><p>
					<div class="alignleft actions">
					
			<?php // view filters
			if ( !is_singular() ) {
			global $wpdb, $wp_locale;
			$arc_query = "SELECT DISTINCT YEAR(post_date) AS yyear, MONTH(post_date) AS mmonth FROM $wpdb->posts WHERE post_type = 'post' ORDER BY post_date DESC";
			
			$arc_result = $wpdb->get_results( $arc_query );
			
			$month_count = count($arc_result);
			
			if ( $month_count && !( 1 == $month_count && 0 == $arc_result[0]->mmonth ) ) {
			$m = isset($_GET['m']) ? (int)$_GET['m'] : 0;
			?>
			<form action="" method="get" enctype="multipart/form-data">
			<input type="hidden" name="page" value="postmash-custom/postMash.php"  />
			<select name='m'>
			<option<?php selected( $m, 0 ); ?> value='0'><?php _e('Show all dates'); ?></option>
			<?php
			foreach ($arc_result as $arc_row) {
				if ( $arc_row->yyear == 0 )
					continue;
				$arc_row->mmonth = zeroise( $arc_row->mmonth, 2 );
			
				if ( $arc_row->yyear . $arc_row->mmonth == $m )
					$default = ' selected="selected"';
				else
					$default = '';
			
				echo "<option$default value='$arc_row->yyear$arc_row->mmonth'>";
				echo $wp_locale->get_month($arc_row->mmonth) . " $arc_row->yyear";
				echo "</option>\n";
			}
			?>
			</select>
			<?php } ?>
			
			<?php
			$cat = $_GET['cat'];
			$dropdown_options = array('show_option_all' => __('View all categories'), 'hide_empty' => 0, 'hierarchical' => 1, 'show_count' => 0, 'orderby' => 'name', 'selected' => $cat, 'taxonomy' => 'category');
			my_wp_dropdown_categories($dropdown_options);
			do_action('restrict_manage_posts');
			?>
			
			<input type="submit" id="post-query-submit" value="<?php _e('Filter'); ?>" class="button-secondary" />
			</form>
			<?php } ?>
			</div>
			
			</p>
			<div style="clear:both; height:20px;"> </div>
		
		<?php postMash_getPages(); ?>
		
		<p class="submit">
			<div id="update_status" style="float:left; margin-left:40px; opacity:0;"></div>
				<input type="submit" id="postMash_submit" tabindex="2" style="font-weight: bold; float:right;" value="Update" name="submit"/>
		</p>
		<br style="margin-bottom: .8em;" />
	</div>

	<div class="wrap" style="width:160px; margin-bottom:0; padding:0;"><p><a href="#" id="postMashInfo_toggle">Show|Hide Further Info</a></p></div>
	<div class="wrap" id="postMashInfo" style="margin-top:-1px;">
		<h2>How to Use</h2>
		<p>In order to make use of postMash, you need to order your posts by "menu_order". Like this...</p>
		<p style="margin-bottom:0; font-weight:bold;">Code:</p>
		<code id="postMash_code"><span class="white">
			&lt;?php $readposts = get_posts(&#x27;orderby=menu_order&#x27;); ?&gt;<br />
			&lt;ul&gt;<br />
			&lt;?php foreach($readposts as $post) : setup_postdata($post); ?&gt;<br />
				&lt;li&gt;&lt;a href=&quot;#&quot;&gt;&lt;?php the_title(); ?&gt;&lt;/a&gt;&lt;/li&gt;<br />
			&lt;?php endforeach; ?&gt;<br />
			&lt;/ul&gt;
		</span></code>
		<p><a href="http://opperud.com/postmash_custom/">Homepage </a></p>
		
		<br />
	</div>
	<?php
}

function postMash_head(){
	//stylesheet & javascript to go in page header
	global $postMash_url;
	
	wp_enqueue_script('postMash_mootools', $postMash_url.'/nest-mootools.v1.11.js', false, false); //code is not compatible with other releases of moo
	wp_deregister_script('prototype');//remove prototype since it is incompatible with mootools
	wp_enqueue_script('postMash', $postMash_url.'/postMash.js', array('postMash_mootools'), false);
	add_action('admin_head', 'postMash_add_css', 1);

}

function postMash_add_css(){
	global $postMash_url;
	printf('<link rel="stylesheet" type="text/css" href="%s/postMash.css" />', $postMash_url);
	?>
<!-- BASED ON
	                    __  __           _     
      WordPress Plugin |  \/  |         | |    
  _ __  __ _  __ _  ___| \  / | __ _ ___| |__  
 | '_ \/ _` |/ _` |/ _ \ |\/| |/ _` / __| '_ \ 
 | |_)  (_| | (_| |  __/ |  | | (_| \__ \ | | |
 | .__/\__,_|\__, |\___|_|  |_|\__,_|___/_| |_|
 | |          __/ |  Author: Joel Starnes
 |_|         |___/   URL: joelstarnes.co.uk
 
 >>postMash Admin Page
-->
	<?php
}

function postMash_add_pages(){
	//add menu link
	global $minlevel, $wp_version;
	if($wp_version >= 2.7){
		$page = add_submenu_page('edit.php', 'postMash Custom: Order Posts', 'Reorder posts', $minlevel,  __FILE__, 'postMash_main'); 
	}else{
		$page = add_management_page('postMash Custom: Order Posts', 'Reorder posts', $minlevel, __FILE__, 'postMash_main');
	}
	add_action("admin_print_scripts-$page", 'postMash_head'); //add css styles and JS code to head
}

add_action('admin_menu', 'postMash_add_pages'); //add admin menu under management tab

/**
 * Modifications to allow Next/Previous Post Link by menu_order using postMash plugin.
*/
function get_previous_post_menu($in_same_cat = false, $excluded_categories = '') {
	return get_adjacent_post_menu($in_same_cat, $excluded_categories);
}


function get_next_post_menu($in_same_cat = false, $excluded_categories = '') {
	return get_adjacent_post_menu($in_same_cat, $excluded_categories, false);
}

function previous_post_link_menu($format='&laquo; %link', $link='%title', $in_same_cat = true, $excluded_categories = '') {
	adjacent_post_link_menu($format, $link, $in_same_cat, $excluded_categories, false);
}

function next_post_link_menu($format='%link &raquo;', $link='%title', $in_same_cat = true, $excluded_categories = '') {
	adjacent_post_link_menu($format, $link, $in_same_cat, $excluded_categories, true);
}

function get_adjacent_post_menu($in_same_cat = false, $excluded_categories = '', $previous = true) {
	global $post, $wpdb;

	if( empty($post) || !is_single() || is_attachment() )
		return null;

	$current_post_date = $post->post_date;
	$current_menu_order = $post->menu_order;
	$join = '';
	$posts_in_ex_cats_sql = '';
	if ( $in_same_cat || !empty($excluded_categories) ) {
		$join = " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";

		if ( $in_same_cat ) {
			$cat_array = wp_get_object_terms($post->ID, 'category', 'fields=ids');
			$join .= " AND tt.taxonomy = 'category' AND tt.term_id IN (" . implode(',', $cat_array) . ")";
		}

		$posts_in_ex_cats_sql = "AND tt.taxonomy = 'category'";
		if ( !empty($excluded_categories) ) {
			$excluded_categories = array_map('intval', explode(' and ', $excluded_categories));
			if ( !empty($cat_array) ) {
				$excluded_categories = array_diff($excluded_categories, $cat_array);
				$posts_in_ex_cats_sql = '';
			}

			if ( !empty($excluded_categories) ) {
				$posts_in_ex_cats_sql = " AND tt.taxonomy = 'category' AND tt.term_id NOT IN (" . implode($excluded_categories, ',') . ')';
			}
		}
	}

	$adjacent = $previous ? 'previous' : 'next';
	$op = $previous ? '<' : '>';
	$order = $previous ? 'DESC' : 'ASC';

	$join  = apply_filters( "get_{$adjacent}_post_join", $join, $in_same_cat, $excluded_categories );
	$where = apply_filters( "get_{$adjacent}_post_where", $wpdb->prepare("WHERE p.menu_order $op %s AND p.post_type = 'post' AND p.post_status = 'publish' $posts_in_ex_cats_sql", $current_menu_order), $in_same_cat, $excluded_categories );
	$sort  = apply_filters( "get_{$adjacent}_post_sort", "ORDER BY p.menu_order $order LIMIT 1" );

	return $wpdb->get_row("SELECT p.* FROM $wpdb->posts AS p $join $where $sort");
}

function adjacent_post_link_menu($format, $link, $in_same_cat = false, $excluded_categories = '', $previous = true) {
	if ( $previous && is_attachment() )
		$post = & get_post($GLOBALS['post']->post_parent);
	else
		$post = get_adjacent_post_menu($in_same_cat, $excluded_categories, $previous);

	if ( !$post )
		return;

	$title = $post->post_title;

	if ( empty($post->post_title) )
		$title = $previous ? __('Previous Post') : __('Next Post');

	$title = apply_filters('the_title', $title, $post);
	$date = mysql2date(get_option('date_format'), $post->post_date);

	$string = '<a href="'.get_permalink($post).'">';
	$link = str_replace('%title', $title, $link);
	$link = str_replace('%date', $date, $link);
	$link = $string . $link . '</a>';

	$format = str_replace('%link', $link, $format);

	$adjacent = $previous ? 'previous' : 'next';
	echo apply_filters( "{$adjacent}_post_link", $format, $link );
}

?>