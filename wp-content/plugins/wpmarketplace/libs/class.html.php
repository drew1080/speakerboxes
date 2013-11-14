<?php
class wpmp_html{
    //for marketplace prime theme product details page
    function product_price(){
        global $current_user,$post;
        $pinfo = get_post_meta($post->ID,"wpmp_list_opts",true);
        @extract($pinfo);
        //check settings for the stock enable or not
        $settings = maybe_unserialize(get_option('_wpmp_settings'));
        $cart_enable="";
        if($settings['stock']['enable']==1){
            if($manage_stock==1){
                if($stock_qty>0)$cart_enable=""; else $cart_enable=" disabled=disabled ";
            } 
        }
            
        $currency_sign = get_option('_wpmp_curr_sign','$');
        $discount = $discount[$current_user->roles[0]];     
        $base_price = (double)$base_price;    
        global $post;
        $prices_text = apply_filters('price_text',__('Prices','wpmarketplace'));
        $prices = <<<PRICE
        <form method="post" action="" name="cart_form">
        <input type="hidden" name="add_to_cart" value="add">
        <input type="hidden" name="pid" value="$post->ID">
        <input type="hidden" name="discount" value="$discount">

        <div class='wpmp-prices'>
         
        <ul>
PRICE;
        $script='<script> ';
        $price_html = number_format($base_price,2);
        if($sales_price>0) $price_html = "<sub><strike>{$currency_sign}{$price_html}</strike></sub> ".$currency_sign.number_format($sales_price,2);
        else $price_html = $currency_sign.$price_html;
        if($base_price==0) $price_html = __('Free', 'wpmarketplace');
        $prices.='<li class="wpmp-regular-price"><h3>'.$price_html.'</h3></li>';
        if($price_variation){
          foreach($variation as $key=>$value){
              if(isset($value['multiple'])){
                  $multiple = "multiple='multiple'";
              }
              else $multiple = "";
              $prices.='<li><div  style="display:block;">'.ucfirst($value['vname']).'</div> <div  style="display:block;"><select name="variation[]" id="var_price_'.uniqid().'"' . $multiple .' >';
             
              foreach($value as $optionkey=>$optionvalue){
                  if(is_array($optionvalue)){
                    $vari = (intval($optionvalue['option_price'])!=0)?" ( + {$currency_sign}".number_format($optionvalue['option_price'],2)." )":"";  
                    $prices.='<option value="'.$optionkey.'">'." ".$optionvalue['option_name'].$vari.'</option>';
                  }
              }
              $prices.='</select></div></li>';
          } 
        }
        
        if($discount>0){ 
            $discount_msg = "$discount% discount";
            $prices .=  "<li class='discount-msg'>$discount_msg ".__("in all prices","wpmarketplace")."</li>";
        }
        $message="";
        
        $messages = apply_filters("wpmp_product_pricing_message",$message, $post->ID);
        
        $prices .= $messages;
        if($settings['instant_download']==1&&$base_price==0&&$pinfo['digital_activate']==1)
        $prices .= '<li><a href="'.home_url('/?wpmpfile='.$post->ID).'" class="btn btn-success btn-download">'.__('Download','wpmarketpalce').'</a></li>';
        else
        $prices.='<li><div class="clearboth"></div><div class="add-to-cart-button"><button '.$cart_enable.' class="btn btn-success" type="submit" ><i class="icon-shopping-cart icon-white"></i> '.__("Add to Cart","wpmarketplace").'</button></div></li>';         
        
        $prices .= <<<PRICE
       
        </ul>
        </div>
        </form>
PRICE;
        
        $prices = apply_filters("wpmp_product_price",$prices);
        
        //$prices = apply_filters("wpmp_user_subscription",$post,$prices);
        return $prices;
    }
   function product_price_for_prime(){
        global $current_user,$post;
        
        @extract(get_post_meta($post->ID,"wpmp_list_opts",true));
        //check settings for the stock enable or not
        $settings = maybe_unserialize(get_option('_wpmp_settings'));
        $cart_enable="";
        if($settings['stock']['enable']==1){
            if($manage_stock==1){
                if($stock_qty>0)$cart_enable=""; else $cart_enable=" disabled ";
            } 
        }
            
        $currency_sign = get_option('_wpmp_curr_sign','$');
        $discount = $discount[$current_user->roles[0]];     
        $base_price = (double)$base_price;    
        global $post;
        $prices_text = apply_filters('price_text',__('Prices','wpmarketplace'));
        $prices = <<<PRICE
        <form method="post" action="" name="cart_form">
        <input type="hidden" name="add_to_cart" value="add">
        <input type="hidden" name="pid" value="$post->ID">
        <input type="hidden" name="discount" value="$discount">

        <div class='wpmp-prices'>
         
        <ul>
PRICE;
        $script='<script> ';
        $base_price = number_format($base_price,2);
        $prices.='<li class="wpmp-regular-price"><div style="width:47%;float:left;">'.__("Regular Price","wpmarketplace").' </div> <div  style="width:47%;float:left;">'.$currency_sign.$base_price."</div></li>";
        if($price_variation){
          foreach($variation as $key=>$value){
              $prices.='<li><div  style="display:block;">'.ucfirst($value['vname']).'</div> <div  style="display:block;"><select name="variation[]" id="var_price_'.uniqid().'">';
              
              foreach($value as $optionkey=>$optionvalue){
                  if($optionkey!="vname"){
                      
                    $prices.='<option value="'.$optionkey.'">'." ".$optionvalue['option_name']." : {$currency_sign}".$optionvalue['option_price'].'</option>';
                  }
              }
              $prices.='</select></div></li>';
          } 
        }
        
        if($discount>0){ 
            $discount_msg = "$discount% discount";
            $prices .=  "<li class='discount-msg'>$discount_msg ".__("in all prices","wpmarketplace")."</li>";
        }
        $message="";
        
        $messages = apply_filters("wpmp_product_pricing_message",$message, $post->ID);
        
        $prices .= $messages;
        
        $prices.='<li><div class="clearboth"></div><div class="add-to-cart-button"><button '.$cart_enable.' class="btn btn-danger" type="submit" ><i class="icon-shopping-cart icon-white"></i> '.__("Add to Cart","wpmarketplace").'</button></div></li>';         
        
        $prices .= <<<PRICE
       
        </ul>
        </div>
        </form>
PRICE;
        
        $prices = apply_filters("wpmp_product_price",$prices);
        //$prices = apply_filters("wpmp_user_subscription",$post,$prices);
        return $prices;
    }
    
   
    function product_rating(){
        $pid = get_the_ID();//postid  
        $user_id = get_current_user_id();
        
        $cnt = get_post_meta($pid,"post_rating",true);
        $count=0;
        $sum=0;
        if($cnt){
         foreach($cnt as $val){
             $count++;
              $sum+=$val;
         }
        }
         $avg=0;
         if($count>0)
         $avg=($sum/$count);
         $star1=$star2=$star3=$star4=$star5=0;
         if($avg>=0)$star1=" star_on";
         if($avg>=1.4)$star2=" star_on";
         if($avg>=2.4)$star3=" star_on";
         if($avg>=3.4)$star4=" star_on";
         if($avg>=4.4)$star5=" star_on";
         
          $rating =<<<RATE
        <div id="rating_area">
           
        <a href='#' id='star1' class="star star1 $star1" rate_value="1" post="$pid" >1</a>
        <a href='#' id='star2' class="star star2 $star2"  rate_value="2" post="$pid">2</a>
        <a href='#' id='star3' class="star star3 $star3"  rate_value="3" post="$pid">3</a>
        <a href='#' id='star4' class="star star4 $star4"  rate_value="4" post="$pid">4</a>
        <a href='#' id='star5' class="star star5 $star5"  rate_value="5" post="$pid">5</a>
        <div class="clear"></div>
         Average <span id="average_vote">$avg</span> 
         <div class="clear"></div>
        </div>
                                                             
RATE;
    

$content1 = <<<CONT

<div id="rating">$rating</div>
CONT;
return $content1;
    }
    
