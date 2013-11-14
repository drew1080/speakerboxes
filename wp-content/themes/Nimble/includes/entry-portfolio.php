<?php
	$i = 1;
	$portfolios_per_row = (int) apply_filters( 'et_portfolios_per_row', 3 );
	
	if ( have_posts() ) : while ( have_posts() ) : the_post();
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
		if ( function_exists('wp_pagenavi') ) { wp_pagenavi(); }
		else { get_template_part( 'includes/navigation', 'portfolio' ); }
	else:
		get_template_part( 'includes/no-results','portfolio' );
	endif;
?>