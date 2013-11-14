<?php
    global $wpdb;
?>
<div class="wrap">
    <div class="icon32"><img src="<?php echo plugins_url('wpmarketplace/images/order.png'); ?>" /></div><h2><?php echo __("Orders","wpmarketplace");?> 
    
    <div style="float: right;">
    <h2 style="border-radius:4px;font-size: 12pt;padding:5px 20px;margin-right:10px;background: #5CA572;color:#fff;text-shadow:none;font-weight: bolder"><?php echo __("Total Sales:","wpmarketplace");?> <?php $total = $wpdb->get_var("select sum(total) as tamount from {$wpdb->prefix}mp_orders where payment_status='Completed'"); echo '$'.number_format($total,2); ?></h2><br />
    </div>
    </h2>

    
<?php if(isset($msg)):
        if(is_array($msg)):
            foreach($msg as $a => $b):
            echo "<h3>$b</h3>";
            endforeach;
        else:
            echo "<h3>$msg</h3>";
        endif;
endif;     
?>


           
<form method="get" action="" id="posts-filter">
 <input type="hidden" name="post_type" value="wpmarketplace">
 <input type="hidden" name="page" value="orders">
<div class="tablenav">

<div class="alignleft actions">
   

 <select class="select-action" name="ost">
<option value="">Order status:</option>
<option value="Pending" <?php if(isset($_REQUEST['ost'])) echo $_REQUEST['ost']=='Pending'?'selected=selected':''; ?>>Pending</option>
<option value="Processing" <?php if(isset($_REQUEST['ost'])) echo $_REQUEST['ost']=='Processing'?'selected=selected':''; ?>>Processing</option>
<option value="Completed" <?php if(isset($_REQUEST['ost'])) echo $_REQUEST['ost']=='Completed'?'selected=selected':''; ?>>Completed</option>
<option value="Canceled" <?php if(isset($_REQUEST['ost'])) echo $_REQUEST['ost']=='Canceled'?'selected=selected':''; ?>>Canceled</option>
</select>
      
<select class="select-action" name="pst">
<option value="">Payment status:</option>
<option value="Pending" <?php if(isset($_REQUEST['pst'])) echo $_REQUEST['pst']=='Pending'?'selected=selected':''; ?>>Pending</option>
<option value="Processing" <?php if(isset($_REQUEST['pst'])) echo $_REQUEST['pst']=='Processing'?'selected=selected':''; ?>>Processing</option>
<option value="Completed" <?php if(isset($_REQUEST['pst'])) echo $_REQUEST['pst']=='Completed'?'selected=selected':''; ?>>Completed</option>
<option value="Canceled" <?php if(isset($_REQUEST['pst'])) echo $_REQUEST['pst']=='Canceled'?'selected=selected':''; ?>>Canceled</option>
</select>
<?php echo __("Date","wpmarketplace");?><span class="info infoicon" title="(yyyy-mm-dd)">(?)</span> : 
<?php echo __("from","wpmarketplace");?> <input type="text" name="sdate" value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate']; ?>"> 
<?php echo __("to","wpmarketplace");?> <input type="text" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate']; ?>">

<?php echo __("Order ID:","wpmarketplace");?> <input type="text" name="oid" value="<?php if(isset($_REQUEST['oid'])) echo $_REQUEST['oid']; ?>">

<input type="submit" class="button-secondary action" id="doaction" name="doaction" value="Apply">

| <b><?php echo $t; ?> <?php echo __("order(s) found","wpmarketplace");?></b>
</div>

<br class="clear">
</div>

<div class="clear"></div>

