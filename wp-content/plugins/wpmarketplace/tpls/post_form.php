<?php
    $m = get_post_meta($_GET['postid'],"wpmp_list_opts",true);
    @extract($m); 
      
?>
    <!--<link href="<?php //echo plugins_url('/wpmarketplace/uploadify/uploadify.css');?>" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="<?php //echo plugins_url('/wpmarketplace/uploadify/jquery-1.4.2.min.js');?>"></script>
    <script type="text/javascript" src="<?php //echo plugins_url('/wpmarketplace/uploadify/swfobject.js');?>"></script>
    <script type="text/javascript" src="<?php //echo plugins_url('/wpmarketplace/uploadify/jquery.uploadify.v2.1.4.min.js');?>"></script>-->
    
    <script type="text/javascript">
    var filenames="";
    </script>
<style>
.user-post{
    border: 1px solid #cccccc;
}
</style>
<style type="">
#pfUploader {background: transparent url('<?php echo plugins_url(); ?>/wpmarketplace/images/browse.png') left top no-repeat; }
#pfUploader:hover {background-position: left bottom; }
.highlight{
    border:1px solid #F79D2B;
    background:#FDE8CE;
    width: 40px;
    height: 40px;
    float:left;padding:5px;
}
.pf{
    float:left;padding:5px;
}
.dfile{
    padding:5px 10px;
    border:1px solid #FFCCBF;
    margin-bottom: 3px;
}
.cfile{
    padding:5px 10px;
    border:1px solid #ccc;
    margin-bottom: 3px;
}
.dfile img,.cfile img{
    cursor: pointer;
}

#apvUploader {background: transparent url('<?php echo plugins_url(); ?>/wpmarketplace/images/browse.png') left top no-repeat; }
#apvUploader:hover {background-position: left bottom; }
.highlight{
    border:1px solid #F79D2B;
    background:#FDE8CE;
    width: 40px;
    height: 40px;
    float:left;padding:5px;
}
.adp{
    float:left;padding:5px;
}
</style>
<div class="wrap">
<form action="" method="post" name="wppu" id="wppu" enctype="multipart/form-data" >
<input type="hidden" name="action" value="wpmp_save_post"> 
<input type="hidden" name="task" value="<?php echo $_REQUEST['task'];?>">
<input type="hidden" name="postid" id="postid" value="<?php if($thispost->ID) echo $thispost->ID; ?>">
 

<ul class="formstyle"> 
  
   <li> 
         <input placeholder="Enter title here" type="text" value="<?php if(!empty($thispost->post_title))echo $thispost->post_title;?>" name="user_post_title" id="user_post_title" >
      </li>
   <li> 
         <div style="clear: both;"></div>
         <!--<textarea name="user_post_desc" id="user_post_desc" style="width: 532px; height: 137px;"><?php if(!empty($thispost->post_content))echo $thispost->post_content;?></textarea>-->
         <?php the_editor($thispost->post_content,'user_post_desc'); ?>
      </li>


          
       

     
     <?php //div for excerpt?> 
     <?php //if(get_option('_wpup_excerpt')=="excerpt"){?>
     <li>
      
     <div class="wrap">
        <div style="margin-top: 10px;"></div> 
        <div id="poststuff" >
            <div class="postbox " id="quick-notice-options">
            
                <div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Excerpt :</span></h3>
                 <div class="inside">
                        <div id="postcustomstuff">
                <div id="custom_box">
                
               <textarea name="user_post_excerpt" id="user_post_excerpt"  cols="8" rows="2"><?php if(!empty($user_post_excerpt[0]))echo $user_post_excerpt[0];?></textarea>
                </div>
                </div>
                </div>
                
            </div>  
        </div>
    </div>
     </li>
        <?php //} ?>
        
        
        <li>
        
        
             <div class="wrap">
        <div style="margin-top: 10px;"></div> 
        <div id="poststuff" >
            <div class="postbox " id="quick-notice-options">
            
                <div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Product Type :</span></h3>
                 <div class="inside">
                        <div id="postcustomstuff">
                <div id="custom_box">
        
<?php //echo $thispost->ID;if($thispost->ID){$terms=wp_get_post_terms($thispost->ID,"ptype");print_r($terms);}?>
         <select id="cat" class="postform" name="cat">
         <?php
           global $wpdb; 
           
           $categories = get_terms('ptype', 'hide_empty=0');
          if(count($categories)>0){
              foreach($categories as $categ){
                  echo '<option value="'.$categ->name.'">'.$categ->name.'</option>';
              }
          } 
