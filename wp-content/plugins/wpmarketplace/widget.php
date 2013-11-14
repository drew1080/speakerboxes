<?php
class wpmarketplaceMiniCart extends WP_Widget {
    
    function __construct() {
        global $pagenow;
        parent::WP_Widget( /* Base ID */'wpmarketplaceMiniCart', /* Name */'WP Marketplace Mini Cart widget', array( 'description' => 'Wpmarketplace Mini Cart widget' ) );

        
    }

    /** @see WP_Widget::widget */
    function widget( $args, $instance ) {
        extract( $args );
        //wp_enqueue_script('jquery-form');
        $title =  $instance['title'] ;        
        $number_posts =  $instance['number_posts']?$instance['number_posts']:5 ;
        
         echo $before_widget;
       if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } 
       
       $currency_sign = get_option('_wpmp_curr_sign','$'); 
                        
       $shopping_cart=load_ajax_cart();
       //$shopping_cart1=$shopping_cart);
//print_r($shopping_cart1);
        if(count($cart_items)==0) $cart = __("No item in cart.","wpmarketplace")."<br/><a href='".$settings['continue_shopping_url']."'>".__("Continue shopping","wpmarketplace")."</a>";
        ?>
        <div id="mini_cart_widget" title="<?php __("Clicke here to view cart details","wpmarketplace");?>"><b><?php  echo $shopping_cart['items'];?> items</b> ( Subtotal: <?php echo $currency_sign.wpmp_get_cart_subtotal();?> )
        </div>
        <div id="mini_cart_details"><?php echo $shopping_cart['content'];?></div>
        
        <style type="text/css">
        #mini_cart_widget{
            padding: 5px;
            cursor: pointer;
            border:1px solid #ccc;
            border-radius:5px;
            padding: 5px;
            background: url('<?php echo plugins_url('wpmarketplace/images/cart.png'); ?>') 8px center no-repeat;
            line-height: 36px;
            height: 36px;
            padding-left: 36px;
        }
        #mini_cart_details{
            display: none;
            border:1px solid #ccc;
            border-radius:5px;
            margin: 10px 0;
        }
        .wpmp_cart_delete_item{
            text-decoration: none;
            padding: 5px;
            color: #800000 !important;
        }
        </style>
        <script type="text/javascript">
        jQuery('#mini_cart_widget').live("click",function(){
            jQuery('#mini_cart_details').slideToggle();
        });
        
        </script>
        <?php
        
           echo $after_widget;
    }

    
    function update( $new_instance, $old_instance ) {
        $instance = $new_instance;
       
        return $instance;
    }

   
    function form( $instance ) {
        
        
        if ( $instance ) {
            extract($instance);
        }
        else {
            
        }
        
        ?>
      
         <p>        
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
 
        
        
        <?php 
    }

} 
add_action( 'widgets_init', create_function( '', 'register_widget("wpmarketplaceMiniCart");' ) );


