<?php
//show list...
//add new item to list...
if(isset($_REQUEST['list']) && $_REQUEST['list']==2){
    require_once WPMP_BASE_DIR . "featureset/add_list_items.php";
}
else{
    require_once WPMP_BASE_DIR . "featureset/show_list_items.php";
}