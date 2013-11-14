
    jQuery(function() { 
        if(pagenow=='wpmarketplace_page_settings') {
        jQuery( "#paccordion" ).accordion({
            autoHeight: false,
            navigation: true});
            
        jQuery( "#paccordion1" ).accordion({
            autoHeight: false,
            navigation: true
        });
        }
            
            jQuery('#add_currency').live("click",function(){
                var tm=new Date().getTime();
                
                
                
               jQuery('#currency_table').append('<tr id="currency_row_'+tm+'"><td><span id="w8c_'+tm+'" style="position: absolute; text-decoration: blink; margin-left: 20px; display: none;">Saving...</span><input type="radio" name="currency_radio" class="currency_radio" id="'+tm+'"></td><td><input type="text" name="_wpmp_settings[currency]['+tm+'][currency_name]" id="c_n_'+tm+'" value="'+jQuery('#currency_n').val()+'" class="currency_name"></td><td><input  class="currency_symbol" type="text" name="_wpmp_settings[currency]['+tm+'][currency_symbol]" id="c_s_'+tm+'" value="'+jQuery('#currency_s').val()+'"></td><td><a href="#" class="del_currency" id="'+tm+'">Delete</a></td></tr>'); 
               jQuery('#currency_n').val("")  ;
               jQuery('#currency_s').val("");
            });
            
            jQuery('.del_currency').live("click",function(){
               
                var id = jQuery(this).attr("id");
                
                jQuery.post(ajaxurl,{action:"wpmp_default_currency_del",currency_name:jQuery('#c_n_'+jQuery(this).attr("id")).val(),currency_value:jQuery('#c_s_'+jQuery(this).attr("id")).val(),currency_key:jQuery(this).attr("id")},
                function(res){
                    jQuery('#currency_row_'+id).remove(); 
                });
                
                 
                
               //jQuery('#currency_row_'+jQuery(this).attr("id")).remove(); 
               //ajax call to delete from option
               
            });
            
           
            
            jQuery('.currency_radio').live("click",function(){
                var ccid = '#w8c_'+this.id;
                jQuery(ccid).fadeIn();
                jQuery.post(ajaxurl,{action:"wpmp_default_currency",currency_name:jQuery('#c_n_'+jQuery(this).attr("id")).val(),currency_value:jQuery('#c_s_'+jQuery(this).attr("id")).val(),currency_key:jQuery(this).attr("id")},function(res){jQuery(ccid).fadeOut();});
            });
            
            //for global low stock checkbox click function
            jQuery('.global_low_stock').click(function(){
                global_low_stock();
            });
            //check the stock checkbox status while page load first time
            global_low_stock();
            
            //check the low stock checkbox status
            function global_low_stock(){
                if(jQuery('.global_low_stock').attr("checked")){
                    jQuery('#low_stock_row').fadeIn();
                }else{
                    jQuery('#low_stock_row').fadeOut();
                }
            }
            
            //function for showing/hiding the stock quantity in add product
            jQuery('#mng_stock').click(function(){
                manage_stock();
            });
            
            manage_stock();
            
            function manage_stock(){
                if(jQuery('#mng_stock').attr("checked")){
                    jQuery('#stk_qty').fadeIn();
                }else{
                    jQuery('#stk_qty').fadeOut();
                }
            }
            
            
            
    });
    