$('#submitButton').click(function () {
   var res = $('#submitForm').serializeArray();
   var postData = {};
   $(res).each(function (i) {
       postData[this.name] = this.value;
   });
   $.post(URL.submit_url, postData, function (result) {
       if(result.status===1){
           dialog.success(result.message, URL.success_url);
       }
       if(result.status===0){
           dialog.error(result.message);
       }
   },"JSON");
});

$('#registerButton').click(function () {
    var res = $('#submitForm').serializeArray();
    var postData = {};
    $(res).each(function (i) {
        postData[this.name] = this.value;
    });
    $.post(URL.submit_url, postData, function (result) {
        if(result.status===1){
            console.log(result);
            dialog.email(result.message, result.data);
        }
        if(result.status===0){
            dialog.error(result.message);
        }
    },"JSON");
});

$('* .listorder').blur(function () {
    var id = $(this).attr('attr-id');
    var listorder = $(this).val();
    var postData = {
        'id' : id,
        'listorder' : listorder
    };

    $.post(URL.listorder_url, postData, function (result) {
        if(result.status===1){
            dialog.success(result.message, URL.success_url);
        }

    }, "JSON");
});

$('* .editButton').click(function () {
    var id = $(this).attr('attr-id');
    window.location.href=URL.edit_url+"?id="+id;
});

$('* .menuButton').click(function () {
    var id = $(this).attr('attr-id');
    window.location.href = URL.success_url + '?id=' + id;
});

$('* .statusButton').click(function () {
   var id = $(this).attr('attr-id');
   var status = $(this).attr('attr-status');
   postData = {
       'id' : id,
       'status' : status
   };
   dialog.status('是否确定更改状态', URL.status_url, postData);
});

$('* .buyButton').click(function () {
   var id = $(this).attr('attr-id');
   var postData = {
     'id' : id
   };
   $.post(URL.buy_url, postData, function (result) {
       if(result.status===1){
           window.location.href = URL.buy_url+"?id="+id;
       }
       if(result.status===0){
           dialog.error(result.message);
       }
   },"JSON");

});