?>
         </select>
         </div>
                </div>
                </div>
                
            </div>  
        </div>
    </div>
     </li>
        
        
             <li>
      
     <div class="wrap">
        <div style="margin-top: 10px;"></div> 
        <div id="poststuff" >
            <div class="postbox " id="quick-notice-options">
            
                <div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Author :</span></h3>
                 <div class="inside">
                        <div id="postcustomstuff">
                <div id="custom_box">
                
<?php if($author)wp_dropdown_users(array('selected' => $author,'name' => 'wpmp_list[author]'));else wp_dropdown_users(array('name' => 'wpmp_list[author]')); ?>
                </div>
                </div>
                </div>
                
            </div>  
        </div>
    </div>
     </li>
     
     <li>
      
     <div class="wrap">
        <div style="margin-top: 10px;"></div> 
        <div id="poststuff" >
            <div class="postbox " id="quick-notice-options">
            
                <div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Pricing & Discounts :</span></h3>
                 <div class="inside">
                        <div id="postcustomstuff">
                <div id="custom_box">
                
<div class="postbox" style="width: 48%;float: right;">
    <h3>Role Based Discount</h3>
     <table style="width:100%; " style="margin: 10px;">
     <tr><th align="left">Role</th><th align="left" style="padding-left: 15px;">Discount (%)</th></tr>
     <tr><td >Guest (guest) </td><td ><input type="text" style="width: 50px" size="8" name="wpmp_list[discount][guest]" value="<?php echo $discount['guest']; ?>"></td></tr>     
         <?php
    global $wp_roles;
    $roles = array_reverse($wp_roles->role_names);
    foreach( $roles as $role => $name ) { 
    
    
    
    if(  $currentAccess ) $sel = (in_array($role,$currentAccess))?'checked':'';
    
    
    
    ?>
    <tr><td width="150px"><?php echo $name; ?> (<?php echo $role; ?>) </td><td width="150px"><input style="width: 50px" type="text" size="8" name="wpmp_list[discount][<?php echo $role; ?>]" value="<?php echo $discount[$role]; ?>"></td></tr>     
    
    <?php } ?>     
    </table>
    </div>
    <div class="postbox" style="width: 48%;float: left;">
    <h3>License Based Pricing</h3>
     <table style="width:100%; " style="margin: 10px;">
     <tr><th align="left">License Type</th><th align="left" style="padding-left: 15px;">Price($)</th></tr>
     <tr><td width="150px">Single User/Domain </td><td width="150px"><input style="width: 50px" type="text" size="8" name="wpmp_list[price][single_user]" value="<?php echo $price['single_user']; ?>"></td></tr>     
     <tr><td width="150px">Multi User/Domain </td><td width="150px"><input style="width: 50px" type="text" size="8" name="wpmp_list[price][multi_user]" value="<?php echo $price['multi_user']; ?>"></td></tr>     
     <tr><td width="150px">Unlimited User/Domain </td><td width="150px"><input style="width: 50px" type="text" size="8" name="wpmp_list[price][unlimited_user]" value="<?php echo $price['unlimited_user']; ?>"></td></tr>     
     <tr><td width="150px">Developer </td><td width="150px"><input type="text" style="width: 50px" size="8" name="wpmp_list[price][dev_user]" value="<?php echo $price['dev_user']; ?>"></td></tr>     
    </table>
    </div>
     <div style="clear: both;"></div>
                </div>
                </div>
                </div>
                
            </div>  
        </div>
    </div>
     </li>
     
     <li>
      
     <div class="wrap">
        <div style="margin-top: 10px;"></div> 
        <div id="poststuff" >
            <div class="postbox " id="quick-notice-options">
            
                <div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Demo Info :</span></h3>
                 <div class="inside">
                        <div id="postcustomstuff">
                <div id="custom_box">
                
