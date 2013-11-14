<?php
global $payment_methods;
$payment_methods = apply_filters('payment_method', $payment_methods);
?>
<div id="gateway-order" class="section" style="display: block;">                        
    <h3><?php echo __("Payment Gateways","wpmarketplace");?></h3>
        <p><?php echo __("Your activated payment gateways are listed below.","wpmarketplace");?> </p>
                        <table cellspacing="0" class="wc_gateways widefat">
                            <thead>
                                <tr>
                                    <th width="1%">Default</th>
                                    <th><?php echo __("Gateway","wpmarketplace");?></th>
                                    <th><?php echo __("Status","wpmarketplace");?></th>
                                </tr>
                            </thead>
                            <tbody class="ui-sortable">
                            <?php
                           foreach($payment_methods as $payment_method){  
                            if(class_exists($payment_method)){
                                        ?>
                                <tr>
                                        <td width="1%" class="radio">
                                            <input type="radio" value="cheque" name="default_gateway">
                                            
                                        </td>
                                        <td>
                                            <p><strong><?php echo ucfirst($payment_method);?></strong><br>
                                            
                                        </td>
                                        <td><?php if(isset($settings[$payment_method]['enabled']))echo __("Active","wpmarketplace");else echo __("Inactive","wpmarketplace");?></td>
                                    </tr>
                                    <?php
                                    }
                                }
                           
                        ?>
                                    
                                    
                                                               </tbody>
                        </table>
                        </div>
                        <div style="clear: both;margin-top:20px ;"></div>
                        
 <h3><?php echo __("Payment Methods Configuration","wpmarketplace");?></h3>
                        <div id="paccordion" class="wpmppgac">
                        <?php
                         
                          
                         foreach($payment_methods as $payment_method){  
                            if(class_exists($payment_method)){
                                //echo $pdir.$methods[$j];
                                //include_once($pdir.$methods[$j]."/class.".$methods[$j].".php");
                                $obj=new $payment_method();
                                echo '<h3><a href="#">'.ucfirst($payment_method).'</a></h3>';
                                echo "<div>";
                                echo $obj->ConfigOptions();
                                echo '</div>';
                            }
                         }
                                
                        ?>


                        </div>
                        
                        <br /><br />
                        <h3><?php echo __("Currency Configuration","wpmarketplace");?></h3>
                        <div id="paccordion1">
                        <h3><a href="#"><?php echo __("Currency","wpmarketplace");?></a></h3>
                        <div>
                        <?php 
                        //$currencies = get_option('wpmp_currencies');print_r($currencies);
                        $currencies = $settings['currency']; //print_r($currencies);
                        $currency_key = get_option('_wpmp_curr_key');
                        //echo $currency_key;
                        ?>
                        <table id="currency_table" width="50%" border="0" class="currency_table wc_gateways widefat"><thead>
                        <tr><th><?php echo __("Default","wpmarketplace");?></th><th><?php echo __("Currency Code","wpmarketplace");?></th><th><?php echo __("Currency Symbol","wpmarketplace");?></th><th><?php echo __("Action","wpmarketplace");?></th></tr>
                        </thead>
                        <tbody>
                        <?php
                            
                            
                            if($currencies){
                                //echo '';
                                foreach($currencies as $key=> $currency){
                                    if($key==$currency_key)$select='checked="checked"';else $select="";
                                    echo '<tr id="currency_row_'.$key.'"><td><span id="w8c_'.$key.'" style="position:absolute;display:none;text-decoration:blink;margin-left:20px;">Saving...</span><input type="radio" '.$select.' name="currency_radio" class="currency_radio" id="'.$key.'"></td><td><input id="c_n_'.$key.'" type="text" name="_wpmp_settings[currency]['.$key.'][currency_name]" value="'.$currency['currency_name'].'" class="currency_name"></td><td><input id="c_s_'.$key.'" class="currency_symbol" type="text" name="_wpmp_settings[currency]['.$key.'][currency_symbol]" value="'.$currency['currency_symbol'].'"></td><td><a href="#" class="del_currency" id="'.$key.'">'.__("Delete","wpmarketplace").'</a></td></tr>';
                                }
                            }else{
                                
                            }
                        ?>
                        </tbody>
                        </table>
                        <br>
                        <br>
                        
                        <?php echo __("Currency Code:","wpmarketplace");?>
                        <input type="text" id="currency_n" class="currency_name">
                        <?php echo __("Currency Symbol:","wpmarketplace");?>
                         <input type="text" id="currency_s" class="currency_symbol">
                        
                       <input type="button" id="add_currency" value="Add" class="button"> 
                        <span id="loadingc" style="display: none;"><img src="images/loading.gif" alt=""> saving...</span>                
                        </div>
                        
                        </div>
                        
                        