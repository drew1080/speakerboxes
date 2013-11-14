<?php

defined( 'ABSPATH' ) or die( '-1' );
?>
<div class="wrap">
	<?php screen_icon() ?>
	<h2>Color Manager</h2>
	<form method="post" action="options.php">
		<?php settings_fields( 'color_settings' ); ?>
		<?php settings_errors(); ?>
		<br class="clear">
		<table class="widefat" id="colors">
			<thead>
				<tr>
					<th><?php _e( 'Label' ) ?></th>
					<th><?php _e( 'CSS Selector' ) ?></th>
					<th><?php _e( 'Property' ) ?></th>
					<th><?php _e( 'Default' ) ?></th>
					<th><?php _e( 'Remove' ) ?></th>
				</tr>
			</thead>
			<tbody>
				<?php $i = 0;
				if( $options['colors'] ) : foreach( $options['colors'] as $option ) : ?>
				<tr>
					<td><?php echo $option['label'] ?><input type="hidden" name="colormanager[colors][<?php echo $i ?>][label]" value="<?php echo $option['label'] ?>" /></td>
					<td dir="ltr"><?php echo $option['selector'] ?><input type="hidden" name="colormanager[colors][<?php echo $i ?>][selector]" value="<?php echo $option['selector'] ?>" /></td>
					<td><?php echo $option['property'] ?><input type="hidden" name="colormanager[colors][<?php echo $i ?>][property]" value="<?php echo $option['property'] ?>" /></td>
					<td><?php echo $option['default'] ?><input type="hidden" name="colormanager[colors][<?php echo $i ?>][default]" value="<?php echo $option['default'] ?>" /><input type="hidden" name="colormanager[colors][<?php echo $i ?>][value]" value="<?php echo $option['value'] ?>" /></td>
					<td><a class="delete" href="#"><?php _e( 'Remove' ) ?></a></td>
				</tr>
				<?php $i++; endforeach; endif; ?>
			</tbody>
		</table>
		<div id="color-picker"></div>
		<p class="submit">
			<input type="button" class="button-secondary" value=" <?php _e( 'Add Option' ) ?> " id="add_color" />
			<input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>">
		</p>
	</form>
</div><!-- .wrap -->