<table width="95%">
     <tr><td width="200px">Demo Site URL: </td><td><input type="text" style="width: 90%" name="wpmp_list[demo_site]" value="<?php echo $demo_site; ?>"></td></tr>
     <tr><td>Demo Admin URL: </td><td><input type="text" style="width: 90%" name="wpmp_list[demo_admin]" value="<?php echo $demo_admin; ?>"></td></tr>
     <tr><td>Username: </td><td><input type="text" style="width: 90%" name="wpmp_list[demo_username]" value="<?php echo $demo_username; ?>"></td></tr>
     <tr><td>Password: </td><td><input type="text" style="width: 90%" name="wpmp_list[demo_password]" value="<?php echo $demo_password; ?>"></td></tr>     
     </table>
                </div>
                </div>
                </div>
                
            </div>  
        </div>
    </div>
     </li>
     
     
     <li>
      
     <div class="wrap" style="width: 260px; float: left; margin-right: 10px;">
        <div style="margin-top: 10px;"></div> 
        <div id="poststuff" >
            <div class="postbox " id="quick-notice-options">
            
                <div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Upload Product File :</span></h3>
                 <div class="inside">
                        <div id="postcustomstuff">
                <div id="custom_box">
                <div id="currentfiles">

                    <?php

                         
                        
                    $adpdir = WPMP_UPLOAD_DIR; //WP_PLUGIN_DIR.'/wpmarketplace/product-files/';  

                    if($file!=''){
                    $value = $file;
                    $filename = end( explode('/',$value)  );
                    $filename = preg_replace("/wpmp\-([0-9]+)\-/","",$filename);
                    if(strlen($filename)>20)
                    $filename = substr($filename,0,10).'...'.substr($filename,strlen($filename)-13);
                    ?>
                    <div class="cfile">
                    <input type="hidden" value="<?php echo $value; ?>" name="wpmp_list[file]">
                    <nobr>
                    <b><img align="left" rel="del" src="<?php echo plugins_url();?>/wpmarketplace/images/remove.png">&nbsp;<?php echo  $filename; ?></b>
                    </nobr>
                    <div style="clear: both;"></div>
                    </div>


                    <?php
                    }
                    

                    ?>


                    <?php if($file):  ?>
                    <script type="text/javascript">


                    jQuery('img[rel=del], img[rel=undo]').click(function(){

                         if(jQuery(this).attr('rel')=='del')
                         {
                         
                         jQuery(this).parents('div.cfile').removeClass('cfile').addClass('dfile').find('input').attr('name','del[]');
                         jQuery(this).attr('rel','undo').attr('src','<?php echo plugins_url(); ?>/wpmarketplace/images/add.png').attr('title','Undo Delete');
                         
                         } else {
                                jQuery(this).parents('div.dfile').removeClass('dfile').addClass('cfile').find('input').attr('name','file');
                                jQuery(this).attr('rel','del').attr('src','<?php echo plugins_url(); ?>/wpmarketplace/images/remove.png').attr('title','Delete File');

                         
                         
                         }



                    });



                    </script>


                    <?php endif; ?>



                    </div>

                <div style="float: left;width:80%"> 
                      <label ><?php echo __("Select files to upload:","wpmarketplace"); ?></label><br/>
                      <input id="pf" name="pf" type="file" /></div>
                      <div style="float: left;width:50%"><div id="custom-queue1" class="uploadifyQueue"></div></div>
                <br>
                <br>
                <br>
                <br>
                </div>
                </div>
                </div>
                
            </div>  
        </div>
    </div>

      
     <div class="wrap" style="width: 260px; float: left;margin-right: 10px;">
        <div style="margin-top: 10px;"></div> 
        <div id="poststuff" >
            <div class="postbox " id="quick-notice-options">
            
                <div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Icon :</span></h3>
                 <div class="inside">
                        <div id="postcustomstuff">
                <div id="custom_box">
                
