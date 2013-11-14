<?php
    
function wpmp_js(){   
    ?>
    <script language="JavaScript">
    <!--
      jQuery(function(){
          
          
          var star1c = jQuery('#star1').attr('class');
          var star2c = jQuery('#star2').attr('class');
          var star3c = jQuery('#star3').attr('class');
          var star4c = jQuery('#star4').attr('class');
          var star5c = jQuery('#star5').attr('class');
       
          jQuery('.star').click(function(){
              jQuery.post('<?php echo home_url();?>/?rate='+jQuery(this).attr('rate_value')+'&post_id='+jQuery(this).attr('post'),function(res){
                  //...
                   
                  var average = res.average;
                  var total = res.total;
                
                   jQuery('#average_vote').text(average);
                   jQuery('#total_vote').text(total);
                   //alert(parseInt(str1[1]));
                  
                   
                   /*for(var i=1;i<=parseInt(average);i++){
                       
                       jQuery('#star'+i).addClass('star_on');
                        
                   } 
                    for(var i=(parseInt(average)+1);i<=5;i++){
                       jQuery('#star'+i).removeClass('star_on');
                       
                   }     */
                     
                    star1c = "star star1 "+((average>=0)?'star_on':''); //jQuery('#star1').attr('class');
                    star2c = "star star2 "+((average>=1.4)?'star_on':''); //jQuery('#star1').attr('class');
                    star3c = "star star3 "+((average>=2.4)?'star_on':''); //jQuery('#star1').attr('class');
                    star4c = "star star4 "+((average>=3.4)?'star_on':''); //jQuery('#star1').attr('class');
                    star5c = "star star5 "+((average>=4.4)?'star_on':''); //jQuery('#star1').attr('class');
                    /*star2c = jQuery('#star2').attr('class');
                    star3c = jQuery('#star3').attr('class');
                    star4c = jQuery('#star4').attr('class');
                    star5c = jQuery('#star5').attr('class');
                                                                  */
                  //alert(res);
              })
           return false;
          });
          var prev_stat = new Array();
          jQuery('.star').mouseover(function(){
              
              for(var i=0;i<parseInt(jQuery(this).attr('rate_value'));i++){
                  
                  if(jQuery('.star'+i).hasClass('star_on')) { //prev_stat[i] = 1; 
                  }
                  else {//prev_stat[i] = 0;    
                  jQuery('.star'+i).addClass('star_on');   
                  }
                  
              }
              
              for(var i=parseInt(jQuery(this).attr('rate_value'));i<=5;i++){
                  
                  if(jQuery('.star'+i).hasClass('star_on')) { 
                     // prev_stat[i] = 1;  
                     jQuery('.star'+i).removeClass('star_on');   }
                  else {//prev_stat[i] = 0;  
                  }
                  
              }  
              
          });
          
          jQuery('.star').mouseout(function(){
              
              for(var i=0;i<parseInt(jQuery(this).attr('rate_value'));i++){
                  var prev_stat = new Array();
                  if(jQuery('.star'+i).hasClass('star_on')) { prev_stat[i] = 1; }
                  else {prev_stat[i] = 0;    jQuery('.star'+i).addClass('star_on');    }
                  
              }
              
          });  
          
         
         jQuery('#rating_area').mouseout(function(){
            
            jQuery('#star1').removeAttr('class').attr('class',star1c);
            jQuery('#star2').removeAttr('class').attr('class',star2c);
            jQuery('#star3').removeAttr('class').attr('class',star3c);
            jQuery('#star4').removeAttr('class').attr('class',star4c);
            jQuery('#star5').removeAttr('class').attr('class',star5c);
          
      }); 
          
      
      });
      
      
    //-->
    </script>
    <?php
}

function wpmp_addrating(){
      global $current_user; 
      //echo $_REQUEST['rate']; 
      if(isset($_REQUEST['rate']) && $_REQUEST['rate']){
        $msg=""; 
         //delete_post_meta($_REQUEST['post_id'],"post_rating");
         $rate=get_post_meta($_REQUEST['post_id'],"post_rating",true);
         $sum=0;
         $count=0;
         $rate[$current_user->ID]= $_REQUEST['rate'];
         $url[$current_user->ID]= $_SERVER['REQUEST_URI'];
         
         update_post_meta($_REQUEST['post_id'],"post_rating",$rate);
         foreach($rate as $key=>$value){
             $count++;
             $sum+=$value;
             /*if($key==$current_user->ID){
                 $flag=1;break; 
             } */
         }
         /*if($flag==0){
              $rate[$current_user->ID]= $_REQUEST['rate'];
              update_post_meta($_REQUEST['post_id'],"post_rating",$rate);
              $msg= "Rating Saved";
         }else
            $msg= "You already rated this.";  */
         
         $avg_rate=number_format($sum/$count,1);
          $data['average'] = $avg_rate;
          $data['total'] = $count;
          //save the average rating for the products to show the top rate products easily
          update_post_meta($_REQUEST['post_id'],"avg_rating",$avg_rate);
          
            header("Content-type: application/json");
            die(json_encode($data)); 
    }
    
       

}


add_action("init",'wpmp_addrating');
add_action('wp_head','wpmp_js');
?>