<table cellspacing="0" class="widefat fixed">
    <thead>
    <tr>
    <th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
 
    <th style="" class="manage-column column-media" id="media" scope="col"><?php echo __("Order ID","wpmarketplace");?></th>
    <th style="" class="manage-column column-author" id="author" scope="col"><?php echo __("Total Amount","wpmarketplace");?></th>
    <th style="" class="manage-column column-author" id="author" scope="col"><?php echo __("Customer Name","wpmarketplace");?></th>
    <th style="" class="manage-column column-parent" id="parent" scope="col"><?php echo __("Order Status","wpmarketplace");?></th>
    <th style="" class="manage-column column-parent" id="parent" scope="col"><?php echo __("Payment Status","wpmarketplace");?></th>
    <th style="" class="manage-column column-parent" id="parent" scope="col"><?php echo __("Order Date","wpmarketplace");?></th>
    </tr>
    </thead>

    <tfoot>
    <tr>
    <th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
 
    <th style="" class="manage-column column-media" id="media" scope="col"><?php echo __("Order ID","wpmarketplace");?></th>
    <th style="" class="manage-column column-author" id="author" scope="col"><?php echo __("Total Amount","wpmarketplace");?></th>
    <th style="" class="manage-column column-author" id="author" scope="col"><?php echo __("Customer Name","wpmarketplace");?></th>
    <th style="" class="manage-column column-parent" id="parent" scope="col"><?php echo __("Order Status","wpmarketplace");?></th>
    <th style="" class="manage-column column-parent" id="parent" scope="col"><?php echo __("Payment Status","wpmarketplace");?></th>
    <th style="" class="manage-column column-parent" id="parent" scope="col"><?php echo __("Order Date","wpmarketplace");?></th>
    </tr>
    </tfoot>

    <tbody class="list:post" id="the-list">
    <?php     
    foreach($orders as $order) { 
          $user_info = get_userdata($order->uid);         
        ?>
    <tr valign="top" class="alternate author-self status-inherit" id="post-8">

                <th class="check-column" scope="row"><input type="checkbox" value="<?php echo $order->order_id; ?>" name="id[]"></th>
                
                <td class="media column-media">
                    <strong>
                    <a title="Edit" href="edit.php?post_type=wpmarketplace&page=orders&task=vieworder&id=<?php echo $order->order_id; ?>">
                    <?php echo $order->title; ?><?php echo $order->order_id; ?>
                    </a>
                    </strong>
                    <div class="row-actions">
                        <span class="trash">
                            <a title="Delete This Item" href="edit.php?post_type=wpmarketplace&page=orders&task=delete_order&id=<?php echo $order->order_id; ?>">Delete</a>
                        </span>
                    </div>
                </td>
                <td class="author column-author"><?php echo $order->total; ?> USD</td>
                <td class="author column-author"><?php echo $user_info->user_login; ?></td>
                <td class="parent column-parent"><?php echo $order->order_status; ?></td>
                <td class="parent column-parent"><?php echo $order->payment_status; ?></td>
                <td class="parent column-parent"><?php echo date("D, F d, Y",$order->date); ?></td>
     
     </tr>
     <?php } ?>
    </tbody>
</table>
                    
<?php
 

$page_links = paginate_links( array(
    'base' => add_query_arg( 'paged', '%#%' ),
    'format' => '',
    'prev_text' => __('&laquo;'),
    'next_text' => __('&raquo;'),
    'total' => ceil($t/$l),
    'current' => $p
));


?>

<div id="ajax-response"></div>

<div class="tablenav">

<?php if ( $page_links ) { ?>
<div class="tablenav-pages"><?php $page_links_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>%s',
    number_format_i18n( ( $_GET['paged'] - 1 ) * $l + 1 ),
    number_format_i18n( min( $_GET['paged'] * $l, $t ) ),
    number_format_i18n( $t ),
    $page_links
); echo $page_links_text; ?></div>
<?php } ?>

<div class="alignleft actions">
  
<input type="submit" class="button-secondary action" id="doaction2" name="doaction2" value="Apply">
<input type="submit" class="button-primary action" id="delete_selected" name="delete_selected" value="Delete Selected">
<input type="hidden" id="delete_confirm" name="delete_confirm" value="0" />
</div>
    
<select class="select-action" name="delete_all_by_payment_sts">
<option value="">Payment Status:</option>
<option value="Pending">Pending</option>
<option value="Processing">Processing</option>
<option value="Canceled">Canceled</option>
</select>    
<input type="submit" class="button-primary action" name="delete_by_payment_sts" value="Delete All By Payment Status" />    
<br class="clear">
</div>
    <div style="display: none;" class="find-box" id="find-posts">
        <div class="find-box-head" id="find-posts-head"><?php echo __("Find Posts or Pages","wpmarketplace");?></div>
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
<script type="text/javascript">
    jQuery(document).ready(function($){
        $("#delete_selected").on('click',function(){
            $("#delete_confirm").val("1");
        });
    });
</script>