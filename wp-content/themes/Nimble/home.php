<?php get_header(); ?>

<?php if ( 'false' == et_get_option( 'nimble_blog_style', 'false' ) ){ ?>
	<?php if ( 'on' == et_get_option( 'nimble_display_services', 'on' ) || 'on' == et_get_option( 'nimble_display_quote', 'on' ) ){ ?>
		<div id="home-section-info" class="home-section">
			<div class="container">
		<?php 
			if ( 'on' == et_get_option( 'nimble_display_services', 'on' ) ){
				$blurbs_number = (int) apply_filters( 'et_blurbs_number', 3 );
				echo '<div id="services" class="clearfix">';
					for ( $i = 1; $i <= $blurbs_number; $i++ ){
						$service_query = new WP_Query( apply_filters( 'et_service_query_args', 'page_id=' . get_pageId( html_entity_decode( et_get_option( 'nimble_home_page_' . $i ) ) ), $i ) );
						while ( $service_query->have_posts() ) : $service_query->the_post();
							global $more;
							$more = 0;
							$page_title = ( $blurb_custom_title = get_post_meta( get_the_ID(), 'Blurbtitle', true ) ) && '' != $blurb_custom_title ? $blurb_custom_title : get_the_title();
							$page_permalink = ( $blurb_custom_permalink = get_post_meta( get_the_ID(), 'Blurblink', true ) ) && '' != $blurb_custom_permalink ? $blurb_custom_permalink : get_permalink();
							
							echo '<div class="service' . ( 1 == $i ? ' first' : '' ) . ( $blurbs_number == $i ? ' last' : '' ) . '">';
								if ( ( $page_icon = get_post_meta( get_the_ID(), 'Icon', true ) ) && '' != $page_icon )
									printf( '<img src="%1$s" alt="%2$s" class="et_page_icon" />', esc_attr( $page_icon ), esc_attr( $page_title ) );
								
								echo '<h3>' . $page_title . '</h3>';
								
								if ( has_excerpt() ) the_excerpt();
								else the_content( '' );
								
								echo '<a href="' . esc_url( $page_permalink ) . '" class="learn-more">' . __( 'Learn More', 'Nimble' ) . '</a>';
								
							echo '</div> <!-- end .service -->';
						endwhile; 
						wp_reset_postdata();
					}
				echo '</div> <!-- end #services -->';
			} 
		?>

		<?php 
			if ( 'on' == et_get_option( 'nimble_display_quote', 'on' ) ){
				echo '<div id="quote">';
					if ( ( $quote_first_line = et_get_option( 'nimble_quote_first_line' ) ) && '' != $quote_first_line )
						echo '<h3>' . wp_kses_post( $quote_first_line ) . '</h3>';
					if ( ( $quote_second_line = et_get_option( 'nimble_quote_second_line' ) ) && '' != $quote_second_line )
						echo '<p>' . wp_kses_post( $quote_second_line ) . '</p>';
				echo '</div> <!-- end #quote -->';
			}
		?>
			</div> <!-- end .container -->
		</div> <!-- end #home-section-info -->
	<?php } ?>

	<?php if ( 'on' == et_get_option( 'nimble_display_fromblog_section', 'on' ) ){ ?>
		<div id="home-section-news" class="home-section">
			<div class="container">
				<hgroup class="section-title">
					<h2><?php echo et_get_option( 'nimble_news_text', 'News &amp; Updates' ); ?></h2>
					<h3><?php echo esc_html( et_get_option( 'nimble_news_description_text', 'This Is a Description For The Homepage' ) ); ?></h3>
				</hgroup>
				
			<?php
				$i = 1;
				$blog_posts_per_row = (int) apply_filters( 'et_blog_posts_per_row', 3 );
			?>
				
				<div id="blog-posts" class="clearfix">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<div class="blog-post<?php if ( $i % $blog_posts_per_row == 0 ) echo ' last'; ?>">
					<?php
						$thumb = '';
						$width = apply_filters( 'et_blog_image_width', 80 );
						$height = apply_filters( 'et_blog_image_height', 80 );
						$classtext = '';
						$titletext = get_the_title();
						$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
						$thumb = $thumbnail["thumb"];
						
						if ( '' != $thumb ) {
							echo '<div class="blog-post-image">';
								echo '<a href="' . esc_url( get_permalink() ) . '">';
									print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext );
									echo '<span class="overlay"></span>';
								echo '</a>';
							echo '</div> <!-- end .blog-post-image -->';
						}
					?>
		
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<?php 
						printf ( __( '<p class="meta-info">Posted by <a href="%1$s">%2$s</a> on %3$s</p>', 'Nimble' ),
							esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
							get_the_author(),
							esc_html( get_the_time( et_get_option( 'nimble_date_format', 'M j, Y' ) ) )
						);
					?>
						<p><?php truncate_post( 270 ); ?></p>
						<a href="<?php the_permalink(); ?>" class="learn-more"><?php esc_html_e( 'Learn More', 'Nimble' ); ?></a>
					</div> <!-- end .blog-post -->
			<?php
					$i++;
					endwhile;
				endif; 
			?>
				</div> <!-- end #blog-posts -->	
			<?php 
				if ( ( $news_url = et_get_option( 'nimble_news_url' ) ) && '' != $news_url )
					echo '<a href="' . esc_url( $news_url ) . '" class="more-info">' . esc_html__( 'View More Blog Posts', 'Nimble' ) . '</a>'; 
			?>
			</div> <!-- end .container -->
		</div> <!-- end #home-section-news -->
	<?php } ?>

	<?php if ( 'on' == et_get_option( 'nimble_display_recentwork_section', 'on' ) ){ ?>
		<div id="home-section-projects" class="home-section">
			<div class="container">
				<hgroup class="section-title">
					<h2><?php echo et_get_option( 'nimble_work_text', 'Work <span>&amp;</span> Feedback' ); ?></h2>
					<h3><?php echo esc_html( et_get_option( 'nimble_work_description_text', 'Work &amp; Feedback section description' ) ); ?></h3>
				</hgroup>
				
			<?php
				$portfolio_args = array(
					'post_type' => 'project',
					'posts_per_page' => (int) et_get_option( 'nimble_homepage_numposts_projects', '3' )
				);
				
				if ( false != et_get_option( 'nimble_homepage_exlcats_projects', false ) )
					$portfolio_args['tax_query'] = array(
						array(
							'taxonomy' => 'project_category',
							'field' => 'id',
							'terms' => (array) array_map( 'intval', et_get_option( 'nimble_homepage_exlcats_projects' ) ),
							'operator' => 'NOT IN'
						)
					);

				$portfolio_query = new WP_Query( apply_filters( 'et_home_portfolio_args', $portfolio_args ) );
				if ( $portfolio_query->have_posts() ){
					$i = 1;
					$portfolios_per_row = (int) apply_filters( 'et_portfolios_per_row', 3 );
					
					echo '<div id="portfolio_items">';
						while ( $portfolio_query->have_posts() ) : $portfolio_query->the_post();			
							$thumb = '';
							$width = (int) apply_filters( 'et_portfolio_image_width', 266 );
							$height = (int) apply_filters( 'et_portfolio_image_height', 266 );
							$classtext = '';
							$titletext = get_the_title();
							$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Portfolioimage' );
							$thumb = $thumbnail["thumb"];
							
							if ( '' != $thumb ) {
								echo '<div class="portfolio-image' . ( $i % $portfolios_per_row == 0 ? ' last' : '' ) . '">';								
									print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext );
									echo '<span class="overlay"></span>';
									
									echo '<div class="portfolio_description">';
										echo '<div class="portfolio_info_top">';
											echo '<p class="portfolio_small_date">' . get_the_time( _x( 'M j, Y', 'small date in portfolio description', 'Nimble' ) ) . '</p>';
											printf ( '<h3 class="title"><a href="%1$s">%2$s</a></h3>', esc_url( get_permalink() ), esc_html( get_the_title() ) );
										echo '</div> <!-- end .portfolio_info_top -->';
										
										echo '<a href="' . esc_url( get_permalink() ) . '">' . __( 'Read More', 'Nimble' ) . '</a>';
									echo '</div>';
								echo '</div> <!-- end .portfolio-image -->';
							}
							
							$i++;
						endwhile;
					echo '</div> <!-- end #portfolio_items -->';
				}
				wp_reset_postdata();
				
				if ( ( $projects_url = et_get_option( 'nimble_projects_url' ) ) && '' != $projects_url )
					echo '<a href="' . esc_url( $projects_url ) . '" class="more-info">' . esc_html__( 'View More Projects', 'Nimble' ) . '</a>';
			?>
			</div> <!-- end .container -->
		</div> <!-- end #home-section-projects -->
	<?php } ?>

	<?php if ( 'on' == et_get_option( 'nimble_display_pricing', 'on' ) ){ ?>
		<div id="home-section-pricing" class="home-section">
			<div class="container">
				<hgroup class="section-title">
					<h2><?php echo et_get_option( 'nimble_plans_text', 'Plans <span>&amp;</span> Pricing' ); ?></h2>
					<h3><?php echo esc_html( et_get_option( 'nimble_plans_description_text', 'Plans &amp; Pricing section description' ) ); ?></h3>
				</hgroup>
			<?php
				$pricing_query = new WP_Query( apply_filters( 'et_pricing_query_args', 'page_id=' . get_pageId( html_entity_decode( et_get_option( 'nimble_home_page_pricing' ) ) ) ) );
				if ( $pricing_query->have_posts() ) :
					while ( $pricing_query->have_posts() ) : $pricing_query->the_post();
						$pricing_page_url = ( $custom_pricing_url = get_post_meta( get_the_ID(), 'Homelink' , true ) ) && '' != $custom_pricing_url ? $custom_pricing_url : get_permalink();
					
						if ( has_excerpt() ) the_excerpt();
						else the_content( '' );

						echo '<a href="' . esc_url( $pricing_page_url ) . '" class="more-info">' . esc_html__( 'View Plans and Pricing', 'Nimble' ) . '</a>';
					endwhile;
				endif;
			?>
			</div> <!-- end .container -->
		</div> <!-- end #home-section-pricing -->
	<?php } ?>
<?php } else { ?>
	<div id="main-area">
		<div class="container">
			<div id="content-area" class="clearfix">
				<div id="left-area">
					<?php get_template_part( 'includes/entry', 'index' ); ?>
				</div> <!-- end #left-area -->
				<?php get_sidebar(); ?>
			</div> <!-- end #content-area -->
		</div> <!-- end .container -->
	</div> <!-- end #main-area -->
<?php } ?>
	
<?php get_footer(); ?>