/* 
 * The javascript below will allow us to active the WordPress Image Uploader
 */
jQuery(document).ready(function() {

var uploadID = ""; /*setup the var in a global scope*/

jQuery('.upload-button').live('click', function() {
uploadID = jQuery(this).prev('input'); /*set the uploadID variable to the value of the input before the upload button*/
formfield = jQuery('.upload').attr('name');
tb_show('', 'media-upload.php?type=image&amp;amp;amp;TB_iframe=true');
return false;
});

window.send_to_editor = function(html) {
imgurl = jQuery('img',html).attr('src');
uploadID.val(imgurl); /*assign the value of the image src to the input*/
tb_remove();
};
});


// 3.5 media upload jquery
//jQuery(document).ready(function() {
//jQuery('.custom_media_upload').click(function() {
//
//    var send_attachment_bkp = wp.media.editor.send.attachment;
//
//    wp.media.editor.send.attachment = function(props, attachment) {
//
//        jQuery('.custom_media_image').attr('src', attachment.url);
//        jQuery('.custom_media_url').val(attachment.url);
//        jQuery('.custom_media_id').val(attachment.id);
//
//        wp.media.editor.send.attachment = send_attachment_bkp;
//    }
//
//    wp.media.editor.open();
//
//    return false;       
//});
//});