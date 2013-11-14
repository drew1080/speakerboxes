<?php
/**
 * Description of Slide_Js_Options is
 * to setup the options page for
 * our plugin
 *
 * @author Jeff Clark 1010 Collective
 */

include_once 'Slide_Js_Setup.php';

class Slide_Js_Options extends Slide_Js_Setup {
    
    var $slidejs_parent_title = "edit.php?post_type=slidejs-slideshow";
    var $slidejs_setting_title = "Options";
    var $slidejs_options_slug = "slidejs-options";
    
    
    public function __construct() {
        add_action('admin_menu', array($this, 'slidejs_create_options_page'));
    }
    
    
    
    /**
     * Setup the options page 
     */
    public function slidejs_create_options_page(){              
        add_submenu_page(
                $this->slidejs_parent_title, 
                'Slides JS Options', 
                $this->slidejs_setting_title, 
                'edit_posts', 
                $this->slidejs_options_slug, 
                array($this, 'slidejs_options_content')
                );
    }
    
    
    
    
    public function slidejs_options_content(){
        ?>
        
        <div class="settings-wrap">
            <h2>Slides JS Plus Options</h2>
        </div>

        <?php
    }

    
}

