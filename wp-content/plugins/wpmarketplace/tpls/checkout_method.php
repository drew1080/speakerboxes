
<?php
global $sap; 
    wp_enqueue_script('jquery-validate',plugins_url("wpmarketplace/js/jquery.validate.js"));
?>
        <div class="step-title">
            
            <h2><?php echo __("Register / Login","wpmarketplace");?></h2>
            
        </div>
        <div style="<?php if($current_user->ID)echo "display:none";else echo "";?>" class="step a-item" id="csl">
            <div class="row-fluid">
        <div class="span6">
        <h4><?php echo __("Register","wpmarketplace");?></h4>
       
            <p><?php echo __("Register with us for future convenience:","wpmarketplace");?></p>
            <div style="display: none;" id="rloading_first"><img  src="<?php echo home_url();?>/wp-admin/images/loading.gif" /></div><div id="rloading_message"></div>
                <form method="post" action="<?php the_permalink(); echo $sap; ?>checkout_register=register" id="registerform" name="registerform" >
                    <input type="hidden" name="permalink" value="<?php the_permalink(); ?>" />
                    <ul class="form-list">
                        <li>
                            <label ><?php echo __("Username","wpmarketplace"); ?></label>
                            <input type="text"  class="input-text required" id="registerform_user_login" value="<?php echo $_SESSION['tmp_reg_info']['user_login']; ?>" name="reg[user_login]">
                        </li>
                        <li>
                            <label ><?php echo __("E-mail","wpmarketplace"); ?></label>
                            <input type="text" class="input-text required email" id="registerform_user_email" value="<?php echo $_SESSION['tmp_reg_info']['user_email']; ?>" name="reg[user_email]">
                        </li>
                        <li>
                        
                            <label ><?php echo __("Password","wpmarketplace"); ?></label>
                            <input type="password" class="input-text required " id="registerform_user_pass" value="<?php echo $_SESSION['tmp_reg_info']['user_email']; ?>" name="reg[user_pass]">
                        </li>
                    </ul>
    

<div style="clear: both;"></div>              


            <b><?php echo __("Register and save time!","wpmarketplace");?></b>
            <p><?php echo __("Register with us for future convenience:","wpmarketplace");?></p>
            <ul class="ul">
                <li><?php echo __("Fast and easy check out","wpmarketplace");?></li>
                <li><?php echo __("Easy access to your order history and status","wpmarketplace");?></li>
            </ul>
             <button id="register_btn" class="btn btn-success" type="submit"><?php echo __("Continue","wpmarketplace");?></button>
             <div id="rmsg"></div>
           </form>
            </div>
    <div class="span6">
        <h4><?php echo __("Login","wpmarketplace");?></h4>
        <p><?php echo __('if you arelady have an account login here:',"wpmarketplace"); ?></p>
        <div style="display: none;" id="loading_first"><img  src="<?php echo home_url();?>/wp-admin/images/loading.gif" /></div><div id="loading_message"></div>
                <form name="loginform" id="loginform" action="<?php the_permalink(); echo $sap; ?>&task=login" method="post" class="login-form" > 

<input type="hidden" name="permalink" value="<?php the_permalink(); ?>" />
<h1><?php echo __("Login","wpmarketplace");?></h1>
             <ul class="form-list">
                <li>
                <label  for="user_login"><?php echo __("Username","wpmarketplace"); ?></label> 
                <input type="text" name="login[log]" id="loginform_user_login" class="input-text required" value="" size="20" /> 
             
             </li>
             <li>
                <label  for="user_pass"><?php echo __("Password","wpmarketplace"); ?></label> 
                <input type="password" name="login[pwd]" id="loginform_user_pass" class="input-text required" value="" size="20" /> 
             </li>
             </ul>
            
            <p class="login-remember"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php echo __("Remember Me","wpmarketplace");?></label></p> 
            
            <p class="login-submit"> 
                <button type="submit" name="wp-submit" id="loginbtn" class="btn btn-success" ><?php echo __("Log In","wpmarketplace");?></button> 
                <div id="lmsg"></div>
                <input type="hidden" name="redirect_to" value="<?php the_permalink(); ?>" /> 
            </p> 
            <p>  <br>
            <a href="<?php echo home_url('wp-login.php?action=lostpassword'); ?>"><?php echo __('Forgot password?',"wpmarketplace"); ?></a>
 
<div style="clear: both;"></div>              
            
</form> 
    </div>
</div>
            <div style="clear: both;"></div>
            
        </div>
        
       
    
