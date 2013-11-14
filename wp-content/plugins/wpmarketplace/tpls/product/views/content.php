<?php

$cnt = do_shortcode(wpautop($post->post_content));
$feature = featurelist_frontend($post->ID); 
$content = ' <br><br>
 
<ul class="nav nav-tabs" id="wpmp-tabs">
              <li class="active"><a data-toggle="tab" href="#desc">'.__('Description','wpmarketplace').'</a></li>
              <li><a data-toggle="tab" href="#fet">'.__('Features','wpmarketplace').'</a></li>
               
</ul> 

<div id="tab-content" class="tab-content">
 <div class="tab-pane active" id="desc">'.$cnt.'</div>
<div class="tab-pane" id="fet">'.$feature.'</div>
</div>
 
  
';