<?php    
        $path = "wp-content/plugins/wpmarketplace/images/icons/";
        $scan = scandir( ABSPATH.'/'.$path );
        $k = 0;
        foreach( $scan as $v )
        {
        if( $v=='.' or $v=='..' or is_dir('../'.$path.$v) ) continue;

        $fileinfo[$k]['file'] = 'wpmarketplace/images/icons/'.$v;
        $fileinfo[$k]['name'] = $v;
        $k++;
        }


        if( !empty($fileinfo) )
        {
                  
         include dirname(__FILE__).'/../libs/icon.php';

        } else {

        ?>
        <div class="updated" style="padding: 5px;">
            upload your icons on '/wp-content/plugins/wpmarketplace/images/icons/' using ftp</div>

        <?php } ?>
        
                </div>
                </div>
                </div>
                
            </div>  
        </div>
    </div>
    
    <div class="wrap" style="width: 260px; float: left;margin-right: 10px;">
        <div style="margin-top: 10px;"></div> 
        <div id="poststuff" >
            <div class="postbox " id="quick-notice-options">
            
                <div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Images :</span></h3>
                 <div class="inside">
                        <div id="postcustomstuff">
                <div id="custom_box">
                <ul id="adpcon">
                    <?php
                        $adpdir = WPMP_IMAGE_DIR;
                        if(is_array($images)){
                            foreach($images as $mpv){//echo $adpdir.$mpv;
                                //if(file_exists($adpdir.$mpv)){
                                ?>
                                 <li id='<?php echo ++$mmv; ?>' class='adp'>
                                 <input type='hidden'  id='in_<?php echo $mmv; ?>' name='wpmp_list[images][]' value='<?php echo $mpv; ?>' />
                                 <img style='position:absolute;z-index:9999;cursor:pointer;' id='del_<?php echo $mmv; ?>' rel="<?php echo $mmv; ?>" src='<?php echo plugins_url(); ?>/wpmarketplace/images/remove.png' class="del_adp" align=left />
                                 <img src='<?php echo plugins_url(); ?>/wpmarketplace/libs/timthumb.php?w=50&h=50&zc=1&src=<?php echo WPMP_IMAGE_URL.$mpv; ?>'/>
                                 <div style='clear:both'></div>
                                 </li>
                                <?php
                            }
                        //}
                        }
                    ?>
                    </ul>
                
                
                <div style="width:80%"> 
      <label ><?php echo __("Select files to upload:","wpmarketplace"); ?></label><br/>
      <input id="apv" name="apv" type="file" /></div>
      <div style="float: left;width:50%"><div id="custom-queue" class="uploadifyQueue"></div></div>




                </div>
                </div>
                </div>
                
            </div>  
        </div>
    </div>
     </li>
     
      <li style="clear: both;">    
   
      
      <div id="imgs">
       <table id="file_table" border="0" width="400px" cellpadding="0" cellspacing="0" style="border: none;">
        <tr ><td style="border: none;"></td><td style="border: none;"></td><td style="border: none;"></td></tr>
      <?php 
     /* if(!empty($_REQUEST['postid'])){
      $postattachment=get_posts("post_type=attachment&post_parent=".$thispost->ID);
      //print_r($postattachment);
      if(count($postattachment)>0){
          foreach($postattachment as $img){
              $arr[]=basename($img->guid); 
              
              
              
              $file_name=basename($img->guid);
        $fextension=get_file_extension($file_name);
        if($fextension!='png' && $fextension!='gif' && $fextension!='jpg' && $fextension!='jpeg' && $fextension!='tif' && $fextension!='bmp' && $fextension!='tiff'){
             $fltype= array("js","aac","ai","aiff","avi","c","cpp","css","dat","dmg","doc","docx","dotx","dwg","dxf","eps","exe","flv","h","hpp","html","ics","iso","java","key","mid","mp3","mp4","mpg","mpeg","odf","ods","odt","otp","ots","ott","pdf","php","ppt","psd","py","qt","rar","rb","rtf","sql","tga","tgz","txt","wav","xls","xlsx","xml","yml","zip"); 
             for($i=0;$i<count($fltype);$i++){
                 if($fextension==$fltype[$i]){
                   $imgsrc =plugins_url('/wpmarketplace/images/file-type-icons/').$fltype[$i].".png";
                   break; 
                 }else
                    $imgsrc =plugins_url('/wpmarketplace/images/file.png');
             }
        }else{
              $imgsrc=$img->guid;    
          }
        
        
          ?>
           
      
       <tr id="<?php echo $img->ID;?>">
       <td >
      

       <img  style="float:left;" id="img_" src="<?php echo $imgsrc;?>"  width=32  style="padding:5px; margin:1px; float:left; border:#fff 2px solid " /> 
         
          </td>
          <td >
          <?php echo basename($img->guid);?>
          </td>
          <td >
          <?php if($img->post_mime_type)echo $img->post_mime_type; ?>
          </td>
          <td>
          <img class="delimg" rel="<?php echo $img->ID;?>" style="cursor:pointer; float:left;" src="<?php echo plugins_url('/wpmarketplace/images/remove.png');?>" />   
          </td>

       </tr>
        <?php
          }
          
          //$imgarr=implode("~",$arr);
      }
      //print_r($postattachment);
      }*/
      ?>
       </table> 
      </div>
      </li> 
      
     
       <li>
       
       <div style="clear: both;"></div>
         <input type="submit"   name="user_post_submit" id="user_post_submit" value="Submit">
         <input type="reset" value="Cancel"  name="cancel" id="cancel" onclick="location.href='<?php the_permalink();?>'">
      </li>
 </ul>
