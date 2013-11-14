<?php global $current_user; 
    if($_GET['product']!='') 
        $product = get_post($_GET['product'],ARRAY_A); 
         
    if($product['post_author']!=$current_user->ID) {
        $product = array(); 
        if($_GET['product']!='')
        die("<script>location.href='".home_url('/members/add-product/')."';</script>");
    }
        
    $wpmp_list = get_post_meta($product['ID'],'wpmp_list_opts',true); 
    @extract($wpmp_list); 
    //print_r($wpmp_list);
?>
<?php
    wp_enqueue_script('plupload-all');
    // place js config array for plupload
    $plupload_init = array(
        'runtimes' => 'html5,silverlight,flash,html4',
        'browse_button' => 'plupload-browse-button', // will be adjusted per uploader
        'container' => 'plupload-upload-ui', // will be adjusted per uploader
        'drop_element' => 'drag-drop-area', // will be adjusted per uploader
        'file_data_name' => 'async-upload', // will be adjusted per uploader
        'multiple_queues' => true,
        'max_file_size' => '8388608b',
        'url' => admin_url('admin-ajax.php'),
        'flash_swf_url' => includes_url('js/plupload/plupload.flash.swf'),
        'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
        'filters' => array(array('title' => __('Allowed Files'), 'extensions' => '*')),
        'multipart' => true,
        'urlstream_upload' => true,
        'multi_selection' => false, // will be added per uploader
         // additional post data to send to our ajax hook
        'multipart_params' => array(
            '_ajax_nonce' => "", // will be added per uploader
            'action' => 'plupload_action', // the ajax action name
            'imgid' => 0 // will be added per uploader
        )
    );
?>
<script type="text/javascript">
    var base_plupload_config=<?php echo json_encode($plupload_init); ?>;
    var pluginurl = "<?php echo plugins_url("wpmarketplace/"); ?>";
    var uploadimgurl = "<?php echo WPMP_IMAGE_URL; ?>";
</script>
    
<style type="text/css">
    .delet_vdiv{
        float: right; margin: 3px;cursor: pointer;
    }
    .delet_voption{
        cursor: pointer;
    }
    
    #wpmp-wrapper ul li{
        list-style: none;
    }
    #wpmp-wrapper .span4{
    margin-left:0px !important;
}
.div_title{
    font-size: 18px;
    
    border-bottom: 1px solid #ccc;
    margin-bottom: 10px;
    padding-bottom: 5px;
    margin-right: 10px;
}
    </style> 
         
<div id="wpmp-wrapper" class="wp-marketplace">                       
 <form method="post" id="validate_form" name="contact_form" action="">
<input type="hidden" name="id" value="<?php echo $product['ID']; ?>">
<input type="hidden" name="__product_wpmp" value="<?php echo wp_create_nonce('wpmp-product');; ?>">
<input type="hidden" name="post_type" value="wpmarketplace">
<div class="my-profile row-fluid">
<h3><?php echo __("Add/Edit Product","wpmarketplace");?></h3>
<hr size="1" noshade="noshade" />

<div  class="span8">
<div id="form" class="form product-form">
<ul>
    <li><div class=""><div class="div_title"><?php echo __("Title:","wpmarketplace");?></div> <input type="text" class="span"  value="<?php echo $product['post_title']; ?>" name="product[post_title]" id="title"></div> </li>
    
    <li class="full"><div class=""><div class="div_title"><?php echo __("Description:","wpmarketplace");?></div>
    
    <?php the_editor($product['post_content'],'product[post_content]'); ?>
    </div><br />
    </li> 
   
   <li class="full"><div class="well"><div class="div_title"><?php echo __("Excerpt:","wpmarketplace");?></div><textarea class="span" cols="40" rows="3" name="product[post_excerpt]" id="exc"><?php echo $product['post_excerpt']; ?></textarea></div></li>                                                
   
    <li>
    <div class="well"><input type="checkbox" id="digital_activate" name="wpmp_list[digital_activate]" value="1" <?php if($digital_activate) echo 'checked';?> > <?php echo __("Digital Product","wpmarketplace");?> 
     
    <div style="clear: both; margin: 5px;"></div>
    <script type="text/javascript">
    function activate_check(){
        if(jQuery('#digital_activate').attr("checked")){
            jQuery('#dpro').slideDown();
           
        }else{
            jQuery('#dpro').slideUp();   
          
        }
    }
    jQuery('#digital_activate').click(function(){
         activate_check();
    });
    window.onload=activate_check;
    </script>
    <div class="row-fluid" id="dpro">
    
    <div id="product_upload_box"  class="span6">  