    function get_related_author_posts() {
        global $authordata, $post;
        $currency_sign = get_option('_wpmp_curr_sign','$');
        $pluginurl = plugins_url();
        $authors_posts = get_posts( array( 'author' => $authordata->ID, 'post_type' => "wpmarketplace",'post__not_in' => array( $post->ID ), 'posts_per_page' => 5 ) );

        $output = '<ul class="recentitem">';
        foreach ( $authors_posts as $authors_post ) {
            $imgurl = wp_get_attachment_url( get_post_thumbnail_id($authors_post->ID) );
            $output .= '<li><a title="'.$authors_post->post_title.'" class="preview" rel="'. $pluginurl.'/wpmarketplace/libs/timthumb.php?w=200&zc=1&src='.$imgurl.'" href="' . get_permalink( $authors_post->ID ) . '"><img src="'. $pluginurl.'/wpmarketplace/libs/timthumb.php?w=77&h=50&zc=1&src='.$imgurl.'"  title="aaaaa"  alt="gallery thumbnail"></a></li>';
        }
        $output .= '</ul>';

        return $output;
    }
    
    function get_related_author_posts_theme_market() {
        global $authordata, $post;
        $currency_sign = get_option('_wpmp_curr_sign','$');
        $pluginurl = plugins_url();
        $authors_posts = get_posts( array( 'author' => $authordata->ID, 'post_type' => "wpmarketplace",'post__not_in' => array( $post->ID ), 'posts_per_page' => 5 ) );

        $output = '';
        foreach ( $authors_posts as $authors_post ) {
            $imgurl = wp_get_attachment_url( get_post_thumbnail_id($authors_post->ID) );
            @extract(get_post_meta($authors_post->ID,"wpmp_list_opts",true));
            $output .= '<li><a title="'.$authors_post->post_title.'" href="'. get_permalink( $authors_post->ID ). '"><img src="'. $pluginurl.'/wpmarketplace/libs/timthumb.php?w=110&h=110&zc=1&src='.$imgurl.'"></a><span class="pricetag">'.$currency_sign.$base_price.'</span></li>';
        }
        $output .= '';

        return $output;
    }
    
    

    
}