</form> 
</div>
<script type="text/javascript">
    
   <?php $upload_dir = wp_upload_dir();
   
   ?> 
           
    var filenames=new Array();
    var fcount=0;
    var imgcount=0;
    jQuery(document).ready(function() {
        
      //jQuery('#adpcon').sortable({placeholder:'highlight'});
        jQuery('#apv').uploadify({
    
          'uploader'  : '<?php echo plugins_url(); ?>/wpmarketplace/uploadify/uploadify.swf',   
          /*'script'    : '<?php echo plugins_url(); ?>/wpmarketplace/uploadify/uploadify.php',  */
          'script'    : '<?php echo home_url('/wp-admin/');?>admin.php?task=wpmp_upload_previews',                      
          'cancelImg' : '<?php echo plugins_url(); ?>/wpmarketplace/uploadify/cancel.png',  
          'folder'    : '<?php echo str_replace($_SERVER['DOCUMENT_ROOT'],'',UPLOAD_DIR); ?>',
          'multi'  : true,
          'wmode': 'transparent',
          'hideButton': true,
          'auto'      : true,
          'width': 150,
          'height': 30,
          'onComplete': function(event, ID, fileObj, response, data) {                            
                            if(fileObj.name.length>20) nm = fileObj.name.substring(0,7)+'...'+fileObj.name.substring(fileObj.name.length-10);
                            jQuery('#adpcon').append("<div id='"+ID+"' style='display:none;float:left;padding:5px;' class='adp'><input type='hidden' id='in_"+ID+"' name='wpmp_list[images][]' value='"+response+"' /><nobr><b><img style='position:absolute;z-index:9999;cursor:pointer;' id='del_"+ID+"' src='<?php echo plugins_url(); ?>/wpmarketplace/images/remove.png' rel='del' align=left /><img src='<?php echo plugins_url(); ?>/wpmarketplace/libs/timthumb.php?w=50&h=50&zc=1&src=<?php echo WPMP_IMAGE_URL; ?>"+response+"'/></b></nobr><div style='clear:both'></div></div>");
                            jQuery('#'+ID).fadeIn();
                            jQuery('#del_'+ID).click(function(){
                                if(confirm('Are you sure?')){
                                    jQuery.post(ajaxurl,{action:'wpmp_delete_preview',file:jQuery('#in_'+ID).val()})
                                    jQuery('#'+ID).fadeOut().remove();
                                }
                                
                            });
                            
                         }   
        });
                        
        jQuery('.del_adp').click(function(){
                                if(confirm('Are you sure?')){
                                    jQuery.post(ajaxurl,{action:'wpmp_delete_preview',file:jQuery('#in_'+jQuery(this).attr('rel')).val()})
                                    jQuery('#'+jQuery(this).attr('rel')).fadeOut().remove();
                                }
                                
                            });
      
      
      
      
      
      jQuery('#pf').uploadify({
    
          'uploader'  : '<?php echo plugins_url(); ?>/wpmarketplace/uploadify/uploadify.swf',   
          /*'script'    : '<?php echo plugins_url(); ?>/wpmarketplace/uploadify/uploadify.php',  */
          'script'    : '<?php echo home_url('/wp-admin/');?>admin.php?task=wpmp_upload_product_files',                      
          'cancelImg' : '<?php echo plugins_url(); ?>/wpmarketplace/uploadify/cancel.png',  
          'folder'    : '<?php echo str_replace($_SERVER['DOCUMENT_ROOT'],'',UPLOAD_DIR); ?>',
          'multi'  : false,
          'wmode': 'transparent',
          'hideButton': true,
          'auto'      : true,
          'width': 150,
          'height': 30,
          'onComplete': function(event, ID, fileObj, response, data) {                            
                            var nm = fileObj.name;
                            if(fileObj.name.length>20) nm = fileObj.name.substring(0,7)+'...'+fileObj.name.substring(fileObj.name.length-10);
                            jQuery('#currentfiles').html("<div id='"+ID+"' style='display:none' class='cfile'><input type='hidden' id='in_"+ID+"' name='wpmp_list[file]' value='"+response+"' /><nobr><b><img id='del_"+ID+"' src='<?php echo plugins_url(); ?>/wpmarketplace/images/remove.png' rel='del' align=left />&nbsp;"+nm+"</b></nobr><div style='clear:both'></div></div>");
                            jQuery('#'+ID).fadeIn();
                            jQuery('#del_'+ID).click(function(){
                                if(jQuery(this).attr('rel')=='del'){
                                jQuery('#'+ID).removeClass('cfile').addClass('dfile');
                                jQuery('#in_'+ID).attr('name','del[]');
                                jQuery(this).attr('rel','undo').attr('src','<?php echo plugins_url(); ?>/wpmarketplace/images/add.png').attr('title','Undo Delete');
                                } else if(jQuery(this).attr('rel')=='undo'){
                                jQuery('#'+ID).removeClass('dfile').addClass('cfile');
                                jQuery('#in_'+ID).attr('name','files[]');
                                jQuery(this).attr('rel','del').attr('src','<?php echo plugins_url(); ?>/wpmarketplace/images/remove.png').attr('title','Delete File');
                                }
                                
                                
                            });
                            
                         }    
        });
                        
       
      
    });
    </script>

 