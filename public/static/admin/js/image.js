/**
 * Created by rehellinen on 2017/9/21.
 */
$(function(){
    var up = $('#upload').Huploadify({
        auto:true,
        fileTypeExts:'*.gif; *.jpg; *.png; *.jpeg',
        multi:false,
        formData:{key:123456,key2:'vvvv'},
        fileSizeLimit:99999999999,
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

$(function(){
    var up = $('#upload2').Huploadify({
        auto:true,
        fileTypeExts:'*.gif; *.jpg; *.png; *.jpeg',
        multi:false,
        formData:{key:123456,key2:'vvvv'},
        fileSizeLimit:99999999999,
        showUploadedPercent:true,
        showUploadedSize:true,
        removeTimeout:1000,
        uploader: URL.image_url,
        onUploadComplete:function(file, data, response){
            var obj = JSON.parse(data);
            $("#upload_org_code_img2").attr("src", obj.data).show();
            // $("#upload_org_code_img").show();
            $("#file_upload_image2").attr("value", obj.data);
        }
    });
});

$(function(){
    var up = $('#upload3').Huploadify({
        auto:true,
        fileTypeExts:'*.gif; *.jpg; *.png; *.jpeg',
        multi:false,
        formData:{key:123456,key2:'vvvv'},
        fileSizeLimit:99999999999,
        showUploadedPercent:true,
        showUploadedSize:true,
        removeTimeout:1000,
        uploader: URL.image_url,
        onUploadComplete:function(file, data, response){
            var obj = JSON.parse(data);
            $("#upload_org_code_img3").attr("src", obj.data).show();
            // $("#upload_org_code_img").show();
            $("#file_upload_image3").attr("value", obj.data);
        }
    });
});



