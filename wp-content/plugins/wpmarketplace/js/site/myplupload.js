jQuery.fn.exists = function () {
    return jQuery(this).length > 0;
}
jQuery(document).ready(function($) {
 
    if($(".plupload-upload-uic").exists()) {
        var pconfig=false;
        $(".plupload-upload-uic").each(function() {
            var $this=$(this);
            var id1=$this.attr("id");
            var imgId=id1.replace("plupload-upload-ui", "");
 
            plu_show_thumbs(imgId);
 
            pconfig=JSON.parse(JSON.stringify(base_plupload_config));
 
            pconfig["browse_button"] = imgId + pconfig["browse_button"];
            pconfig["container"] = imgId + pconfig["container"];
            pconfig["drop_element"] = imgId + pconfig["drop_element"];
            pconfig["file_data_name"] = imgId + pconfig["file_data_name"];
            pconfig["multipart_params"]["imgid"] = imgId;
            pconfig["multipart_params"]["_ajax_nonce"] = $this.find(".ajaxnonceplu").attr("id").replace("ajaxnonceplu", "");
 
            if($this.hasClass("plupload-upload-uic-multiple")) {
                pconfig["multi_selection"]=true;
            }
 
            if($this.find(".plupload-resize").exists()) {
                var w=parseInt($this.find(".plupload-width").attr("id").replace("plupload-width", ""));
                var h=parseInt($this.find(".plupload-height").attr("id").replace("plupload-height", ""));
                pconfig["resize"]={
                    width : w,
                    height : h,
                    quality : 90
                };
            }
 
            var uploader = new plupload.Uploader(pconfig);
 
            uploader.bind('Init', function(up){
 
                });
 
            uploader.init();
 
            // a file was added in the queue
            uploader.bind('FilesAdded', function(up, files){
                $.each(files, function(i, file) {
                    $this.find('.filelist').append(
                        '<div class="file" id="' + file.id + '"><b>' +
 
                        file.name + '</b> (<span>' + plupload.formatSize(0) + '</span>/' + plupload.formatSize(file.size) + ') ' +
                        '<div class="fileprogress"></div></div>');
                });
 
                up.refresh();
                up.start();
            });
 
            uploader.bind('UploadProgress', function(up, file) {
 
                $('#' + file.id + " .fileprogress").width(file.percent + "%");
                $('#' + file.id + " span").html(plupload.formatSize(parseInt(file.size * file.percent / 100)));
            });
 
            // a file was uploaded
            uploader.bind('FileUploaded', function(up, file, response) {
 
                //alert(response["response"]);
                $('#' + file.id).fadeOut();
                //for(j in file)alert(j)
                response=response["response"];
                
                
                // add url to the hidden field
                /*if($this.hasClass("plupload-upload-uic-multiple")) {
                    // multiple
                    var v1=$.trim($("#" + imgId).val());
                    if(v1) {
                        v1 = v1 + "," + response;
                    }
                    else {
                        v1 = response;
                    }
                    $("#" + imgId).val(v1);
                }
                else {
                    // single
                    $("#" + imgId).val(response + "");
                }*/
 
                // show thumbs 
                //plu_show_thumbs(imgId);
                var fname="";
                if(imgId=="img1"){
                    //send request to move file               
                    jQuery.post(ajaxurl,
                    {
                      action:"moveuploadprevfile",
                      fileurl:response  
                    },function(res){
                      fname=res;  
                      jQuery('#adpcon').append("<li id='li_"+file.id+"'><div id='"+file.id+"' style='float:left;padding:5px;' class='adp'><input type='hidden' id='in_"+file.id+"' name='wpmp_list[images][]' value='"+fname+"' /><nobr><b><i style='position:absolute;z-index:9999;cursor:pointer;' id='del_"+file.id+"'  rel='li_"+file.id+"' class='del_adp icon-remove' align=left></i><img src='"+pluginurl+"libs/timthumb.php?w=50&h=50&zc=1&src="+uploadimgurl+fname+"'/></b></nobr><div style='clear:both'></div></div></li>");
                    });
                    
                }
                if(imgId=="fimg"){
                    $("#" + imgId).val(response + "");
                    //send request to move file               
                    jQuery.post(ajaxurl,
                    {
                      action:"moveuploadfeaturedfile",
                      fileurl:response  
                    },function(res){
                      fname=res;  
                      jQuery('#mi').html("<img src='"+pluginurl+"libs/timthumb.php?w=220&zc=1&src="+fname+"'/><br/><img style='z-index:9999;cursor:pointer;' id='mi_del' rel='mi' src='"+pluginurl+"wpmarketplace/images/delete-button03.png' alt='Remove Featured Image' align=left />");
                    });
                    
                }
                
                if(imgId=="clogo"){
                    jQuery('#logo').val(response);
                    jQuery('.filelist').html("<img src='"+pluginurl+"libs/timthumb.php?w=70&zc=1&src="+response+"'/><i style='position:absolute;z-index:9999;cursor:pointer;' class='icon-remove' id='logo_"+file.id+"'></i>");
                     jQuery('#logo_'+file.id).click(function(){
                          jQuery('.filelist').html("");
                           jQuery('#logo').val("");
                     });
                }
                if(imgId=="img2"){
                    //send request to move file               
                    jQuery.post(ajaxurl,
                    {
                      action:"moveuploadprofile",
                      fileurl:response  
                    },function(res){
                        
                      fname=res;
                      var nm = fname;
                        if(fname.length>20) nm = fname.substring(0,7)+'...'+fname.substring(fname.length-10);
                        jQuery('#currentfiles').prepend("<div id='"+file.id+"' style='display:none' class='cfile'><input type='hidden' id='in_"+file.id+"' name='wpmp_list[file][]' value='"+fname+"' /><nobr><b><i id='del_"+file.id+"'  rel='del' class='icon-remove' align=left ></i>&nbsp;"+nm+"</b></nobr><div style='clear:both'></div></div>");
                        jQuery('#'+file.id).fadeIn();
                        jQuery('#del_'+file.id).click(function(){
                            if(jQuery(this).attr('rel')=='del'){
                            jQuery('#'+file.id).removeClass('cfile').addClass('dfile');
                            jQuery('#in_'+file.id).attr('name','del[]');
                            jQuery(this).attr('rel','undo').attr('title','Undo Delete');
                            jQuery(this).removeClass('icon-remove');
                            jQuery(this).addClass('icon-refresh');
                            
                            } else if(jQuery(this).attr('rel')=='undo'){
                            jQuery('#'+file.id).removeClass('dfile').addClass('cfile');
                            jQuery('#in_'+file.id).attr('name','wpmp_list[file][]');
                            jQuery(this).attr('rel','del').attr('title','Redo Delete');
                            jQuery(this).addClass('icon-remove');
                            jQuery(this).removeClass('icon-refresh');
                            }
                            
                            
                        })
                          
                    });
                     ;
                }
                
            });
 
 
 
        });
    }
});
 
