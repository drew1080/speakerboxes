<?php
    //Show Images
    include("views/images.php");
    include("views/prices.php");
    include("views/content.php");
    include("views/demo.php");

    
    $pid = get_the_ID();//postid  
        $user_id = get_current_user_id();
        //$host="localhost";
        $cnt = get_post_meta($pid,"post_rating",true);
        $count=0;
        $sum=0;
        if($cnt){
         foreach($cnt as $val){
             $count++;
              $sum+=$val;
         }
        }
         $avg=0;
         if($count>0)
         $avg=($sum/$count);
         $star1=$star2=$star3=$star4=$star5=0;
         if($avg>=0)$star1=" star_on";
         if($avg>=1.4)$star2=" star_on";
         if($avg>=2.4)$star3=" star_on";
         if($avg>=3.4)$star4=" star_on";
         if($avg>=4.4)$star5=" star_on";
        //$cnt = $cnt?$cnt:'0';
        //$reactions .= "<div class='btn_outer'><a onclick='react(\"$r\", \"$iid\",\"$id\")' class='reactions btn_left simplemodal-login' href='#inline-content'>$r</a><span id='c_{$id}_{$iid}' class='btn_right'>$cnt</span><div style='clear:both'></div></div>";
        //$reactions .= ' <a href="#inline-content" class="'.$class.'"><span class="btn-left '.$style.'-left" onclick="react(\''.$r.'\', \''.$iid.'\',\''.$id.'\')">'.$r.'</span><span class="btn-right '.$style.'-right" id="c_'.$id.'_'.$iid.'" ><i></i>'.$cnt.'</span></a>';
        /*$rating =<<<RATE
        <div id="rating_area">
         Rate this,  
        <a href='#' id='star1' class="star star1 $star1" rate_value="1" post="$pid" >1</a>
        <a href='#' id='star2' class="star star2 $star2"  rate_value="2" post="$pid">2</a>
        <a href='#' id='star3' class="star star3 $star3"  rate_value="3" post="$pid">3</a>
        <a href='#' id='star4' class="star star4 $star4"  rate_value="4" post="$pid">4</a>
        <a href='#' id='star5' class="star star5 $star5"  rate_value="5" post="$pid">5</a>
         Average <span id="average_vote">$avg</span> , Votes <span id="total_vote">$count</span>
         <div class="clear"></div>
        </div>
                                                             
RATE;            */
    

$content1 = <<<CONT
<div class="wp-marketplace">
<div class="container-fluid wpmp-product-details">
<div class="row-fluid">
<div class="span7">
{$previews}
</div>
<div class="span5">
{$prices}{$demo}
</div>  
</div>  
<div class="row-fluid">
<div class="span12">
{$content}
</div>
</div>

</div>
</div>
 
CONT;



?>