<div id="currentfiles">

<?php
//print_r($file);
if(is_array($file) && !empty($file[0])){
    $mm=0;
    foreach($file as $fl){
        if(!empty($fl)){
$value = $fl;
$filename = end( explode('/',$value)  );
$filename = preg_replace("/wpmp\-([0-9]+)\-/","",$filename);
if(strlen($filename)>20)
$filename = substr($filename,0,10).'...'.substr($filename,strlen($filename)-13);
?>
<div id="di_<?php echo $mm;?>" class="cfile">
<input id='in_<?php echo $mm;?>' type="hidden" value="<?php echo $value; ?>" name="wpmp_list[file][]">
<nobr>
<b><i class="icon-remove" rel="del" id="del_<?php echo $mm;?>" ></i>&nbsp;<?php echo  $filename; ?></b>
</nobr>
<div style="clear: both;"></div>
</div>

<script type="text/javascript">


 jQuery('#del_<?php echo $mm;?>').click(function(){

     if(jQuery(this).attr('rel')=='del'){
            jQuery('#di_<?php echo $mm;?>').removeClass('cfile').addClass('dfile');
            jQuery('#in_<?php echo $mm;?>').attr('name','del[]');
            jQuery(this).attr('rel','undo').attr('title','Undo Delete');
            jQuery(this).removeClass('icon-remove');
            jQuery(this).addClass('icon-refresh');        
        } else if(jQuery(this).attr('rel')=='undo'){
            jQuery('#di_<?php echo $mm;?>').removeClass('dfile').addClass('cfile');
            jQuery('#in_<?php echo $mm;?>').attr('name','wpmp_list[file][]');
            jQuery(this).attr('rel','del').attr('title','Redo Delete');
            jQuery(this).addClass('icon-remove');
            jQuery(this).removeClass('icon-refresh');
        }
});



</script>

<?php
$mm++;
        }
}
}

?>


<?php if($file):  ?>

<?php endif; ?>

<?php
// adjust values here
$id = "img2"; // this will be the name of form field. Image url(s) will be submitted in $_POST using this key. So if $id == �img1� then $_POST[�img1�] will have all the image urls
 
$svalue = ""; // this will be initial value of the above form field. Image urls.
 
$multiple = true; // allow multiple files upload
 
$width = null; // If you want to automatically resize all uploaded images then provide width here (in pixels)
 
$height = null; // If you want to automatically resize all uploaded images then provide height here (in pixels)
?>
  
<input type="hidden" name="wpmp_list[file][]" id="<?php echo $id; ?>" value="<?php echo $svalue; ?>" />
<div class="plupload-upload-uic hide-if-no-js <?php if ($multiple): ?>plupload-upload-uic-multiple<?php endif; ?>" id="<?php echo $id; ?>plupload-upload-ui">
    <input id="<?php echo $id; ?>plupload-browse-button" type="button" value="<?php esc_attr_e('Browse File'); ?>" class="btn btn-info" />
    <span class="ajaxnonceplu" id="ajaxnonceplu<?php echo wp_create_nonce($id . 'pluploadan'); ?>"></span>
    <?php if ($width && $height): ?>
            <span class="plupload-resize"></span><span class="plupload-width" id="plupload-width<?php echo $width; ?>"></span>
            <span class="plupload-height" id="plupload-height<?php echo $height; ?>"></span>
    <?php endif; ?>
    <div class="filelist"></div>
</div>
<div class="plupload-thumbs <?php if ($multiple): ?>plupload-thumbs-multiple<?php endif; ?>" id="<?php echo $id; ?>plupload-thumbs">
</div>
<div class="clear"></div>

</div>
<br clear="all" />

