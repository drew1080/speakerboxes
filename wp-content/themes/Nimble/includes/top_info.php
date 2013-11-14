<div id="page-name">	
<?php 
	$et_tagline = '';
	if( is_tag() ) {
		$et_page_title = esc_html__('Posts Tagged &quot;','Nimble') . single_tag_title('',false) . '&quot;';
	} elseif (is_day()) {
		$et_page_title = esc_html__('Posts made in','Nimble') . ' ' . get_the_time('F jS, Y');
	} elseif (is_month()) {
		$et_page_title = esc_html__('Posts made in','Nimble') . ' ' . get_the_time('F, Y');
	} elseif (is_year()) {
		$et_page_title = esc_html__('Posts made in','Nimble') . ' ' . get_the_time('Y');
	} elseif (is_search()) {
		$et_page_title = esc_html__('Search results for','Nimble') . ' ' . get_search_query();
	} elseif (is_category()) {
		$et_page_title = single_cat_title('',false);
		$et_tagline = category_description();
	} elseif (is_author()) {
		global $wp_query;
		$curauth = $wp_query->get_queried_object();
		$et_page_title = esc_html__('Posts by ','Nimble') . $curauth->nickname;
	} elseif ( is_page() || is_single() ) {
		$et_page_title = get_the_title();
		if ( is_page() ) $et_tagline = get_post_meta(get_the_ID(),'Description',true) ? get_post_meta(get_the_ID(),'Description',true) : '';
	} elseif ( is_tax() ){
		$et_page_title = single_term_title( '', false );
		$et_tagline = term_description();
	}
?>
	<hgroup class="section-title">
		<h1><?php echo wp_kses( $et_page_title, array( 'span' => array() ) ); ?></h1>
	<?php if ( $et_tagline <> '' ) { ?>
		<h3><?php echo wp_kses( $et_tagline, array( 'span' => array() ) ); ?></h3>
	<?php } ?>
	<?php
		if ( is_single() && 'project' != get_post_type( get_the_ID() ) ) {
			$single_postinfo = et_get_option( 'nimble_postinfo2' );
			if ( $single_postinfo && have_posts() ) {
				the_post();
				
				echo '<p class="main_post_info">';
				et_postinfo_meta( $single_postinfo, et_get_option('nimble_date_format'), esc_html__('0 comments','Nimble'), esc_html__('1 comment','Nimble'), '% ' . esc_html__('comments','Nimble') );
				echo '</p>';
				
				rewind_posts();
			}
		}
	?>
	</hgroup>
</div> <!-- end #page-name -->