$(document).ready(function () {
    
    //for get api
    $('.getuserapi').on('click',function(){
        var formaction = $(this).closest("form").attr('action'); //("input[name='act']").val();
        var usertoken = $(this).closest("form").find("input[name='usertoken']").val();
        
        var formdata = $(this).closest("form").serialize();
        $.ajax({
            type: 'GET',
            url: formaction,
            data: formdata,
            headers:{
                        "Authorization": "Bearer " + usertoken
                    },
            success: function(data) {
                var w = window.open('','_blank');
                var jsondata = JSON.stringify(data,null,"\t");
                w.document.write('<html><body><pre>' + jsondata + '</pre></body></html>');
            },
            error: function (data) {
                var w = window.open('','_blank');
                var jsondata = JSON.stringify(data.responseJSON,null,"\t");
                w.document.write('<html><body><pre>' + jsondata + '</pre></body></html>');
            }
        });
    });
    
    //for post api
    $('.userapi').on('click',function(){
        var formaction = $(this).closest("form").attr('action'); //find("input[name='act']").val();
        var usertoken = $(this).closest("form").find("input[name='usertoken']").val();

        var form = $(this).closest("form");
        var formData = false;
        if (window.FormData){
            formData = new FormData(form[0]);
        }
        
        $.ajax({
            type: 'POST',
            url: formaction,
            processData: false,
            contentType: false,
            data:  formData ? formData : form.serialize(),
            headers:{
                        "Authorization": "Bearer " + usertoken
                    },
            success: function(data) {
                var w = window.open('','_blank');
                var jsondata = JSON.stringify(data,null,"\t");
                w.document.write('<html><body><pre>' + jsondata + '</pre></body></html>');
            },
            error: function (data) {
                var w = window.open('','_blank');
                var jsondata = JSON.stringify(data.responseJSON,null,"\t");
                w.document.write('<html><body><pre>' + jsondata + '</pre></body></html>');
            }
        });
    });
});