function load_ajax_cart(){
    
        global $wpdb;
       $cart_data = wpmp_get_cart_data();
       foreach($cart_data as $pid=>$cdt){
            extract($cdt);
            if($pid){
                $cart_items[$pid] = get_post($pid,ARRAY_A);
                $cart_items[$pid]['quantity'] =  $quantity;
                $cart_items[$pid]['discount'] =  $discount;
                $cart_items[$pid]['variation'] =  $variation;
                $cart_items[$pid]['price'] = (double)$price;
                
                if($cdt['coupon']){
                    $valid_coupon=check_coupon($pid,$coupon);
                    if($valid_coupon!=0){
                        $cart_items[$pid]['coupon'] =  $coupon;
                        $cart_items[$pid]['coupon_discount'] =  $valid_coupon;
                    }
                    else
                        $cart_items[$pid]['error'] =  "Coupon does not exist";
                }
            }
        }
       $settings = get_option('_wpmp_settings');
       $currency_sign = get_option('_wpmp_curr_sign','$');
        $total_quantity=0; 
        $cart = "<form method='post' class='abc' action='' name='widgetcart_form'><input type='hidden' name='wpmp_update_cart' value='1' /><table class='wpdm_cart'>";
        if(is_array($cart_items)){
            //print_r($cart_items);
        foreach($cart_items as $item){
            $prices=0;
            $variations="";
            @extract(get_post_meta($item['ID'],"wpmp_list_opts",true));
            $svariation = array() ;
            foreach($variation as $key=>$value){
                foreach($value as $optionkey=>$optionvalue){
                  if($optionkey!="vname"){
                     if($item['variation']){                
                        foreach($item['variation'] as $var){                   
                            if($var==$optionkey){
                                $prices+=$optionvalue['option_price'];
                                $svariation[] = $optionvalue['option_name'].": ".($optionvalue['option_price']>0?'+':'').$currency_sign.$optionvalue['option_price'];
                                $variations .= '<input type="hidden" name="cart_items['.$item[ID].'][variation][]" value="'.$optionkey.'">';
                            }
                        }
                    }    
                  }
                }
            }
            if($svariation)
            $variations .= "<small><i>".implode(", ",$svariation)."</i></small>";     
            
            if($item['coupon_discount']){$discount_amount=(($item['coupon_discount']/100)* ($item['price']+$prices)*$item[quantity]);$discount_style="style='color:#008000; text-decoration:underline;'";$discount_title='Discounted $'.$discount_amount." for coupon code '$discount_amount'";} else{ $discount_amount="";$discount_style="";$discount_title="";}
            if($item['error']){$coupon_style="style='border:1px solid #ff0000;'";$title=$item['error'];} else {$coupon_style="";$title="";}
            //filter for adding various message after cart item
            $cart_item_info="";
            $cart_item_info = apply_filters("wpmp_cart_item_info", $cart_item_info, $item['ID']);
            $imgurl="";
            $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($item[ID]), array(600,300) );                       $imgurl = $thumb['0'];
            $cart .= "<tr id='mini_cart_item_{$item[ID]}'><td><a title='".__('Delete cart item','wpmarketplace')."' class='wpmp_cart_delete_item' href='#' onclick='return wpmp_mpp_remove_cart_item($item[ID])'>&times;</a></td><td><a href='".get_permalink($item[ID])."'><img src='".plugins_url("wpmarketplace")."/libs/timthumb.php?src=".$imgurl."&w=40&h=40'></a></td><td class='cart_item_title'>$item[post_title]<br>$variations".$cart_item_info."<input type='hidden' name='cart_items[$item[ID]][license]' value='$item[license]'><input type='hidden' name='cart_items[$item[ID]][price]' value='$item[price]'></td><td class='cart_item_subtotal amt'>".$currency_sign.number_format($item['price']+$prices)."x ".$item['quantity']." <input type='hidden' name='cart_items[$item[ID]][item_total]' value='".number_format((($item['price']+$prices)*$item['quantity'])-$discount_amount,2)."'></td></tr>";
          $total_quantity+= $item['quantity']; 
        }}
        $cart .= "

        <tr><td colspan=3 align=right>".__("Subtotal:","wpmarketplace")."</td><td class='amt' id='wpmp_mini_cart_subtotal'>".$currency_sign.wpmp_get_cart_subtotal()."</td></tr>
        
        <tr><td colspan=4><button class='btn btn-primary btn-large' type='button' onclick='location.href=\"".get_permalink($settings['page_id'])."\"'><i class='icon-white icon-edit'></i> ".__("View Cart","wpmarketplace")."</button> <button class='btn btn-success btn-large' type='button' onclick='location.href=\"".get_permalink($settings['check_page_id'])."\"'><i class='icon-white icon-shopping-cart'></i> ".__("Checkout","wpmarketplace")."</button></td></tr>
        </table>

        </form>

        <script language='JavaScript'>
        <!--
            function  wpmp_mpp_remove_cart_item(id){
                   if(!confirm('Are you sure?')) return false;
                   jQuery('#mini_cart_item_'+id+' *').css('color','#ccc');
                   jQuery.post('".home_url('?wpmp_remove_cart_item=')."'+id
                   ,function(res){ 
                   var obj = jQuery.parseJSON(res);
                   
                   jQuery('#mini_cart_item_'+id).fadeOut().remove(); 
                   /*jQuery('#wpmp_cart_total').html(obj.cart_total); 
                   jQuery('#wpmp_cart_discount').html(obj.cart_discount);*/ 
                   jQuery('#wpmp_mini_cart_subtotal').html(obj.cart_subtotal); });
                   return false;
            }  
        //-->
        </script>

        ";
        $cart_['content']=$cart;
        $cart_['items']=$total_quantity;
        return $cart_;
    
}


?>
