
jQuery(document).ready(function($){
    var scntDiv = $('#slidejs-slide');
    var i = $('#slidejs-slide p').size() + 1;
        
    $('#addSlide').live('click', function() {
        $('<p class="slide-wrap"><label for="new_slides">New Slide</label><input class="upload" type="text" name="image[]" value="" /><input class="upload-button" type="button" name="wsl-image-add" value="Upload Image" /><label>Slide Link</label><input type="text" id="slide-link" name="slide-link[]" value="" /><a href="#" id="remScnt">Remove</a></p>').appendTo(scntDiv);
        i++;
        return false;
    });
        
    $('#remScnt').live('click', function() { 
        if( i > 0 ) {
            $(this).parents('p').remove();
            i--;
        }
        return false;
    });
    
    
    $('.group').live('click',function(){
        $('.slidejs-img', this).hide();
        $('.hidden', this).removeClass('hidden');
    });
    
    $('.group').mouseenter(function(){
        $('.close-img', this).show()
    })
    
    $('.group').mouseleave(function(){
        $('.close-img', this).hide()
    })
});