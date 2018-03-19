/**
 * Created by rehellinen on 2017/9/21.
 */

// $(function() {
//     $("#file_upload").uploadify({
//         'swf'             :    URL.swf_url,
//         'uploader'       :    URL.image_url,
//         'buttonText'     :   '图片上传',
//         'fileTypeDesc'   :   '图片',
//         'fileObjName'    :   'file',
//         'fileTypeExts'   :   '*.gif; *.jpg; *.png',
//         'onUploadSuccess' : function(file, data, response) {
//             if(response){
//                 var obj = JSON.parse(data);
//                 $("#upload_org_code_img").attr("src", obj.data);
//                 $("#upload_org_code_img").show();
//                 $("#file_upload_image").attr("value", obj.data);
//             }
//         }
//     });
// });

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
            $("#upload_org_code_img").attr("src", obj.data);
            $("#upload_org_code_img").show();
            $("#file_upload_image").attr("value", obj.data);
        }
    });
});



