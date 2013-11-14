<?php
if(isset($digital_activate)){ 
if($demo_site!=''||$demo_admin!=''){
$demo = <<<DEMO
<div class='prices' style='margin-top:15px'>
<b>Demo Info:</b> <br/>
<ul>
DEMO;

if($demo_site!='')
$demo .="<li>".__("Front-end","wpmarketplace")." <a href='{$demo_site}'>".__("Enter","wpmarketplace")."</a></li>";

if($demo_admin!=''){
$demo .= "<li>".__("Admin","wpmarketplace")." <a href='{$demo_admin}'>".__("Enter","wpmarketplace")."</a></li>";
$demo .= "<li><span>".__("Username:","wpmarketplace")."</span>{$demo_username}</li>";
$demo .= "<li><span>".__("Password:","wpmarketplace")."</span>{$demo_password}</li>";
}


$demo .= <<<PRICE
</ul>
</div>
PRICE;
}  
}
