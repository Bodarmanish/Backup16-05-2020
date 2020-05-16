$(document).ready(function () {

});

function show_inner_loader(block_div, parent_ID)
{
    if (parent_ID) {
        var string = parent_ID + ' ' + block_div;
    }
    else {
        var string = block_div;
    }
    $(string).block({
        message: '<h4 class="font-normal"><img src="/assets/images/busy.gif" style="margin-right:2px;" /> Please Wait...</h4>',
        overlayCSS: {
            backgroundColor: '#ffffff',
            opacity: '0.5'
        },
        css: {
            border: '0px',
            background: 'transparent',
            width: '50%',
            top: '30%',
            left: '40%'
        }
    });
}

function hide_inner_loader(block_div, parent_ID)
{
    if (parent_ID) {
        var string = parent_ID + ' ' + block_div;
    }
    else {
        var string = block_div;
    }
    $(string).unblock();
}

function get_common_ajax(url,extra_data,popup_id){

    if(popup_id == '' || popup_id == null || popup_id == 'undefined') {
        popup_id = "popup-modal-default";
    }
    
    if(typeof extra_data !== "object"){
        extra_data = { data: extra_data };
    }

    $.ajax({
        url: url,
        type: "post",
        dataType: "json",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: extra_data,
        success:function(response){ 
            if(response.type=='success'){
                $("#"+popup_id).removeClass("loading");
                $('#'+popup_id+' .modal-dialog').html(response.data);
            }
            else{
                $("#"+popup_id).removeClass("loading");
                $('#'+popup_id+' .modal-dialog').html(response.message);
            }
        }  
    });
}

function show_popup(popup_id,popup_type,options)
{
    var bg_color = '';
    
    if(popup_id == '' || popup_id == null || popup_id == 'undefined')
    {
        popup_id = "popup-modal-default";
    }
    
    if(options == '' || options == null || options == 'undefined')
    {
        options = null;
    }
    
    bg_color = "bg-info";
    if(popup_type != '' && popup_type != null && popup_type != 'undefined')
    {
        bg_color = popup_type;
    }
    
    var html_str = $("#"+popup_id+" .modal-dialog").html();

    $("#"+popup_id+" .modal-dialog").html(html_str);
    $("#"+popup_id+" .modal-header").addClass(bg_color);
    $("#"+popup_id).addClass("loading").modal(options).on('hidden.bs.modal', function (e) {
        $("#"+popup_id+" .modal-dialog").html(html_str);
        $("#"+popup_id).removeClass("loading");
    });
}

function hide_popup(popup_id){
    if(popup_id == null || popup_id == "" || popup_id == 0)
    {
        popup_id = "popup-modal-default";
    }
    var html_str = $("#"+popup_id+" .modal-dialog").html();
    $("#"+popup_id+" .modal-dialog").html(html_str);
    $("#"+popup_id).removeClass("loading");
    $("#"+popup_id).modal('hide');
}
