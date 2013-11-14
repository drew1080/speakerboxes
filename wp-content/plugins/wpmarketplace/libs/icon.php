<style type="text/css">
.wdmiconfile{    
    -webkit-border-radius: 6px;
    -moz-border-radius: 6px;
    border-radius: 6px;
    float:left;
}
</style>
<?php 
$img = array('jpg','gif','jpeg','png');
foreach($fileinfo as $index=>$value):
    $ext = strtolower(end(explode(".",$value['file']))); 
    if(in_array($ext,$img)): ?>
        <img class="wdmiconfile" id="<?php echo md5($value['file']) ?>" src="<?php  echo plugins_url().'/'.$value['file'] ?>" alt="<?php echo $value['name'] ?>" style="padding:5px; margin:1px; float:left; border:#fff 2px solid " />

        <input rel="wdmiconfile" style="display:none" <?php if(isset($icon) && $icon==$value['file']) echo ' checked="checked" ' ?> type="radio"  name="wpmp_list[icon]"  class="checkbox"  value="<?php echo $value['file'] ?>">

<?php 
    endif; 
endforeach; 
?>

<script type="text/javascript">
jQuery('#<?php echo md5($icon) ?>').css('border','#008000 2px solid').css('background','#F2FFF2');

jQuery('img.wdmiconfile').click(function(){

    jQuery('img.wdmiconfile').css('border','#fff 2px solid').css('background','transparent');
    jQuery(this).css('border','#008000 2px solid').css('background','#F2FFF2');
});

</script>
<div style="clear: both;"></div>