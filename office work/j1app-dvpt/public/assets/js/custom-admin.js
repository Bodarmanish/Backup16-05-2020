/*jslint browser: true*/
/*global $, jQuery, alert*/
$(document).ready(function () {

    /* Start: Overide Script of js/validator.js */
    $('form.form-validator').validator({
        feedback: {
            success: 'fa fa-check',
            error: 'fa fa-times'
        },
        custom: {
            'nowhitespace': function($el) {  
                if ($el.val().indexOf(" ") < 0) { 
                    return "White space are not allowed.";
                }
            },
            'notempty': function($el) {  
                if ($.trim($el.val()) != "" ) { 
                    return "Please fill out this field.";
                }
            },
        },
        errors: {
            nowhitespace: "White space are not allowed.",
            notempty: "Please fill out this field.",
        } 
    });
    /* End: Overide Script of js/validator.js */ 
    
    load_datepicker();
    load_datetimepicker();
    load_timepicker();
    load_switch();
});

/**
 * Function ajaxFormValidator(selector,submit_function)
  @param selector string of selector e.g.: Class => '.exampleClass', Id => '#exampleId'
  @param submit_function A callback function on submit form
 * **/
function ajaxFormValidator(selector,submit_function,turn_off){
    
    if(turn_off !== "off"){
        $(selector).validator({
            feedback: {
                success: 'fa fa-check',
                error: 'fa fa-times',
            },
            disable: false,
            focus: true,
            custom: {
                'nowhitespace': function($el) {  
                    if ($el.val().indexOf(" ") < 0) { 
                        return "White space are not allowed.";
                    }
                },
                'notempty': function($el) {  
                    if ($.trim($el.val()) != "") { 
                        return "Please fill out this field.";
                    }
                },
            },
            errors: {
                nowhitespace: "White space are not allowed.",
                notempty: "Please fill out this field.",
            },
        }).on('submit',function(e){ 
            if (!e.isDefaultPrevented()) {
                if(typeof submit_function === "function"){
                    submit_function(this,e);
                }
            }
        });
    }
    else{
        $(selector).on('submit',function(e){
            submit_function(this,e);
        });
    }
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
    
    bg_color = "bg-black";
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

/** Start Function of Datepicker/DatetimePicker **/
function load_datepicker(){
    $('.datepicker').datepicker({
        autoclose: true,
        todayHighlight: true,  
        orientation: 'auto auto',
        startDate: "1-1-1970"
    });
}

function load_datetimepicker(){
 $('.datetimepicker').datetimepicker({
        format: "MM/DD/YYYY hh:mm A",
        weekStart: true,
        todayBtn:  false,
        autoclose: true,
        todayHighlight: true,
        startView: 2,
        forceParse: false,
        showMeridian: true,
        startDate: new Date("1970-01-01 12:00:00")
    });
}

function load_timepicker(){
    $('.timepicker').datetimepicker({
        format: "HH:ii P",
        weekStart: true,
        todayBtn:  false,
        autoclose: true,
        todayHighlight: true,
        startView: 1,
        minView: 0,
        maxView: 1,
        forceParse: false,
        showMeridian: 1,
        startDate: new Date("1970-01-01 12:00:00")
    });
}

/** End Function of Datepicker/DatetimePicker **/

/* Start Function of Display Switch */
function load_switch(){
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    $('.js-switch').each(function() {
        new Switchery($(this)[0], $(this).data());
    });
}

function notifyResponseTimerAlert(msg,type,title,time,btntext)
{ 
    var data = {};

    if(title == "" || title == "undefined" || title == null)
        title = "";

    data['title'] = title;

    if(msg == "" || msg == "undefined" || msg == null)
        msg = "Please wait...";

    data['text'] = msg;

    if(time == "" || time == "undefined" || time == null)
        time = 3000; 

    if(type != "" && type != "undefined" && type != null)
        data['type'] = type;
    
    if(btntext == "" || btntext == "undefined" || btntext == null)
        btntext = "Ok";
    
    data['confirmButtonText'] = btntext;
    data['showConfirmButton'] = true;
    data['html'] = true;
    data['closeOnConfirm'] = true;
    data['confirmButtonColor'] = "#41b3f9";
    data['timer'] = time;
    swal(data);
}

function notifyAlert(msg,type,title)
{ 
    var data = {};

    if(title != "" && title != "undefined" && title != null)
        data['title'] = title;
    data['text'] = msg;
    data['type'] = "error";
    if(type != "" && type != "undefined" && type != null)
        data['type'] = type;
    data['confirmButtonText'] = "Ok";
    data['showConfirmButton'] = true;
    data['html'] = true;
    data['closeOnConfirm'] = true;
    data['confirmButtonColor'] = "#41b3f9";
    
    swal(data);
}

function confirmAlert(msg,type,title,btntext,callback_func,ele)
{ 
    var data = {};

    if(title == "" || title == "undefined" || title == null)
        title = "";

    data['title'] = title;

    if(msg == "" || msg == "undefined" || msg == null)
        msg = "Please wait...";

    data['text'] = msg;

    if(type != "" && type != "undefined" && type != null)
        data['type'] = type;
    
    if(btntext == "" || btntext == "undefined" || btntext == null)
        btntext = "Ok";
    
    data['confirmButtonText'] = btntext;
    
    if(callback_func == "" || callback_func == "undefined" || callback_func == null)
        callback_func = "";

    data['showConfirmButton'] = true;
    data['showCancelButton'] = true;
    data['cancelButtonText'] = "Cancel";
    data['html'] = true;
    data['closeOnConfirm'] = true;
    data['confirmButtonColor'] = "#41b3f9";
    
    swal(data, function(isConfirm){
        if(typeof callback_func === "function"){
            callback_func(ele,isConfirm);
        }
    });
}

function notifyResponse(target,msg_txt,type,autohidedelay){
    
    var wrapper = "",wrapper_class = "";
    var message = "";
    
    if(typeof msg_txt == "object")
    {
        message += "<ul>";
        $.each(msg_txt, function( key, value ) {
            message += '<li>' + value+ '</li>';
        });
        message += "</ul>";
    }
    if(typeof msg_txt == "string")
    {
        message = msg_txt;
    }
    
    if(type === "success")
    {
        wrapper_class = "alert-success";
    }
    else if(type === "warning")
    {
        wrapper_class = "alert-warning";
        wrapper = "<div class='alert alert-warning'></div>";
        
    }
    else if(type === "error")
    {
        wrapper_class = "alert-danger";
    }
    
    wrapper = "<div class='alert "+wrapper_class+"'>"+message+"</div>";
    
    $(target).html(wrapper);
    
    autohidedelay = parseInt(autohidedelay);
    
    if(autohidedelay == "" || autohidedelay == "undefined" || autohidedelay == null || typeof autohidedelay != 'number')
        autohidedelay = 0;
    
    if(autohidedelay > 100)
    {
        setTimeout(function(){
            $(target).find(".alert").fadeOut(500);
            setTimeout(function(){ 
                $(target).html(""); 
            },500);
        }, autohidedelay);
    }
}

function showLoader(selector){
    $(selector).show();
}

function hideLoader(selector){
    $(selector).hide();
}

function confirmDelete(route) {
    showLoader("#full-overlay");
    confirmAlert("On confirm record will be deleted.","warning","Are you sure?","Confirm",function(r,i){
        if(i){
            window.location.href = r;
        }
        else{
            hideLoader("#full-overlay");
        }
    },route);
};

function setFormBtnAction(form_id,btn_action)
{
    $("#"+form_id+" input[name='btn_action']").val(btn_action);
    return true;
} 

function toggle_fields(action, category_name) {
    if (action == true) {
        $("#"+category_name).slideDown();
        $("#"+category_name+" .disable-control").attr("disabled",false);
    } else {
        $("#"+category_name).slideUp();
        $("#"+category_name+" .disable-control").attr("disabled",true);
    }
}

function toggle_j1_program_field(action, type) {
    if(type === "1")
    {
        if (action == true) {
            $("#j1_program_field").slideDown();
            $("#j1_program_field .j1_recent_program .disable-control").attr("disabled",false);
        } else {
            $("#j1_program_field").slideUp();
            $("#j1_program_field .disable-control").attr("disabled",true);
            toggle_j1_program_field(false, '2');
        }
    }
    
    if(type === "2")
    {
        if (action == true) {
            $("#j1_program_field .j1_old_program").show();
            $("#j1_program_field .j1_old_program .disable-control").attr("disabled",false);
            $(".add_new_j1_field").hide();
        } else {
            $("#j1_program_field .j1_old_program").hide();
            $("#j1_program_field .j1_old_program .disable-control").attr("disabled",true);
            $(".add_new_j1_field").show();
        }
    }
}

function stageComplete(nextStageNum,active_step_key,text)
{
    swal({
        title: "Congratulations",   
        text: text,
        type: "success",
        confirmButtonColor: "#1faae6",
        confirmButtonText: "Let's Go",
    },function(btn){
        if(btn === true){
            navigateStages(nextStageNum,active_step_key);
        }
    });
}