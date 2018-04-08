/**
 * Created by rehellinen on 2017/9/21.
 */
$(function(){
    var up = $('#upload').Huploadify({
        auto:true,
        fileTypeExts:'*.gif; *.jpg; *.png; *.jpeg',
        multi:false,
        fileSizeLimit:20000,
        showUploadedPercent:true,
        showUploadedSize:true,
        removeTimeout:1000,
        uploader: URL.image_url,
        onUploadComplete:function(file, data, response){
            var obj = JSON.parse(data);
            $("#upload_org_code_img").attr("src", obj.data).show();
            // $("#upload_org_code_img").show();
            $("#file_upload_image").attr("value", obj.data);
        }
    });
});