<div class="clear"></div>
      </div>
    
    <div id="demo_box" class="span6">
    
     <div>
     <div><label ><?php echo __("Frontend Demo URL:","wpmarketplace"); ?></label><input type="text" name="wpmp_list[demo_site]" value="<?php echo $demo_site; ?>" /></div>
     <div><label ><?php echo __("Admin Demo URL:","wpmarketplace"); ?></label><input type="text" name="wpmp_list[demo_admin]" value="<?php echo $demo_admin; ?>" /></div>    
     <div><label ><?php echo __("Username:","wpmarketplace"); ?></label><input type="text" name="wpmp_list[demo_username]"  value="<?php echo $demo_username; ?>" /></div>
     <div><label ><?php echo __("Password:","wpmarketplace"); ?></label><input type="text" name="wpmp_list[demo_password]" value="<?php echo $demo_password; ?>" /></div>
     <div><label ><?php echo __("Other Demo Info:","wpmarketplace"); ?></label><textarea name="wpmp_list[demo_info]"><?php echo $demo_info; ?></textarea></div>
     </div>
    
    </div>
    
    </div>
    </div>
    </li>
      
      
      <li>
      
    <div class="well">
    <div class="div_title"><?php echo __("Prices","wpmarketplace");?></div>
    
    <h3 id="variation_heading"><?php if($price_variation) echo __("Variation Options","wpmarketplace");else echo __("Pricing","wpmarketplace");?></h3>
         <table   width="95%" style="margin: 10px;" >
     <tr id="base_price" style="<?php //if($price_variation) echo "display: none;"; else echo "";?>"><td><?php echo __("Price:","wpmarketplace");?> <input type="text" size="16" id="price_label" class="input-small" name="wpmp_list[base_price]"  value="<?php echo $base_price;?>"></td>
     <td width="250px"><?php echo __("Sales Price:","wpmarketplace");?> <input type="text" value="" name="wpmp_list[sales_price]" id="price_labe" value="<?php echo $sales_price;?>" size="16" class="input-small" ></td></tr>
     <tr><td><input type="checkbox" <?php if($price_variation) echo "checked='checked'"; else echo "";?> name="wpmp_list[price_variation]" id="price_variation" name="price_variation"> <?php echo __("Variation","wpmarketplace");?></td></tr>       
    </table>
    
    <div id="price_dis_table" style="<?php if($price_variation) echo ""; else echo "display: none;";?>">
        <div id="vdivs">
        <?php 
    //echo "<pre>"; print_r($variation); echo "</pre>";
    if($variation){
        //show variations
        
        foreach($variation as $key=>$vname){
            ?>
            <div id="variation_div_<?php echo $key;?>" class="well" width="100%" style="margin: 10px; ">
         <i class="delet_vdiv icon-remove" rel="variation_div_<?php echo $key;?>" title="delete this variation" ></i>
         <table border="0" id="voption_table_<?php echo $key;?>">
         <tr>
             <tr>
             <td><label>Multiple Select:</label></td>
             <td><input type="checkbox" name="wpmp_list[variation][<?php echo $key;?>][multiple]" placeholder="Multiple Select" <?php if(isset($vname['multiple'])) echo "checked='checked'"; ?> ></td>
            </tr> 
             <td colspan="3"><input type="text" name="wpmp_list[variation][<?php echo $key;?>][vname]" id="" placeholder="variation name" value="<?php echo $vname['vname'];?>"></td></tr>
         <?php
    if($vname){
        foreach($vname as $optionkey=>$optionval){
            if($optionkey!="vname" && $optionkey !="multiple"){
?>
         <tr id="voption<?php echo $optionkey;?>"><td><input type="text" name="wpmp_list[variation][<?php echo $key;?>][<?php echo $optionkey;?>][option_name]"  placeholder="option name" value="<?php echo $optionval['option_name'];?>"></td><td><input type="text" name="wpmp_list[variation][<?php echo $key;?>][<?php echo $optionkey;?>][option_price]" id="" placeholder="price" value="<?php echo $optionval['option_price'];?>"></td><td><i class="delet_voption icon-remove" rel="voption<?php echo $optionkey;?>" title="delete this option" ></i></td></tr>
         <?php
            }
        }
    }
?>
         </table>
         <div style="clear: both;"></div>
         <input type="button" class="btn btn-info btn-mini add_voption" rel="<?php echo $key;?>" value="Add Option">
         </div>
            <?php
        }
    }else{
?>
            <div id="variation_div1" class="well" width="100%" style="margin: 10px; ">
         <i class="delet_vdiv icon-remove" rel="variation_div1" title="delete this variation" ></i>
         <table border="0" id="voption_table_1">
         <tr>
             <td><label>Multiple Select:</label></td>
             <td><input type="checkbox" name="wpmp_list[variation][1][multiple]" placeholder="Multiple Select"></td>
         </tr>
         <tr><td colspan="3"><input type="text" name="wpmp_list[variation][1][vname]" id="" placeholder="variation name"></td></tr>
         <tr id="voption1"><td><input type="text" name="wpmp_list[variation][1][1][option_name]"  placeholder="option name"></td><td><input type="text" name="wpmp_list[variation][1][1][option_price]" id="" placeholder="price"></td><td><i class="delet_voption icon-remove" rel="voption1" title="delete this option"></i></td></tr>
         </table>
         <div style="clear: both;"></div>
         <input type="button" class="btn btn-info btn-mini add_voption" rel="1" value="Add Option">
         
         
         </div>
         <?php
    }