function plu_show_thumbs(imgId) {
    var $=jQuery;
    var thumbsC=$("#" + imgId + "plupload-thumbs");
    thumbsC.html("");
    // get urls
    var imagesS=$("#"+imgId).val();
    var images=imagesS.split(",");
    for(var i=0; i<images.length; i++) {
        if(images[i]) {
            var thumb=$('<div class="thumb" id="thumb' + imgId +  i + '"><img src="' + images[i] + '" alt="" /><div class="thumbi"><a id="thumbremovelink' + imgId + i + '" href="#">Remove</a></div> <div class="clear"></div></div>');
            thumbsC.append(thumb);
            thumb.find("a").click(function() {
                var ki=$(this).attr("id").replace("thumbremovelink" + imgId , "");
                ki=parseInt(ki);
                var kimages=[];
                imagesS=$("#"+imgId).val();
                images=imagesS.split(",");
                for(var j=0; j<images.length; j++) {
                    if(j != ki) {
                        kimages[kimages.length] = images[j];
                    }
                }
                $("#"+imgId).val(kimages.join());
                plu_show_thumbs(imgId);
                return false;
            });
        }
    }
    if(images.length > 1) {
        thumbsC.sortable({
            update: function(event, ui) {
                var kimages=[];
                thumbsC.find("img").each(function() {
                    kimages[kimages.length]=$(this).attr("src");
                    $("#"+imgId).val(kimages.join());
                    plu_show_thumbs(imgId);
                });
            }
        });
        thumbsC.disableSelection();
    }
}