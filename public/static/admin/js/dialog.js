/**
 * Created by rehellinen on 2017/9/11.
 */

var dialog = {
    //错误弹出层
    error : function (message) {
        layer.open({
            content : message,
            icon : 2,
            title: '错误提示'
        })
    },

    //成功弹出层
    success : function(message,url) {
        layer.open({
            content : message,
            icon : 1,
            yes : function(){
                location.href=url;
            }
        });
    },

    // 确认弹出层
    confirm : function(message, url) {
        layer.open({
            content : message,
            icon:3,
            btn : ['是','否'],
            yes : function(){
                location.href=url;
            }

        });
    },

    //无需跳转到指定页面的确认弹出层
    toconfirm : function(message) {
        layer.open({
            content : message,
            icon:3,
            btn : ['确定']
        });
    },

    //用于修改状态询问的弹出层
    status : function (message,url,postData) {
        layer.open({
            content : message,
            icon : 3,
            btn : ['是', '否'],
            yes : function () {
                $.post(url, postData, function (result) {
                    if(result.status===1){
                        dialog.success(result.message, URL.success_url);
                    }
                    if(result.status===0){
                        dialog.error(result.message);
                    }
                },"JSON")
            }
        })
    }
};