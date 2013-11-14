<?php
    //stock reduce function
    function wpmp_reduce_stock($orderid=0){
        $settings = maybe_unserialize(get_option('_wpmp_settings'));
        if($settings['stock']['enable']==1){
            if($orderid==0)$orderid=$_POST['order_id'];
            $order = new Order();
            //$order1 = $order->GetOrder($orderid);
            $order_items = $order->GetOrderItems($orderid);
            //print_r($order_items);
            foreach($order_items as $product_info){
                $post_meta=array();
                $post_meta=get_post_meta($product_info['pid'],"wpmp_list_opts",true);
                //print_r($post_meta);
                if($post_meta['manage_stock']==1){
                    $quant=($post_meta['stock_qty']-$product_info['quantity']);
                    if($quant>=0)
                        $post_meta['stock_qty']=$quant;
                    else
                        $post_meta['stock_qty']=0;
                    update_post_meta($product_info['pid'],"wpmp_list_opts",$post_meta);
                }
            }
            die("Stock Reduced");
        }else{
            die("Stock not enabled");
        }
    }
    
    //stock restore function
    
    function wpmp_restore_stock($orderid=0){
        
        $settings = maybe_unserialize(get_option('_wpmp_settings'));
        if($settings['stock']['enable']==1){
        
            if($orderid==0)$orderid=$_POST['order_id'];
            $order = new Order();
            $order = $order->getOrder($orderid);
            $order->items = unserialize($order->items);
            //print_r($order->items);
            foreach($order->items as $pid=>$product_info){
                $post_meta=array();
                $post_meta=get_post_meta($pid,"wpmp_list_opts",true);
                $post_meta['stock_qty']=($post_meta['stock_qty']+$product_info['quantity']);
                update_post_meta($pid,"wpmp_list_opts",$post_meta);
            }
            die("Stock Restored");
        }else{
            die("Stock not enabled");
        }
    }
    
    
    //return the stock of the product
    function wpmp_get_stock($productid=0){
        $settings = maybe_unserialize(get_option('_wpmp_settings'));
        if($settings['stock']['enable']==1){
            
            
        }else{
        die(__("Stock not enabled","wpmarketplace"));
        }
        return ;
    }
?>