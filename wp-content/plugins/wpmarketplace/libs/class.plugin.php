<?php
if(!class_exists('ahm_plugin')){
class ahm_plugin{
    protected $plugin_dir;
    protected $plugin_url;
    function ahm_plugin($plugin){               
             $this->plugin_dir = str_replace("libs","",dirname(__FILE__));
             if(function_exists('plugins_url'))
             $this->plugin_url = plugins_url().'/'.$plugin;
    }

    function load_styles(){
        global $current_screen,$post;
        $dir = is_admin()?'admin':'site';
        $cssdir = $this->plugin_dir.'css/'.$dir.'/';
        $cssurl = $this->plugin_url.'/css/'.$dir.'/';
        if($dir=="admin")
            if($current_screen->post_type=="wpmarketplace")
                wp_enqueue_style(uniqid(),$cssurl.'css.php');                
        if($dir=="site")
                wp_enqueue_style(uniqid(),$cssurl.'css.php');                
        
    }
    
    function load_scripts(){
        
        wp_enqueue_script('jquery');
        $dir = is_admin()?'admin':'site';
        $jsdir = $this->plugin_dir.'js/'.$dir.'/';
        $jsurl = $this->plugin_url.'/js/'.$dir.'/';
        $files = scandir($jsdir);
        foreach($files as $file){
            $tmp = explode(".",$file);
            if(!is_dir($file)&&end($tmp)=='js')
            wp_enqueue_script(uniqid(),$jsurl.$file);
        }
    }
    
    function load_modules(){       
        
        $mdir = $this->plugin_dir.'modules/';
        
        $files = scandir($mdir);
        foreach($files as $file){
            $tmp = explode(".",$file);
            if(!is_dir($file)&&end($tmp)=='php')
            include($mdir.$file);
        }
    }
    
    
    
}

}

?>
