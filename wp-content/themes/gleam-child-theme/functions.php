<?php
// CUSTOM CODE FOR HAS

define('child_template_directory', get_stylesheet_directory_uri() );

wp_enqueue_script('has', child_template_directory . '/js/ise.js', array('jquery'), '1.0', true);
