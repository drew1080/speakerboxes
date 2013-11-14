<?php
function wpmp_product_files(){ 
    if(isset($_GET['post'])) $m = get_post_meta($_GET['post'],"wpmp_list_opts",true);
    if(isset($m)) @extract($m); 
    // print_r($m);
    $adpdir = WPMP_UPLOAD_DIR; //WP_PLUGIN_DIR.'/wpmarketplace/product-files/';     
?>
      
<div id="product_upload_box">  
    

<?php
// adjust values here
$id = "img2"; // this will be the name of form field. Image url(s) will be submitted in $_POST using this key. So if $id == �img1� then $_POST[�img1�] will have all the image urls
 
$svalue = ""; // this will be initial value of the above form field. Image urls.
 
$multiple = true; // allow multiple files upload
 
$width = null; // If you want to automatically resize all uploaded images then provide width here (in pixels)
 
$height = null; // If you want to automatically resize all uploaded images then provide height here (in pixels)
?>
 
 
<input type="hidden" name="wpmp_list[file1]" id="<?php echo $id; ?>" value="<?php echo $svalue; ?>" />
<div class="plupload-upload-uic hide-if-no-js <?php if ($multiple): ?>plupload-upload-uic-multiple<?php endif; ?>" id="<?php echo $id; ?>plupload-upload-ui">
    <input id="<?php echo $id; ?>plupload-browse-button" type="button" value="<?php esc_attr_e('Select Product File(s)'); ?>" class="button" />
    <span class="ajaxnonceplu" id="ajaxnonceplu<?php echo wp_create_nonce($id . 'pluploadan'); ?>"></span>
    <?php if ($width && $height): ?>
            <span class="plupload-resize"></span><span class="plupload-width" id="plupload-width<?php echo $width; ?>"></span>
            <span class="plupload-height" id="plupload-height<?php echo $height; ?>"></span>
    <?php endif; ?>
    <div class="filelist"></div>
</div>
<div class="plupload-thumbs <?php if ($multiple): ?>plupload-thumbs-multiple<?php endif; ?>" id="<?php echo $id; ?>plupload-thumbs">
</div>

<ul id="currentfiles">
<?php
        

 if(isset($file) && !is_array($file)&&$file!=''){
     $temp=$file;
        $file=array();
        $file[]=$temp;
 }   
 if(isset($file) && is_array($file)){
     $mmv=0;
        foreach($file as $sfile){
?>  
<li id='pro_<?php echo $mmv; ?>'>
<input type="hidden" value="<?php echo $sfile; ?>" name="wpmp_list[file][]">
[<a href='#' id='<?php echo $mmv; ?>' class="del_pro">delete</a>] <?php echo $sfile; ?>              
             <div style='clear:both'></div>
             </li>
            <?php
            ++$mmv;
        }
   
    }
?>
</ul><br clear="all" />
<script type="text/javascript">
jQuery(".del_pro").live("click",function(){
    if(confirm("are you sure")){
    jQuery("#pro_"+jQuery(this).attr("id")).remove();
    }
    return false;
});
</script>    
  

<div class="clear"></div>


        
 

      </div>
<?php    
} 



function wpmp_delete_product_file(){
    @unlink(WPMP_UPLOAD_DIR.$_POST['file']);
    die();
}

function wpmp_meta_box_demo($post){     
    
      @extract(get_post_meta($post->ID,"wpmp_list_opts",true));  
    ?>
    <div id="demo_box">
      
     <?php echo __("Demo Site URL:","wpmarketplace");?> <br /><input type="text" style="width: 100%" name="wpmp_list[demo_site]" value="<?php if(isset($demo_site)) echo $demo_site; ?>">
     <?php echo __("Demo Admin URL: ","wpmarketplace");?><br /><input type="text" style="width: 100%" name="wpmp_list[demo_admin]" value="<?php if(isset($demo_admin)) echo $demo_admin; ?>">
     <?php echo __("Username:","wpmarketplace");?> <br /><input type="text" style="width: 100%" name="wpmp_list[demo_username]" value="<?php if(isset($demo_username)) echo $demo_username; ?>">
     <?php echo __("Password:","wpmarketplace");?> <br /><input type="text" style="width: 100%" name="wpmp_list[demo_password]" value="<?php if(isset($demo_password)) echo $demo_password; ?>">
     
      </div>
     <?php
} 

function wpmp_digital_files($post){
       @extract(get_post_meta($post->ID,"wpmp_list_opts",true));
    ?>
    <div><input type="checkbox" id="digital_activate" name="wpmp_list[digital_activate]" value="1" <?php if(isset($digital_activate)) echo 'checked';?> > Activate</div>
    <div style="clear: both; margin: 5px;"></div>
    <script type="text/javascript">
    function activate_check(){
        if(jQuery('#digital_activate').attr("checked")){
            jQuery('#dpbox').slideDown();
             
        }else{
            jQuery('#dpbox').slideUp();
             
        }
    }
    jQuery('#digital_activate').click(function(){
         activate_check();
    });
    window.onload=activate_check;
    </script>
    <div id="dpbox"> 
    <table width="100%">
    <tr>
    <td width="50%"><?php wpmp_product_files(); ?></td>
    <td width="50%"><?php wpmp_meta_box_demo($post); ?></td>
    </tr>
    </table>
    <?php
    
       
       
       echo "<div class='clear'></div></div>";
}

function wpmp_meta_box_product_upload($meta_boxes){
    
    $meta_boxes['wpmarketplace-digital-files'] = array('title'=>'Digital Products','callback'=>'wpmp_digital_files','position'=>'normal','priority'=>'core');
    
    return $meta_boxes;
}

if(is_admin())  {
    
    add_action("wp_ajax_wpmp_delete_product","wpmp_delete_product");
    add_filter("wpmp_meta_box","wpmp_meta_box_product_upload");

}

 