?>
         </div>
         <input type="button" class="btn btn-success btn-large " id="add_variation" value="Add Variation">
     </div>
    <script type="text/javascript">
    jQuery('#price_variation').click(function(){
        if(jQuery('#price_variation').attr("checked")){
            //jQuery('#base_price').hide();
            
            jQuery('#variation_heading').text("Variation Options"); 
            jQuery('#price_dis_table').show();
            
        }else{
              //jQuery('#base_price').show();
              jQuery('#variation_heading').text("Pricing"); 
              jQuery('#price_dis_table').hide()  ;
        }
    });
    jQuery('#add_variation').live("click",function (){
        var tm=new Date().getTime();
        jQuery('#vdivs').append('<div id="variation_div_'+tm+'" class="wpmp-variation well" width="100%" style="margin: 10px; "><i class="delet_vdiv icon-remove" rel="variation_div_'+tm+'" title="delete this variation"></i><table border="0" id="voption_table_'+tm+'"><tr><td><label>Multiple Select:</label></td><td><input type="checkbox" name="wpmp_list[variation]['+tm+'][multiple]" placeholder="Multiple Select"></td></tr><tr><td colspan="3"><input type="text" name="wpmp_list[variation]['+tm+'][vname]" id="" placeholder="variation name "></td></tr><tr id="voption_'+tm+'"><td><input type="text" name="wpmp_list[variation]['+tm+']['+tm+'][option_name]" id="" placeholder="option name"></td><td><input type="text" name="wpmp_list[variation]['+tm+']['+tm+'][option_price]" id="" placeholder="price"></td><td><i class="delet_voption icon-remove" rel="voption_'+tm+'" title="delete this option" ></i></td></tr></table><div style="clear: both;"></div><input type="button" class="btn btn-info btn-mini  add_voption" rel="'+tm+'" value="Add Option"></div>');
    });
    jQuery('.delet_vdiv').live("click",function(){
        if(confirm("Are you sure to remove"))
            jQuery('#'+jQuery(this).attr("rel")).remove();
    });
    jQuery('.add_voption').live("click",function (){
        var tm=new Date().getTime();
        jQuery('#voption_table_'+jQuery(this).attr("rel")).append('<tr id="voption_'+tm+'"><td><input type="text" name="wpmp_list[variation]['+jQuery(this).attr("rel")+']['+tm+'][option_name]"  placeholder="option name"></td><td><input type="text" name="wpmp_list[variation]['+jQuery(this).attr("rel")+']['+tm+'][option_price]" id="" placeholder="price"></td><td><i class="delet_voption icon-remove" rel="voption_'+tm+'" title="delete this option" ></i></td></tr>');
    });
    
    jQuery('.delet_voption').live("click",function(){
        if(confirm("Are you sure to remove"))
            jQuery('#'+jQuery(this).attr("rel")).remove();
    });
    
   
    </script>
   <!-- </div>-->
        </div>
    </li>
      
      
    </ul>
    
    </div>
    <div class="clear"></div>
     

</div>

