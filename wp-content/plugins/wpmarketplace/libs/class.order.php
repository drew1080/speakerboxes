<?php
    class Order{

        var $oid;
        function Order($oid=''){
            if($oid)
                $this->oid = $oid;
        }

        function NewOrder($id, $title, $items, $total, $userid, $order_status = 'processing', $payment_status = 'processing',$cart_data='',$order_notes="",$payment_method="",$shipping_method="",$shipping_cost=0.0,$billing_shipping_data=""){
            global $wpdb, $current_user;        
            get_currentuserinfo();

            $wpdb->insert("{$wpdb->prefix}mp_orders",array('order_id'=>$id, 'title'=>$title,'date'=>time(), 'items'=> $items,'total'=> $total, 'order_status'=>$order_status, 'payment_status'=> $payment_status, 'uid'=> $userid,'cart_data'=>$cart_data,'uid'=>$userid,'order_notes'=>$order_notes,'payment_method'=>$payment_method,'shipping_method'=>$shipping_method,'shipping_cost'=>$shipping_cost,'billing_shipping_data'=>$billing_shipping_data));                

            return $id;
        }

        static function Update($data, $id){
            global $wpdb;
            $res=$wpdb->update("{$wpdb->prefix}mp_orders",$data,array('order_id'=>$id));         
            return $res;
        }

        static function complete_order($id, $email_notify = true){
            
            global $wpdb;
            
            self::Update(array('order_status'=>'Completed','payment_status'=>'Completed'), $id);

            //return if email notification set to false
            if($email_notify==false) return;

            // send email notifications
            $siteurl=home_url("/");           
            $from=home_url("/");
            $userid=$wpdb->get_var("select uid from {$wpdb->prefix}mp_orders where order_id='".$id."'");
            $user_info = get_userdata($userid);
            $admin_email=get_bloginfo("admin_email");
            $email = array();
            $subject="New Order Confirmation";
            $message="An order is made to {$siteurl}<br/> Order Id: ".$id."<br/>Customer Name: ".$user_info->display_name."<br/>Customer Email: ".$user_info->user_email;
            $email['subject']=$subject;
            $email['body']=$message;
            $email['headers'] = 'From:  '.$admin_email.'' . "\r\nContent-type: text/html\r\n";
            $email = apply_filters("order_confirmation_email_buyer", $email);    
            // to buyer
            wp_mail($user_info->user_email,$email['subject'],$email['body'],$email['headers']);   
            // to admin     
            wp_mail($admin_email,$email['subject'],$email['body'],$email['headers']);
            // to seller(s)
            $items = Order::GetOrderItems($id);
            foreach($items as $item){
                $product = get_post($item['pid']); 
                $udata = get_userdata($product->post_author); 
                $seller_emails[$product->post_author] = $udata->user_email;
                $product_by_seller[$product->post_author][] = "<a href='".get_permalink($product->ID)."'>{$product->post_title}</a>";
            }

            //confirm seller email content
            $subject="New Order Confirmation";
            $message="An order is made to {$siteurl}.\n OrderId is ".$id."<br/>Product List:<br/>[plist]<br/>Customer Name: ".$user_info->user_firstname." ".$user_info->lastname."<br/>Customer Email: ".$user_info->user_email;
            $email['subject']=$subject;
            $email['body']=$message;
            $email['headers'] = 'From:  '.$admin_email.'' . "\r\nContent-type: text/html\r\n";
            $email = apply_filters("order_confirmation_email_seller", $email);    
            //send email
            foreach($seller_emails as $id=>$seller_email){
                $prods = implode("<br/>", $product_by_seller[$id]);
                wp_mail($seller_email,$email['subject'],str_replace("[plist]",$prods,$email['body']),$email['headers']);
            }

        }

        static function CancelOrder($id){
            self::Update(array('order_status'=>'Cancelled','payment_status'=>'Cancelled'), $id);
        }

        static function UpdateOrderItems($cart_data, $id){
            global $wpdb;
            $cart_data = maybe_unserialize($cart_data);
            $wpdb->query("delete from {$wpdb->prefix}mp_order_items where oid='$id'");
            if(!empty($cart_data))
                foreach($cart_data as $pid=>$cdt){
                    extract($cdt);             
                    $wpdb->insert("{$wpdb->prefix}mp_order_items",array('oid'=>$id,'pid'=>$pid,'quantity'=>$quantity,'license'=>$license,'price'=>$price,'coupon'=>$coupon,'coupon_amount'=>floatval($coupon_amount)));             

            }
        }

        static function GetOrderItems($id){
            global $wpdb;
            $items = $wpdb->get_results("select * from {$wpdb->prefix}mp_order_items where oid='{$id}'",ARRAY_A);
            return is_array($items)?$items:array();
        }



        function CalcOrderTotal($oid){
            global $wpdb;
            global $current_user;
            get_currentuserinfo();
            $role = $current_user->roles[0];
            $role = $role?$role:'guest';

            $total = 0;
            $orderdata = $this->GetOrder($oid);
            $cart_items = unserialize($orderdata->cart_data);
            $discount1= 0;
            if(is_array($cart_items)){

                foreach($cart_items as $pid=>$item)    {
                    $prices=0;
                    @extract(get_post_meta($pid,"wpmp_list_opts",true));
                    if($item['variation']){
                        foreach($variation as $key=>$value){
                            foreach($value as $optionkey=>$optionvalue){
                                if($optionkey!="vname"){
                                    foreach($item['variation'] as $var){                   
                                        if($var==$optionkey){
                                            $prices+=$optionvalue['option_price'];

                                        }
                                    }    
                                }
                            }
                        }     
                    }
                    if($item['coupon']){
                        $valid_coupon=check_coupon($pid,$item['coupon']);
                        if($valid_coupon!=0){

                            $total +=  (($item['price']+$prices)*$item['quantity'])-(($item['price']+$prices)*$item['quantity']*($valid_coupon/100));
                        } 
                    }else
                        $total +=  (($item['price']+$prices)*$item['quantity']);

                    //calculate discount
                    $discount1 += (($item['price']*$item['quantity']*$discount[$role])/100);
            }}

            $total = apply_filters('wpmp_cart_subtotal',$total);

            $subtotal=$total;


            $total += $orderdata->shipping_cost;

            $tax_summery=$this->wpmp_calculate_tax();

            $tax = 0;
            if(count($tax_summery)>0){
                foreach($tax_summery as $taxrow){
                    $tax += $taxrow['rates'];
                }
            }
            $total += $tax;


            $total = $total-$discount1;

            return $total;
        }

        function wpmp_calculate_tax(){

            $taxr=array();
            $settings = maybe_unserialize(get_option('_wpmp_settings'));

            if(isset($_SESSION['orderid'])) $order_info=$this->GetOrder($_SESSION['orderid']);
            $bdata=unserialize($order_info->billing_shipping_data);
            if(isset($_SESSION['orderid'])) $cart_items = $this->GetOrderItems($_SESSION['orderid']);
            if(isset($settings['tax']['enable']) && $settings['tax']['enable']==1){
                if(is_array($cart_items)){

                    foreach($cart_items as $item)    {
                        $taxes=0;
                        $tax_status="";
                        $tax_class="";
                        @extract(get_post_meta($item['pid'],"wpmp_list_opts",true));

                        if($tax_status=="taxable"){

                            if($settings['tax']['tax_rate']){
                                $temp_class="";
                                $temp_label="";
                                $taxes=0;
                                foreach($settings['tax']['tax_rate'] as $key=> $rate){


                                    if($rate['tax_class']==$tax_class){
                                        $taxes=0;
                                        if(in_array($bdata['shippingin']['country'], $rate['country'])){
                                            if($sales_price) $base_price = $sales_price;
                                            $taxes = (($rate['rate']*$base_price)/100);
                                            if($rate['shipping']==1){
                                                $taxes += (($rate['rate']*$order_info->shipping_cost)/100);
                                            }
                                            //product wise tax
                                            $taxr['label'][$item['pid']][]= $rate['label'];
                                            $taxr['rate'][$item['pid']]+= $taxes;     
                                            //class wise tax
                                            $tax_summery[$key]['label'] = $rate['label'];
                                            $tax_summery[$key]['rates'] += $taxes;

                                        }

                                    }

                                }
                            }

                        }


                    }
                }
            }



            return $tax_summery;
        }

        function GetOrder($id) {
            global $wpdb;
            $id = addslashes($id);
            return $wpdb->get_row("select * from {$wpdb->prefix}mp_orders where order_id='$id'");
        }  

        function GetOrders($id) {
            global $wpdb;
            return $wpdb->get_results("select * from {$wpdb->prefix}mp_orders where uid='$id' order by `date` desc");
        }

        function GetAllOrders($qry="",$s=0, $l=20) {
            global $wpdb;
            return $wpdb->get_results("select * from {$wpdb->prefix}mp_orders $qry order by `date` desc limit $s,$l");
        }

        function totalOrders($qry=''){
            global $wpdb;
            return $wpdb->get_var("select count(*) from {$wpdb->prefix}mp_orders $qry");
        }

        function Delete($id){
            global $wpdb;
            return $wpdb->query("delete from {$wpdb->prefix}mp_orders where order_id='$id'");
        }


}