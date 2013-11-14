jQuery(function($){
	$('.pick-color').live('click', function(){
		var thiz = $(this),
			el = thiz.prev().prev();
		$(thiz.prev().show()).farbtastic(function(color){
			el.val(color);
		});
		return false;
	});
	$(document).mousedown(function(){
		$('div.color-selector').each(function(){
			if( $(this).css('display') == 'block' )
				$(this).fadeOut();
		});
	});

	$('#add_color').click(function(){
		$('#colors tbody').append('<tr><td><input type="text" class="widefat" name="colormanager[colors]['+ colormanager.count +'][label]" /></td><td><input type="text" name="colormanager[colors]['+ colormanager.count +'][selector]" class="widefat" dir="ltr" /></td><td><select name="colormanager[colors]['+ colormanager.count +'][property]"><option value="background">background</option><option value="background-color">background-color</option><option value="color">color</option><option value="border-color">border-color</option><option value="border-top-color">border-top-color</option><option value="border-right-color">border-right-color</option><option value="border-bottom-color">border-bottom-color</option><option value="border-left-color">border-left-color</option></select></td><td><input class="color" type="text" class="widefat" name="colormanager[colors]['+ colormanager.count +'][default]" /><div class="color-selector" style="position: absolute;"></div> <a class="pick-color" href="#">Pick a color</a></td></tr>');
		colormanager.count++;
	});
	$('a.delete').live('click', function(){
		$(this).parents('tr').fadeOut('slow', function(){
			$(this).remove();
		});
		return false;
	});
});