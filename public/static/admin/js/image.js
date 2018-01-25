/**
 * Created by rehellinen on 2017/9/21.
 */

$(function() {
    $("#file_upload").uploadify({
        'swf'             :    URL.swf_url,
        'uploader'       :    URL.image_url,
        'buttonText'     :   '图片上传',
        'fileTypeDesc'   :   '图片',
        'fileObjName'    :   'file',
        'fileTypeExts'   :   '*.gif; *.jpg; *.png',
        'onUploadSuccess' : function(file, data, response) {
            if(response){
                var obj = JSON.parse(data);
                $("#upload_org_code_img").attr("src", obj.data);
                $("#upload_org_code_img").show();
                $("#file_upload_image").attr("value", obj.data);
            }
        }
    });
});



