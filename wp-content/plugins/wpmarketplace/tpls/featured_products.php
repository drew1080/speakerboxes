<?php
    global $wpdb;
?>
<div class="wrap">
    <div class="icon32" id="icon-edit"><br></div>
<h2><?php echo __('Featured Products','wpmarketplace'); ?></h2>
           
<form method="post" action="" id="posts-filter">
 <input type="hidden" name="post_type" value="wpmarketplace">
 <input type="hidden" name="page" value="featured">
 <input type="hidden" name="task" value="add_feature">
<div class="tablenav">

<div class="alignleft actions">
   

<table><tr><td colspan="2"> 
<?php echo __("Product:","wpmarketplace");?>  <input type="text" name="ptitle" id="ptitle" value=""><div id="pids" style="display: none;"></div>  </td></tr><tr><td>
<?php echo __("Start Date","wpmarketplace");?><br/><input type="text" name="sdate" id="sdate" value=""> 
</td><td>
<?php echo __("End Date","wpmarketplace");?><br /><input type="text" name="edate" id="edate" value="">    
<script type="text/javascript">
            jQuery(document).ready(function(){                
                jQuery("#ptitle").fcbkcomplete({
                    json_url: ajaxurl+"?action=wpmp_autosuggest",
                    addontab: true,                   
                    maxitems: 10,
                    input_min_size: 0,
                    height: 10,
                    cache: true,
                    newel: false,
                    select_all_text: "",
                    onselect:function(res){   
                        
                        jQuery('#pids').append('<input type="hidden" id="my'+res._id+'" name="fids[]" value="'+res._value+'">');
                    },
                    onremove:function(res){
                       jQuery('#my'+res._id).remove();
                    }
                });
                
                jQuery('.remove_fea').live("click",function(){
                     if(confirm("Are you sure to remove the feature product")){
                         var pid = jQuery(this).attr("rel");
                         jQuery.post(ajaxurl,{action:"wpmp_remove_featured",id:pid},
                         function(res){
                            
                             jQuery('#r_'+pid).fadeOut().remove();
                         }
                         )
                     }
                     return false;
                });
                
                jQuery( "#sdate" ).datepicker();
                jQuery( "#edate" ).datepicker();
            });
        </script>
</td></tr><tr><td colspan="2">
<input type="submit" class="button-primary action" id="addfeatured" name="addfeatured" value="Add to Featured">
 </td></tr></table><br><br>
</div>

<br class="clear">
</div>

<div class="clear"></div>

<table cellspacing="0" class="widefat fixed">
    <thead>
    <tr>
    <th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
 
    <th width="30px"><?php echo __("ID","wpmarketplace");?></th>
    <th style="" class="manage-column " id="author" scope="col"><?php echo __("Product Title","wpmarketplace");?></th>
    <th style="" class="manage-column column-author"  scope="col"><?php echo __("Start Date","wpmarketplace");?></th>
    <th style="" class="manage-column column-parent"  scope="col"><?php echo __("End Date","wpmarketplace");?></th>
    <th style="" class="manage-column column-parent"  scope="col"><?php echo __("Action","wpmarketplace");?></th>
    </tr>
    </thead>

    <tfoot>
    <tr>
    <th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
 
    <th><?php echo __("ID","wpmarketplace");?></th>
    <th style="" class="manage-column " id="author" scope="col"><?php echo __("Product Title","wpmarketplace");?></th>
    <th style="" class="manage-column column-author"  scope="col"><?php echo __("Start Date","wpmarketplace");?></th>
    <th style="" class="manage-column column-parent"  scope="col"><?php echo __("End Date","wpmarketplace");?></th>
    <th style="" class="manage-column column-parent"  scope="col"><?php echo __("Action","wpmarketplace");?></th>
    </tr>
    </tfoot>

    <tbody class="list:post" id="the-list">
    <?php     
    foreach($featured_products as $featured_product) { 
          //$user_info = get_userdata($order->uid);         
        ?>
    <tr id="r_<?php echo $featured_product->id; ?>" valign="top" class="alternate author-self status-inherit" id="post-8">

                <th class="check-column" scope="row"><input type="checkbox" value="8" name="id[]"></th>
                
                <td width="30">
                    <strong>
                    <a title="Edit" href="post.php?post=<?php echo $featured_product->ID; ?>&action=edit">
                    <?php echo $featured_product->ID; ?> 
                    </a>
                    </strong>
                </td>
                <td class="author "><a title="Edit" href="<?php echo get_permalink($featured_product->ID); ?>"><?php echo $featured_product->post_title; ?></a></td>
                <td class="author column-author"><?php echo date(get_option('date_format'),$featured_product->startdate); ?></td>
                <td class="parent column-parent"><?php echo date(get_option('date_format'),$featured_product->enddate); ?></td>
                <td class="parent column-parent"><button style="padding-top:2px" rel="<?php echo $featured_product->id; ?>" class="remove_fea button"><img src="<?php echo plugins_url("wpmarketplace");?>/images/remove.png"></button></td>
                
     
     </tr>
     <?php } ?>
    </tbody>
</table>
                    
<?php
 

?>

<div id="ajax-response"></div>

<div class="tablenav">

<?php if ( isset($page_links) ) { ?>
<div class="tablenav-pages"><?php $page_links_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>%s',
    number_format_i18n( ( $_GET['paged'] - 1 ) * $l + 1 ),
    number_format_i18n( min( $_GET['paged'] * $l, $t ) ),
    number_format_i18n( $t ),
    $page_links
); echo $page_links_text; ?></div>
<?php } ?>

<div class="alignleft actions">


</div>

<br class="clear">
</div>
    <div style="display: none;" class="find-box" id="find-posts">
        <div class="find-box-head" id="find-posts-head">Find Posts or Pages</div>
        <div class="find-box-inside">
            <div class="find-box-search">
                
                <input type="hidden" value="" id="affected" name="affected">
                <input type="hidden" value="3a4edcbda3" name="_ajax_nonce" id="_ajax_nonce">                <label  for="find-posts-input" class="screen-reader-text"><?php echo __("Search","wpmarketplace"); ?></label>
                <input type="text" value="" name="ps" id="find-posts-input">
                <input type="button" class="button" value="Search" onclick="findPosts.send();"><br>

                <input type="radio" value="posts" checked="checked" id="find-posts-posts" name="find-posts-what">
                <label  for="find-posts-posts"><?php echo __("Posts","wpmarketplace"); ?></label>
                <input type="radio" value="pages" id="find-posts-pages" name="find-posts-what">
                <label  for="find-posts-pages"><?php echo __("Pages","wpmarketplace"); ?></label>
            </div>
            <div id="find-posts-response"></div>
        </div>
        <div class="find-box-buttons">
            <input type="button" value="Close" onclick="findPosts.close();" class="button alignleft">
            <input type="submit" value="Select" class="button-primary alignright" id="find-posts-submit">
        </div>
    </div>
</form>
<br class="clear">

</div>