<div class="span4">
<div class="form product-form ap-sidebar" >
  <ul>
 <li>
 <div class="well"><div class="div_title">Product Types</div>
    
    <?php 
    $term_list = wp_get_post_terms($product['ID'], 'ptype', array("fields" => "all"));        
    
    function wpmp_product_types_checkboxed_tree($parent = 0, $selected = array()){        
        $categories = get_terms( 'ptype' , array('hide_empty'=>0,'parent'=>$parent));           
        foreach($categories as $category){
            if($selected){
            foreach($selected as $ptype){
                if($ptype->term_id==$category->term_id){$checked="checked='checked'";break;}else $checked="";
            }
            }
            echo '<li><input type="checkbox" '.$checked.' name="product_type[]" value="'.$category->term_id.'"> '.$category->name.' ';
            echo "<ul>";
            wpmp_product_types_checkboxed_tree($category->term_id, $selected);    
            echo "</ul>";
            echo "</li>";
        }        
    }
    
    echo "<ul class='ptypes'>";
    wpmp_product_types_checkboxed_tree(0, $term_list);
    echo "</ul>";
    ?>
    </div>
 </li>
 
     <li><div class="well">
     <div class="form-inline">
     <div class="div_title span6"><?php echo __("Featured Image","wpmarketplace");?></div>  
     <div class="span4">
     <?php
     // adjust values here
$id = "fimg"; // this will be the name of form field. Image url(s) will be submitted in $_POST using this key. So if $id == �img1� then $_POST[�img1�] will have all the image urls
 
$svalue = ""; // this will be initial value of the above form field. Image urls.
 
$multiple = false; // allow multiple files upload
 
$width = null; // If you want to automatically resize all uploaded images then provide width here (in pixels)
 
$height = null; // If you want to automatically resize all uploaded images then provide height here (in pixels)
?>      
<input type="hidden" name="wpmp_list[fimage]" id="<?php echo $id; ?>" value="<?php echo $svalue; ?>" />
<div class="plupload-upload-uic hide-if-no-js <?php if ($multiple): ?>plupload-upload-uic-multiple<?php endif; ?>" id="<?php echo $id; ?>plupload-upload-ui">
    <input id="<?php echo $id; ?>plupload-browse-button" type="button" value="<?php esc_attr_e('Browse'); ?>" class="btn btn-info" /><br>
    <br>
    <span class="ajaxnonceplu" id="ajaxnonceplu<?php echo wp_create_nonce($id . 'pluploadan'); ?>"></span>
    <?php if ($width && $height): ?>
            <span class="plupload-resize"></span><span class="plupload-width" id="plupload-width<?php echo $width; ?>"></span>
            <span class="plupload-height" id="plupload-height<?php echo $height; ?>"></span>
    <?php endif; ?>
    <div class="filelist"></div>
</div>
<div class="plupload-thumbs <?php if ($multiple): ?>plupload-thumbs-multiple<?php endif; ?>" id="<?php echo $id; ?>plupload-thumbs">
</div>
    </div> 
    </div>  
     <div id="mpvq"></div>     
     <div id="mpvc">
     
<div class="clear"></div>
<div id="mi" style="position:relative">
     <?php if(has_post_thumbnail($product['ID'])) { ?>
     
     <?php  echo get_the_post_thumbnail($product['ID'], array(220,200) ); ?>     
      <br/>
     <img style='z-index:9999;cursor:pointer;padding:5px;background: #111;border-radius:4px;top:0px;position:absolute' id='mi_del' rel="mi" src='<?php echo plugins_url(); ?>/wpmarketplace/images/remove.png' alt="<?php __('Remove Featured Image','wpmarketplace'); ?>" title="<?php __('Remove Featured Image','wpmarketplace'); ?>" align=left />     
     <?php } ?>
     </div>
     </div>
     </div></li>
     <li><div class="well">
     <div class="form-inline">
     <div class="div_title span7"><?php echo __("Additional Images","wpmarketplace");?></div>
      <div class="span4">
    <?php
         //$adpdir = WP_PLUGIN_DIR.'/wpmarketplace/previews/';
         $adpdir = WPMP_IMAGE_DIR;
          
        
// adjust values here
$id = "img1"; // this will be the name of form field. Image url(s) will be submitted in $_POST using this key. So if $id == �img1� then $_POST[�img1�] will have all the image urls
 
$svalue = ""; // this will be initial value of the above form field. Image urls.
 
$multiple = true; // allow multiple files upload
 
$width = null; // If you want to automatically resize all uploaded images then provide width here (in pixels)
 
$height = null; // If you want to automatically resize all uploaded images then provide height here (in pixels)
?>
 
 
<input type="hidden" name="wpmp_list[images1]" id="<?php echo $id; ?>" value="<?php echo $svalue; ?>" />

