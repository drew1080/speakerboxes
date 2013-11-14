<div align="center"> 
<?php global $wp_query; if($_SESSION['reg_error']!=''&&$wp_query->query_vars['wpedentask']=='register') {  ?>
<blockquote class="error" style="width: 260px;text-align: left;">
<b><?php echo __("Registration Failed!","wpmarketplace");?></b><br/>
<?php echo $_SESSION['reg_error']; ?>
</blockquote>   
<?php } ?>

<form method="post" action="<?php the_permalink(); ?>register/" id="registerform" name="registerform" class="login-form">
<input type="hidden" name="permalink" value="<?php the_permalink(); ?>" />
<input type="hidden" name="register_form" value="register" />
<h1>Register</h1>
    <p>
        <label><?php echo __("Username","wpmarketplace");?><br>
        <input type="text" tabindex="10" size="20" class="input" id="user_login" value="<?php echo $_SESSION['tmp_reg_info']['user_login']; ?>" name="reg[user_login]"></label>
    </p>
    <p>
        <label><?php echo __("E-mail","wpmarketplace");?><br>
        <input type="text" tabindex="20" size="25" class="input" id="user_email" value="<?php echo $_SESSION['tmp_reg_info']['user_email']; ?>" name="reg[user_email]"></label>
    </p>
    <p id="reg_passmail"><?php echo __("A password will be e-mailed to you.","wpmarketplace");?></p>
    <br class="clear">
    <input type="hidden" value="" name="redirect_to">
    <p class=""><input type="submit" tabindex="100" value="Register" class="reg" id="wp-submit" name="wp-submit"></p>
    <p>     <br>
    
            <a href='<?php the_permalink(); ?>/login/' class="delicious" style="font-size: 12pt;"><?php echo __("Already have account? Login","wpmarketplace");?></a><br>            
            
            </p>
</form>
</div>