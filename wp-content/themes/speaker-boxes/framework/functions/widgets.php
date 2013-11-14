<?php
function siiimple_flickr_widget($args) {
	$settings = get_option("widget_flickrwidget");
	$themename = "Paragon";
	$id = $settings['id'];
	$number = $settings['number'];
	echo $args['before_widget'];
?>

<div id="flickr" class="list">
	
	<h1 class="title">Flickr</h1>
	
	<div class="wrap">
		
		<ul class="flickr">
		<li>
		<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo $number; ?>&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo $id; ?>">
		</script>
		</li>  
		</ul>      
		
	</div>
</div>

<?php
	echo $args['after_widget'];
}

function siiimple_flickr_widget_admin() {
	$settings = get_option("widget_flickrwidget");
	$themename = "Paragon";

	// check if anything's been sent
	if (isset($_POST['update_flickr'])) {
		$settings['id'] = strip_tags(stripslashes($_POST['flickr_id']));
		$settings['number'] = strip_tags(stripslashes($_POST['flickr_number']));
		
		update_option("widget_flickrwidget",$settings);
	}

	echo '<p>
			<label for="flickr_id">Flickr ID (<a href="http://www.idgettr.com" target="_blank">idGettr</a>):
			<input id="flickr_id" name="flickr_id" type="text" value="'.$settings['id'].'" /></label></p>';
	echo '<p>
			<label for="flickr_number">Number of photos:
			<input id="flickr_number" name="flickr_number" type="text" value="'.$settings['number'].'" /></label></p>';
	echo '<input type="hidden" id="update_flickr" name="update_flickr" value="1" />';

}
wp_register_sidebar_widget( 'flickr-widget', '$themename', 'Flickr', 'siiimple_flickr_widget', array('description' => 'Pulls in images from your Flickr account.'));
wp_register_widget_control('flickr-widget', 'siiimple_flickr_widget_admin', 400, 200);


//Recent Posts Widget

class Recentposts_thumbnail extends WP_Widget {

    function Recentposts_thumbnail() {
        parent::WP_Widget(false, $name = 'Paragon Recent Posts');
    }

    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        ?>
            <?php echo $before_widget; ?>
            
            <?php if ( $title ) echo $before_title . $title . $after_title;  else echo '<div class="widget-body clear">'; ?>

            <?php
                global $post;
                if (get_option('rpthumb_qty')) $rpthumb_qty = get_option('rpthumb_qty'); else $rpthumb_qty = 5;
                $q_args = array(
                    'numberposts' => $rpthumb_qty,
                );
                $rpthumb_posts = get_posts($q_args);
                foreach ( $rpthumb_posts as $post ) :
                    setup_postdata($post);
            ?>
            
            <?php $thumb = get_post_thumbnail_id(); ?>
            
            	<div class="recentposts">
				
				<ul class="rp">
				
				<?php if ($thumb) { ?>
				
				<li>
				
                	<a href="<?php the_permalink(); ?>">
                    	<?php if ( has_post_thumbnail() && !get_option('rpthumb_thumb') ) {
                        	the_post_thumbnail('mini-thumbnail');
                        	}
                    	?>                
                	</a>
                
                </li>
                                    
                <?php } else { ?>
                
                <li>
                
                	<a href="<?php the_permalink(); ?>">
                    
                		<img src="<?php bloginfo("template_url"); ?>/_images/post-img.png"></img>
                		
                		<h1 class="rp-title"<?php echo $offset; ?>><?php the_title(); ?></h1>
                		
                		<h4 class="rp-date"<?php echo $offset; unset($offset); ?>><?php the_time(__('M j, Y')) ?></h4>                
                	
                	</a>
                
                </li>
                
                <?php } ?><!-- end Conditional -->
                
                </ul>
                
                </div>
                
            <?php endforeach; ?>

            <?php echo $after_widget; ?>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        update_option('rpthumb_qty', $_POST['rpthumb_qty']);
        update_option('rpthumb_thumb', $_POST['rpthumb_thumb']);
        return $instance;
    }

    function form($instance) {
        $title = esc_attr($instance['title']);
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
            <p><label for="rpthumb_qty">Number of posts:  </label><input type="text" name="rpthumb_qty" id="rpthumb_qty" size="2" value="<?php echo get_option('rpthumb_qty'); ?>"/></p>
            <p><label for="rpthumb_thumb">Hide thumbnails:  </label><input type="checkbox" name="rpthumb_thumb" id="rpthumb_thumb" <?php echo (get_option('rpthumb_thumb'))? 'checked="checked"' : ''; ?>/></p>
        <?php
    }

}
add_action('widgets_init', create_function('', 'return register_widget("Recentposts_thumbnail");'));

?>