<div class="plupload-upload-uic hide-if-no-js <?php if ($multiple): ?>plupload-upload-uic-multiple<?php endif; ?>" id="<?php echo $id; ?>plupload-upload-ui">
    <input id="<?php echo $id; ?>plupload-browse-button" type="button" value="<?php esc_attr_e('Browse'); ?>" class="btn btn-info" />
    <span class="ajaxnonceplu" id="ajaxnonceplu<?php echo wp_create_nonce($id . 'pluploadan'); ?>"></span>
    <?php if ($width && $height): ?>
            <span class="plupload-resize"></span><span class="plupload-width" id="plupload-width<?php echo $width; ?>"></span>
            <span class="plupload-height" id="plupload-height<?php echo $height; ?>"></span>
    <?php endif; ?>
    <div class="filelist"></div>
</div>
<div class="plupload-thumbs <?php if ($multiple): ?>plupload-thumbs-multiple<?php endif; ?>" id="<?php echo $id; ?>plupload-thumbs">
</div>
</div>
</div>
     <div id="apvq"></div>
      
     <div id="apvc">
    
<div class="clear"></div>  
<ul id="adpcon">
<?php
    
    if(is_array($images)){
        foreach($images as $mpv){
            //if(file_exists($adpdir.$mpv)){
            ?>
             <li id='<?php echo ++$mmv; ?>' >
             <div class="adp" style="float:left;padding:5px;">
             <input type='hidden'  id='in_<?php echo $mmv; ?>' name='wpmp_list[images][]' value='<?php echo $mpv; ?>' />
             <i style='position:absolute;z-index:9999;cursor:pointer;' id='del_<?php echo $mmv; ?>' rel="<?php echo $mmv; ?>"  class="del_adp icon-remove" align=left ></i>
             <img src='<?php echo plugins_url(); ?>/wpmarketplace/libs/timthumb.php?w=50&h=50&zc=1&src=<?php echo WPMP_IMAGE_URL.$mpv; ?>'/>
             </div>
             </li>
            <?php
        }
    //}
    }
?>
</ul><br clear="all" />
<div class="clear"></div> 
<script type="text/javascript">
      
      jQuery(document).ready(function() {
       
        
        jQuery('.del_adp').live("click",function(){
                                if(confirm('Are you sure?')){
                                    //jQuery.post(ajaxurl,{action:'wpmp_delete_preview',file:jQuery('#in_'+jQuery(this).attr('rel')).val()})
                                    jQuery('#'+jQuery(this).attr('rel')).fadeOut().remove();
                                }
                                
                            });
   
      });
  
      </script>
    
    </div>      
          
     </div></li>
     <li>
     <div class="well">
     <div class="div_title"><?php echo __("Dimension","wpmarketplace");?></div>
     <table width="100%" border="0">
     <tr><td><?php echo __("Weight","wpmarketplace");?></td><td><input type="text" name="wpmp_list[weight]" value="<?php echo $weight;?>" class="span"></td></tr>
     <tr><td><?php echo __("Width","wpmarketplace");?></td><td><input type="text" name="wpmp_list[pwidth]" value="<?php echo $pwidth;?>" class="span"></td></tr>
     <tr><td><?php echo __("Height","wpmarketplace");?></td><td><input type="text" name="wpmp_list[pheight]" value="<?php echo $pheight;?>" class="span"></td></tr>
     </table>
     </div>
     </li> 
    <li>
    <div class="well">
    <div class="div_title"><?php echo __("Coupon Discount","wpmarketplace");?></div>
    <!--<div class="postbox" style="width: 48%;float: left;">-->
    
     <table id="coupon_table"  width="100%" class="table">
     <tr><th align="left"><?php echo __("Coupon Code","wpmarketplace");?></th><th align="left"><?php echo __("Discount(%)","wpmarketplace");?></th></tr>
     <?php
    if(count($coupon_code)>0){
        foreach($coupon_code as $coupon_key=>$coupon_val){
        ?>
     <tr><td> <input type="text" class="span" size="8"  name="wpmp_list[coupon_code][<?php echo $coupon_key?>]"  value="<?php echo $coupon_code[$coupon_key];?>"></td><td><input type="text"class="span" size="8" name="wpmp_list[coupon_discount][<?php echo $coupon_key?>]"  value="<?php echo $coupon_discount[$coupon_key];?>"></td></tr> 
          <?php
        }
    }
