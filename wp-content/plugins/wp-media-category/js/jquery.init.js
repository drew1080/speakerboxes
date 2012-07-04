(function($) {
	if ($('tr.media_category').size() > 0) {
		
		$('select[class^="media-category-level-"]').live('change', function() {
			option = $('option#category-' + this.value);
			children = option.data('children');
			
			$(this).nextAll('select').each(function() {
				$(this).remove();
			});
			
			$('#media-category').val(this.value);
			
			if (children && (children.length > 0)) {
				createSelect(children, $(this));
			}
		});
		
		function createSelect(list, afterElement) {
			select = $('<select></select>');
			select.addClass('media-category-level-' + ($('select', 'tr.media_category').size() + 1));
			select.css('margin-right', '10px');
			
			$('<option></option>').text('--').appendTo(select);
			
			for (i in list) {
				option = $('<option></option>').attr({
					id: 'category-' + list[i].id,
					value: list[i].id
				})
				.text(list[i].name)
				.data('children', list[i].children);
				
				select.append(option);
			}		
			
			$(afterElement).after(select);		
		}
		
		$(document).ready(function() {
			createSelect(categories, '#media-category');
		});
	}
})(jQuery);