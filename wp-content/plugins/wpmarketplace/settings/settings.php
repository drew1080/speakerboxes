 <style>
.wrap{
    margin: 0px;
    
}
#footer,
#wpcontent{
    margin-left: 146px;
}
.wrap *{
    font-family: Tahoma;
    letter-spacing: 1px;
}

input[type=text],textarea{
    width:500px;
    padding:5px;
}

input{
   padding: 7px; 
}
.cats li{   
    width:20%;
    float: left;
}

#icon-options-general{
    margin-left: 15px;
}

#wpmp_settings_form{
    padding-left: 20px;
}
 
#message,.updated{
 
-webkit-border-radius: 0px;
-moz-border-radius: 0px;
border-radius: 0px;
border:0px;
} 
</style>
<script type="text/javascript">
function select_my_list(selectid,val){
    var ln=document.getElementById(selectid).options.length;
    for(var i=0;i<ln; i++){
        if(document.getElementById(selectid).options[i].value==val)
            document.getElementById(selectid).options[i].selected = true;                                               }
}

jQuery(function(){
    jQuery('#message').live('click',function(){
        jQuery('#message').slideUp();
    });
});
</script>
 <?php
    $settings = maybe_unserialize(get_option('_wpmp_settings'));
    //echo "<pre>" ; print_r($settings); echo "</pre>";
?>
<!--[if IE]>
<style>
ul#navigation { 
border-bottom: 1px solid #999999;
}
</style>
<![endif]-->
<div class="wrap">
    


<header>
  <div class="icon32" id="icon-options-general"><br></div><h2><?php echo __("Marketplace Settings","wpmarketplace");?> <img style="display: none;" id="wdms_loading" src="images/loading.gif" /></h2>
</header>

<nav> 
    <ul>
        <li class="selected"><a href="#tab1"><?php echo __("Basic Settings","wpmarketplace");?></a></li>
        <li><a href="#tab2"><?php echo __("Payment Options","wpmarketplace");?></a></li>         
        <li><a href="#tab3"><?php echo __("Shipping Options","wpmarketplace");?></a></li>         
        <li><a href="#tab4"><?php echo __("Stock","wpmarketplace");?></a></li>         
        <li><a href="#tab5"><?php echo __("Tax","wpmarketplace");?></a></li>         
        
    </ul>
</nav>
 
<div class="updated" style="padding: 5px;display: none;" id="message"></div>
<form method="post" id="wpmp_settings_form">
<section class="tab" id="tab1">
<?php include_once("basic_settings.php");?>
</section>
<section class="tab" id="tab2"> 
<?php include_once("payment_options.php");?>
</section>
<section class="tab" id="tab3"> 
<?php include_once("shipping_options.php");?>
</section>
<section class="tab" id="tab4"> 
<?php include_once("stock_options.php");?>
</section>
<section class="tab" id="tab5"> 
<?php include_once("tax_options.php");?>
</section>
 <br>
<br>


<input type="reset" value="Reset" class="button button-secondary button-large" >
<input type="submit" value="Save Settings" class="button button-primary button-large" >   
<img style="display: none;" id="wdms_saving" src="images/loading.gif" />
</form>
<br>
 
</div>

<script type="text/javascript">

jQuery(document).ready(function(){
    
    jQuery('#wpmp_settings_form').submit(function(){
       
       jQuery(this).ajaxSubmit({
        url:ajaxurl,
        beforeSubmit: function(formData, jqForm, options){
          jQuery('#wdms_saving').fadeIn();  
        },   
        success: function(responseText, statusText, xhr, $form){
          jQuery('#message').html("<p>"+responseText+"</p>").slideDown();
          //setTimeout("jQuery('#message').slideUp()",4000);
          jQuery('#wdms_saving').fadeOut();  
          jQuery('#wdms_loading').fadeOut();  
          window.setTimeout('location.reload()', 1000);
        }   
       });
        
       return false; 
    });
    
   
});
 
</script>