?>    
     </table>
     
      <table class="table"  width="100%">
      
     <tr><td><input placeholder="Code" type="text" size="16" id="coupon_code" class="span"  value=""></td><td><input placeholder="Discount" type="text" class="span" size="8" id="coupon_discount"  value=""></td>
     <td><input type="button" class="btn btn-info" size="8" id="add_coupon" value="Add"></td></tr>         
    </table>
    <script type="text/javascript">
     
    var cdtm=new Date().getTime();
    jQuery('#add_coupon').live("click",function (){
        var coupon_code=jQuery('#coupon_code').val();
        var coupon_discount= jQuery('#coupon_discount').val();
        jQuery('#coupon_table').append('<tr><td> <input class="span" size="8" type="text" name="wpmp_list[coupon_code]['+cdtm+']" value="'+coupon_code+'"></td><td><input class="span" type="text" size="8" name="wpmp_list[coupon_discount]['+cdtm+']" value="'+coupon_discount+'"></td></tr>');
        jQuery('#coupon_code').val("");
        jQuery('#coupon_discount').val("");
    });
    </script>
        <!--</div>-->
    
    </div>
    </li> 
    <li>
    <div class="well">
    <div class="div_title"><?php echo __("Role Based Discount","wpmarketplace");?></div>
    <!--<div class="postbox" style="width: 48%;float: right;">-->
    
     <table width="100%" style="margin: 10px;">
     <tr><th align="left"><?php echo __("Role","wpmarketplace");?></th><th align="left"><?php echo __("Discount (%)","wpmarketplace");?></th></tr>
     <tr><td><?php echo __("Guest (guest)","wpmarketplace");?> </td><td><input class="span" type="text" size="8" name="wpmp_list[discount][guest]" value="<?php echo $discount['guest']; ?>"></td></tr>     
         <?php
    global $wp_roles;
    $roles = array_reverse($wp_roles->role_names);
    foreach( $roles as $role => $name ) { 
    
    
    
    if(  $currentAccess ) $sel = (in_array($role,$currentAccess))?'checked':'';
    
    
    
    ?>
    <tr><td><?php echo $name; ?> (<?php echo $role; ?>) </td><td><input class="span" type="text" size="8" name="wpmp_list[discount][<?php echo $role; ?>]" value="<?php echo $discount[$role]; ?>"></td></tr>     
    
    <?php } ?>     
    </table>
    <!--</div>-->
    </div>
    </li>
    <li><br>
    
    <div style="text-align: center;">
    <input type="submit" value="Save Changes" class="btn btn-primary btn-large">
    </div>
    </li>  
  </ul>
  <div class="clear"></div>                                  
    
</div>
</div>
</div>

</div>
</form>
</div>
<script language="JavaScript">
     <!--
       var ftrc = 0;
       jQuery('#addftr').click(function(){           
           ftrc++;
          jQuery('#ftrtbl').append('<tr id="mftr'+ftrc+'"><td valign="top"><input style="width: 97%;" type="text" name="wpmp_list[feature_title][]" size="30"><br/><br/><a href="#" onclick="jQuery(\'#mftr'+ftrc+'\').fadeOut();return false;">Delete this feature</a><br/> </td><td><textarea style="width: 100%;" cols="60" rows="4" name="wpmp_list[feature_description][]"></textarea></td><td valign="top" style="padding-left: 10px"><input id="imgj'+ftrc+'" type="text" style="width: 99%;" name="wpmp_list[feature_image][]"><br/><a href="#" onclick="return upload(\'imgj'+ftrc+'\');" >Upload</a></td></tr>');
          return false;
       });
       
       
     //-->
     </script>

<script type="text/javascript">
      
      jQuery(document).ready(function() {
       
      jQuery('.delp').click(function(){
                                if(confirm('Are you sure?')){
                                    jQuery.post('<?php echo home_url('/');?>',{task:'wpmp_delete_product_file',file:jQuery('#'+jQuery(this).attr('rel')).val()})
                                    jQuery('#c_'+jQuery(this).attr('rel')).fadeOut().remove();
                                }
                                
                            });
   
     
      
      jQuery('#mi_del').live("click",function(){
          
                                if(confirm('Are you sure?')){
                                    jQuery.post('<?php echo home_url('/'); ?>',{task:'wpmp_delete_main_image',file:'<?php echo $product['ID']; ?>'})
                                    jQuery('#mi').html("");
                                }
                                
           
                    });
       }); 
      </script>   
      
      