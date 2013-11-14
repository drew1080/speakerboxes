<div align="center">

<form name="loginform" id="loginform" action="<?php the_permalink(); ?>login/" method="post" class="login-form span4 offset4">

<input type="hidden" name="permalink" value="<?php the_permalink(); ?>" />
<input type="hidden" name="login_form" value="login" />
<h1 class="text-left">Login</h1>
    <?php global $wp_query; if($_SESSION['login_error']!='') {  ?>
        <blockquote class="alert alert-danger" style="width: 310px;text-align: left;">
            <b>Login Failed!</b><br/>
            <?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
        </blockquote>
    <?php } ?>
            <p class="login-username text-left">
                <label for="user_login"><?php echo __("Username","wpmarketplace"); ?></label> 
                <input type="text" name="login[log]" id="user_login" class="input" value="" size="20" tabindex="10" /> 
            </p> 
            <p class="login-password text-left">
                <label  for="user_pass"><?php echo __("Password","wpmarketplace"); ?></label> 
                <input type="password" name="login[pwd]" id="user_pass" class="input" value="" size="20" tabindex="20" /> 
            </p> 
            
            <p class="login-remember text-left"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" /> <?php echo __("Remember Me","wpmarketplace");?></label></p>
            <p class="login-submit text-left">
                <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-success btn-orange" value="Log In" tabindex="100" />
                <input type="hidden" name="redirect_to" value="<?php the_permalink(); ?>" /> 
            </p> 
            <p align='left'>
                <a href='<?php echo site_url('/wp-login.php?action=lostpassword'); ?>' class="delicious" style="font-size: 12pt;"><?php echo __('Forgot Password?','wpmarketplace'); ?></a> &nbsp;
                <a href='<?php echo site_url('/wp-login.php?action=register'); ?>' class="delicious" style="font-size: 12pt;"><i class="icon icon-user"></i> <?php echo __('Register','wpmarketplace'); ?></a><br>
            </p>
            
</form>   
</div>