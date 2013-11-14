<?php

$deposit_data = do_shortcode('[wpmp-prepaid-credits]');

if($deposit_data=='[wpmp-prepaid-credits]') $deposit_data = "You can use prepaid credit add-on here";

$sitename = get_bloginfo('sitename');

$data =<<<WPMPDB

<div class='row-fluid wpmp-dashboard'>

<div class='span6'>
<div class='well' align=center>
<h3>Welcome to $sitename</h3>
</div>
</div>
<div class="span6">
<div class="well">
{$deposit_data}
</div>
</div>

</div>


WPMPDB;
