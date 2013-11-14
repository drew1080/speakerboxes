<div align="center"> 
<form method="post" action="<?php echo home_url(); ?>/wp-login.php?action=lostpassword" id="lostpasswordform" name="lostpasswordform" class="login-form">
<input type="hidden" name="permalink" value="<?php the_permalink(); ?>" />
    <h2><?php echo __("Forgot Password?","wpmarketplace");?></h2>
    <p>
        <label><?php echo __("Username or E-mail:","wpmarketplace");?><br>
        <input type="text" tabindex="10" size="20" value="" class="input" id="user_login" name="user_login"></label>
    </p>
    <input type="hidden" value="" name="redirect_to">
    <p class=""><input type="submit" tabindex="100" value="Get New Password" class="submit" id="wp-submit" name="wp-submit"></p>
</form>
</div>