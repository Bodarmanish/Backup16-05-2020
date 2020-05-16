/*jslint browser: true*/
/*global $, jQuery, alert*/
$(document).ready(function () {

    "use strict";

    var body = $("body");
    
    //hide_alert_message(); /* Unset Seesion of Alert Message */

    /* Strat: Set Modal-Body Height */
    $(function () {
        var chat_list_height;
        $(window).on("load", function () {
            var width = this.screen.width;
            var w_height = $(window).height();
            if (width >= 992) {
                chat_list_height = 400;
            }else if(width < 992){
                chat_list_height = 150;
            }
            $('.modal-body').css({'height': (w_height - chat_list_height) + 'px'});
        });
        
        $(window).on("resize", function () {
            var width = this.screen.width;
            var w_height = $(window).height();
           if (width >= 992) {
                chat_list_height = 400;
            }else if(width < 992){
                chat_list_height = 150;
            }
            $('.modal-body').css({'height': (w_height - chat_list_height) + 'px'});
        });
    });
    /* End: Set Modal-Body Height */
    
    $(function () {
        $(".preloader").fadeOut();
        $('#side-menu').metisMenu();
    });

    /* ===== Theme Settings ===== */

    /* ===== Open-Close Right Sidebar/Chat Box Modal ===== */

    $(".right-side-toggle").on("click", function () {
        $(".right-sidebar").slideDown(50).toggleClass("shw-rside");
        $(".fxhdr").on("click", function () {
            body.toggleClass("fix-header"); /* Fix Header JS */
        });
        $(".fxsdr").on("click", function () {
            body.toggleClass("fix-sidebar"); /* Fix Sidebar JS */
        });

        /* ===== Service Panel JS ===== */

        var fxhdr = $('.fxhdr');
        if (body.hasClass("fix-header")) {
            fxhdr.attr('checked', true);
        } else {
            fxhdr.attr('checked', false);
        }
    });
    
    
    $(".chat-sidebar-toggle").on("click", function () {
        $(".chat-sidebar").slideDown(50).toggleClass("shw-rside");
        $(".chat-sidebar-toggle a").toggleClass("hidden");
    });

    /* ===========================================================
        Loads the correct sidebar on window load.
        collapses the sidebar on window resize.
        Sets the min-height of #page-wrapper to window size.
    =========================================================== */

    $(function () {
        var set = function () {
                var topOffset = 200,
                    width = (window.innerWidth > 0) ? window.innerWidth : this.screen.width,
                    height = ((window.innerHeight > 0) ? window.innerHeight : this.screen.height) - 1;
                if (width < 768) {
                    $('div.navbar-collapse').addClass('collapse');
                    topOffset = 100; /* 2-row-menu */
                } else {
                    $('div.navbar-collapse').removeClass('collapse');
                }

                /* ===== This is for resizing window ===== */

                if (width < 1170) {
                    body.addClass('content-wrapper');
                    $(".sidebar-nav, .slimScrollDiv").css("overflow-x", "visible").parent().css("overflow", "visible");
                } else {
                    body.removeClass('content-wrapper');
                }

                height = height - topOffset;
                if (height < 1) {
                    height = 1;
                }
                if (height > topOffset) {
                    $("#page-wrapper").css("min-height", (height) + "px");
                }
            },
            url = window.location,
            element = $('ul.nav a').filter(function () {
                return this.href === url || url.href.indexOf(this.href) === 0;
            }).addClass('active').parent().parent().addClass('in').parent();
        if (element.is('li')) {
            element.addClass('active');
        }
        $(window).ready(set);
        $(window).bind("resize", set);
    });

    /* ===== Collapsible Panels JS ===== */

    (function ($, window, document) {
        var panelSelector = '[data-perform="panel-collapse"]',
            panelRemover = '[data-perform="panel-dismiss"]';
            
        /* ===== Collapse Panels ===== */
        initCollapsiblePanel(document);

        /* ===== Remove Panels ===== */

        $(document).on('click', panelRemover, function (e) {
            e.preventDefault();
            var removeParent = $(this).closest('.panel');

            function removeElement() {
                var col = removeParent.parent();
                removeParent.remove();
                col.filter(function () {
                    return ($(this).is('[class*="col-"]') && $(this).children('*').length === 0);
                }).remove();
            }
            removeElement();
        });
    }(jQuery, window, document));
        
    /* ===== Popover Initialization ===== */

    $(function () {
        $('[data-toggle="popover"]').popover();
    });
 
    $(".toggle-password").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
          input.attr("type", "text");
        } else {
          input.attr("type", "password");
        }
    });
    
    /* ===== Task Initialization ===== */

    $(".list-task li label").on("click", function () {
        $(this).toggleClass("task-done");
    });
    $(".settings_box a").on("click", function () {
        $("ul.theme_color").toggleClass("theme_block");
    });

    /* ===== Collepsible Toggle ===== */

    $(".collapseble").on("click", function () {
        $(".collapseblebox").fadeToggle(350);
    });

    /* ===== Sidebar ===== */

    initSlimScroll('.slimscrollright',{
        height: '100%',position: 'right',size: "5px",touchScrollStep : 50,color: '#dcdcdc'
    });
    initSlimScroll('.chat_slimscrollright',{
        height: '100%',position: 'right',size: "5px",touchScrollStep : 50,color: 'rgba(0,0,0,0.3)'
    });
    initSlimScroll('.slimscrollsidebar',{
        height: '100%',position: 'right',size: "6px",touchScrollStep : 50,color: 'rgba(0,0,0,0.3)'
    });
    initSlimScroll('.chat-list',{
        height: '100%',position: 'right',size: "5px",alwaysVisible: true,touchScrollStep : 50,color: 'rgba(0,0,0,0.3)'
    });
    initSlimScroll('.slimscrolldropdownmenu',{
        height: '190px',size: "6px",alwaysVisible: true,touchScrollStep : 50,color: 'rgba(0,0,0,0.3)'
    });
    initSlimScroll('.timeline_stp',{
        height: '450px',position: 'right',size: "7px",touchScrollStep : 50,color: 'rgba(0,0,0,0.3)'
    });
    initSlimScroll('.timeline_stp_desc .panel .panel-wrapper',{
        height: '450px',position: 'right',size: "7px",touchScrollStep : 50,color: 'rgba(0,0,0,0.3)'
    });
    
    /* ===== Resize all elements ===== */

    body.trigger("resize");

    /* ===== Visited ul li ===== */

    $('.visited li a').on("click", function (e) {
        $('.visited li').removeClass('active');
        var $parent = $(this).parent();
        if (!$parent.hasClass('active')) {
            $parent.addClass('active');
        }
        e.preventDefault();
    });

    /* ===== Login and Recover Password ===== */

    /* $('#to-recover').on("click", function () {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });

    $('#back-to-recover').on("click", function () {
        $("#recoverform").hide();
        $("#loginform").fadeIn();
    }); */

    /* ================================================================= 
        Update 1.5
        this is for close icon when navigation open in mobile view
    ================================================================= */ 
    $(".navbar-toggle").on("click", function () {
        $(".navbar-toggle i").toggleClass("ti-menu").addClass("ti-close");
    });
     
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
    
    $('.general_right_sidebar .subcat_list a, .sub_category_label a').click(function() {
        window.location.href = "subcategory";
    });
    
    $('.category_label a').click(function() {
        window.location.href = "category";
    });
    
    $('.post_topic').click(function() {
        window.location.href = "postnewtopic";
    });
    
    /* Strat Connection Model/Page Script */
    $("#confrm_req").mouseover(function() {
         this.innerHTML = "Cancel Request";
    });
    $("#confrm_req").mouseout(function() {
         this.innerHTML = "Request Sent";
    });
    /* End Connection Model/Page Script */
    
    /* Start Chat-model Script */
    $('#add_member').click(function() {
        $("#add_member_list").removeClass("hidden");
        $("#hide_add_member").addClass("hidden");
        $(".chat-box").css("opacity","0.5");
    });
    
    $('#hide_add_member_list').click(function() {
        $("#hide_add_member").removeClass("hidden");
        $("#add_member_list").addClass("hidden");
        $(".chat-box").css("opacity","1");
    });
    
    $('.chatonline li a').on("click", function (e) {
        $('.chatonline li a').removeClass('active');
        var $parent = $(this);
        if (!$parent.hasClass('active')) {
            $parent.addClass('active');
            show_inner_loader('.chat-right-aside');
        }
        setTimeout(function(){ hide_inner_loader('.chat-right-aside'); }, 3000);
        e.preventDefault();
    });
    /* End Chat-model Script */

    $("#slideshow > div:gt(0)").hide();

    setInterval(function() {
      $('#slideshow > div:first')
        .fadeOut(1000)
        .next()
        .fadeIn(1000)
        .end()
        .appendTo('#slideshow');
    }, 3000);


    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    initTooltip();
         
    /* Home page multi carousel js*/
    var itemsMainDiv = ('.MultiCarousel');
    var itemsDiv = ('.MultiCarousel-inner');
    var itemWidth = "";

    $('.leftLst, .rightLst').click(function () {
        var condition = $(this).hasClass("leftLst");
        if (condition)
            click(0, this);
        else
            click(1, this)
    });

    ResCarouselSize(); 
    
    $(window).resize(function () {
        ResCarouselSize();
    });
    
    //this function define the size of the items
    function ResCarouselSize() {
        var incno = 0;
        var dataItems = ("data-items");
        var itemClass = ('.item');
        var id = 0;
        var btnParentSb = '';
        var itemsSplit = '';
        var sampwidth = $(itemsMainDiv).width();
        var bodyWidth = $('body').width();
        $(itemsDiv).each(function () {
            id = id + 1;
            var itemNumbers = $(this).find(itemClass).length;
            btnParentSb = $(this).parent().attr(dataItems);
            itemsSplit = btnParentSb.split(',');
            $(this).parent().attr("id", "MultiCarousel" + id);


            if (bodyWidth >= 1200) {
                incno = itemsSplit[2];
                itemWidth = sampwidth / incno;
            }
            else if (bodyWidth >= 992) {
                incno = itemsSplit[2];
                itemWidth = sampwidth / incno;
            }
            else if (bodyWidth >= 768) {
                incno = itemsSplit[2];
                itemWidth = sampwidth / incno;
            }
            else if (bodyWidth >= 480) {
                incno = itemsSplit[2];
                itemWidth = sampwidth / incno;
            }
            else {
                incno = itemsSplit[0];
                itemWidth = sampwidth / incno;
            }
            $(this).css({ 'transform': 'translateX(0px)', 'width': itemWidth * itemNumbers });
            $(this).find(itemClass).each(function () {
                $(this).outerWidth(itemWidth);
            });

            $(".leftLst").addClass("over");
            $(".rightLst").removeClass("over");

        });
    }


    //this function used to move the items
    function ResCarousel(e, el, s) {
        var leftBtn = ('.leftLst');
        var rightBtn = ('.rightLst');
        var translateXval = '';
        var divStyle = $(el + ' ' + itemsDiv).css('transform');
        var values = divStyle.match(/-?[\d\.]+/g);
        var xds = Math.abs(values[4]);
        if (e == 0) {
            translateXval = parseInt(xds) - parseInt(itemWidth * s);
            $(el + ' ' + rightBtn).removeClass("over");

            if (translateXval <= itemWidth / 2) {
                translateXval = 0;
                $(el + ' ' + leftBtn).addClass("over");
            }
        }
        else if (e == 1) {
            var itemsCondition = $(el).find(itemsDiv).width() - $(el).width();
            translateXval = parseInt(xds) + parseInt(itemWidth * s);
            $(el + ' ' + leftBtn).removeClass("over");

            if (translateXval >= itemsCondition - itemWidth / 2) {
                translateXval = itemsCondition;
                $(el + ' ' + rightBtn).addClass("over");
            }
        }
        $(el + ' ' + itemsDiv).css('transform', 'translateX(' + -translateXval + 'px)');
    }

    //It is used to get some elements from btn
    function click(ell, ee) {
        var Parent = "#" + $(ee).parent().attr("id");
        var slide = $(Parent).attr("data-slide");
        ResCarousel(ell, Parent, slide);
    } 
    
    var viewNotify = localStorage.getItem('viewNotifyInfo') === '1'; 
    if (viewNotify) {
        $( ".fix-header" ).removeClass( "MM_ON" );
        return;
    }
    else {
        $( ".mm_box" ).removeClass("hide");
    }
    
    $('#notify_ok').click(function() {
        $(this).parents("div").remove();
        $( ".fix-header" ).removeClass( "MM_ON" );
        localStorage.setItem('viewNotifyInfo', '1');
    });
    
    $( ".datepicker" ).keydown(function( event ) {
        return false;
    }); 
    
    $( ".datetimepicker" ).keydown(function( event ) {
        return false;
    });
   
});

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
        
/* Start Tooltip Script */
function initTooltip(targetselector)
{
    var targets = $( '[rel~=tooltip]' ),
        target  = false,
        tooltip = false,
        title   = false;

    if(targetselector != "" && targetselector != "undefied" && targetselector != null){
        targets = $("[rel~="+targetselector+"]");
    }
 
    targets.bind( 'mouseenter', function()
    {
        target  = $( this );
        tip     = target.attr( 'title' );
        tooltip = $( '<div id="tooltip"></div>' );
 
        if( !tip || tip == '' )
            return false;
 
        target.removeAttr( 'title' );
        tooltip.css( 'opacity', 0 )
               .html( tip )
               .appendTo( 'body' );
 
        var init_tooltip = function()
        {
            if( $( window ).width() < tooltip.outerWidth() * 1.5 )
                tooltip.css( 'max-width', $( window ).width() / 2 );
            else
                tooltip.css( 'max-width', 340 );
 
            var pos_left = target.offset().left + ( target.outerWidth() / 2 ) - ( tooltip.outerWidth() / 2 ),
                pos_top  = target.offset().top - tooltip.outerHeight() - 20;
 
            if( pos_left < 0 )
            {
                pos_left = target.offset().left + target.outerWidth() / 2 - 20;
                tooltip.addClass( 'left' );
            }
            else
                tooltip.removeClass( 'left' );
 
            if( pos_left + tooltip.outerWidth() > $( window ).width() )
            {
                pos_left = target.offset().left - tooltip.outerWidth() + target.outerWidth() / 2 + 20;
                tooltip.addClass( 'right' );
            }
            else
                tooltip.removeClass( 'right' );
 
            if( pos_top < 0 )
            {
                var pos_top  = target.offset().top + target.outerHeight();
                tooltip.addClass( 'top' );
            }
            else
                tooltip.removeClass( 'top' );
 
            tooltip.css( { left: pos_left, top: pos_top } )
                   .animate( { top: '+=10', opacity: 1 }, 50 );
        };
 
        init_tooltip();
        $( window ).resize( init_tooltip );
 
        var remove_tooltip = function()
        {
            tooltip.animate( { top: '-=10', opacity: 0 }, 50, function()
            {
                $( this ).remove();
            });
 
            target.attr( 'title', tip );
        };
 
        target.bind( 'mouseleave', remove_tooltip );
        tooltip.bind( 'click', remove_tooltip );
    });
};
/* End Tooltip Script */

/* Start Function: OPEN/CLOSE Left Sidebar */
function open_close_left_sidebar() {
    $("body").toggleClass("show-sidebar").toggleClass("hide-sidebar");
    $(".sidebar-head .open-close i").toggleClass("ti-menu");
}
/* End Function: OPEN/CLOSE Left Sidebar */

/* Strat Connection Model Script */
    function sent_req() {
        $("#req_after").addClass("hidden");
        $("#req_before").removeClass("hidden");
        $("#available_frds").show();
        $("#search_available_frds").show();
        $("#no_available_frds").hide();
    }
    
    function confrm_req() {
        $(".frd_res_detail").hide();
        $("#close_frd_1").removeClass("hidden");
        $(".frd_res_confrm").removeClass("hidden");
    }
    
    function go_back_frd_res() {
        $(".frd_res_detail").show();
        $("#req_after").removeClass("hidden");
        $("#req_before").addClass("hidden");
        $("#close_frd_1").addClass("hidden");
        $(".frd_res_confrm").addClass("hidden");
    }
    
    function skip_req(id) {
        $("#frd_"+id).hide();
    }
    
    function dlt_frd_confrm() {
        setTimeout(function(){
            $("#dlt_frd_1").addClass("hidden");
            $("#dlt_frd_confrm_1").removeClass("hidden");
            $(".popover").hide();
        }, 1000);
    }
    
    function is_dlt_frd() {
        $("#is_frd_1").hide();
        $("#is_frd_1").removeClass("hidden");
        $(".popover").hide();
    }
    
    function no_dlt_frd() {
        $("#dlt_frd_1").removeClass("hidden");
        $("#dlt_frd_confrm_1").addClass("hidden");
    }
/* End Connection Model/Page Script */

 /* Start: intersted/none-intersted script start - Event sidebar */
function intersted(id){
    if($('#interst_'+id).hasClass("remove_intersted")){
        $('#interst_'+id).empty().append("<i class='fa fa-spin fa-spinner fa-x m-r-5 text-muted'></i>Intersted");
        $('#interst_'+id).removeClass("remove_intersted");
        $('#interst_'+id).addClass("text-black");
        $('#interst_'+id).removeClass("text-info");
        setTimeout(function(){
            $('#interst_'+id).empty().append("Intersted");
            $('#interst_'+id).addClass("text-info");
        }, 3000);
    }else{
        $('#interst_'+id).prepend("<i class='fa fa-spin fa-spinner fa-x m-r-5 text-muted'></i>");
        $('#interst_'+id).addClass("remove_intersted");
        $('#interst_'+id).addClass("text-black");
        $('#interst_'+id).removeClass("text-info");
        setTimeout(function(){
            $('#interst_'+id).empty().append("<i class='fa fa-check text-success m-r-5'></i>Intersted");
            $('#interst_'+id).addClass("text-muted");
            $('#interst_'+id).removeClass("text-black");
        }, 3000);
    }
}
/* End: intersted/none-intersted script end - Event sidebar */

/* Start:  Alert Message/Inner div Loader effect - hide/show */
function show_inner_loader(block_div,parent_ID)
{  
   if(parent_ID){
       var string = parent_ID +' '+block_div;
   }else{
       var string = block_div;
   } 
   $(string).block({
        message: '<h4 class="font-normal"><img src="/user/assets/images/busy.gif" style="margin-right:2px;" /> Please Wait...</h4>',
        overlayCSS: {
            backgroundColor: '#ffffff',
            opacity: '0.5'
        },
        css: {
            border: '0px',
            background: 'transparent',
            width:'50%',
            top:'30%',
            left:'40%'
        }
    });
}
function hide_inner_loader(block_div, parent_ID)
{
    if(parent_ID){
        var string = parent_ID +' '+block_div;
    }else{
        var string = block_div;
    }
    $(string).unblock();
}
/* End: Alert Message/Inner div Loader effect - hide/show */

/* Start: New add member hide/show */
function load_new_chat_box(){
    $("#new_add_chat_people").removeClass("hidden");
    $("#chat_msg").addClass("hidden");
}
function goto_chat_list(){
    $("#new_add_chat_people").addClass("hidden");
    $("#chat_msg").removeClass("hidden");
} 
/* End: New add member hide/show */

/* Start: Alert Message Display Page/Popup Modal - hide/show */
function hide_alert(element){
    $(element).closest("div.alert").addClass("hidden");
}
function show_alert(){
    $(".alert").removeClass("hidden");
}
function show_alert_message(type,msg,action,fadeout,url){

    if(url == "" || url == null)
    {
        url = "set_alert_message.php";
    }
    if(type == '' || type == null || type == 'undefined')
    {
        /* Set Default Info Alert */
        type = "info";
    }
    if(fadeout == '' || fadeout == null || fadeout == 'undefined')
    {
        /* Set Default Info Alert */
        fadeout = false;
    }
    if(action == '' || action == null || action == 'undefined')
    {
        /* Set Default Page Name/Action Name */
        action = current_page_name;
    }
    $.post(url,{ action : "show_alert_msg", type: type, msg: msg },function(response){
        if(response.type == "success"){
            $("#"+action+"_alert_message").html(response.data);
            if(fadeout === true || fadeout === '1'){
                setTimeout(function(){
                    hide_alert_message(action);
                }, 3000);
            }
        }
    },'json');
}

function hide_alert_message(action){ 
    $.ajax({
        url: 'AjaxRequest',
        type: "post",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: { action : 'hide_alert_msg' },
        success:function(response){
            if(response.type == "success"){
                $("#"+action+"_alert_message").fadeOut("slow").html("");
            }
        }
    });
}
/* End: Alert Message Display Page/Popup Modal - hide/show */

/* Start popup related function*/

function popup_connection_modal()
{
    show_popup('modal-lg');
    get_common_ajax("connection_modal","modal-lg");
}

function popup_create_event()
{
    show_popup('modal-lg');
    get_common_ajax("modal_create_event","modal-lg");
}

function popup_edit_event()
{
    show_popup('modal-lg');
    get_common_ajax("modal_edit_event","modal-lg");
} 

function popup_activity_log()
{
    show_popup('modal-md');
    get_common_ajax("activity_log","modal-md");
}

function popup_set_partner_odc()
{
    show_popup('modal-default','bg-extralight');
    get_common_ajax("which_partner_odc","modal-default");
}

function popupBranding(popup_id)
{
    show_popup('modal-lg-itn-odc');
    get_common_ajax(popup_id,"modal-lg-itn-odc");
}

function popup_document_instruction()
{
    show_popup('modal-lg');
    get_common_ajax("document_instruction","modal-lg");
}
/* End popup related function*/

/* Start: Function of SweetAlert - plugins/sweetalert/jquery.sweet-alert.custom.js */
    
function swal_dlt_post_data(id, txt, action){
    swal({
        title: "Are you sure?",   
        text: "Delete is permanent.",   
        type: "warning",   
        showCancelButton: true,
        confirmButtonColor: "#1faae6",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false 
    }, function(){
        swal({
            title: "Deleted!",
            text: txt+" was successfully deleted...",
            type: "success",   
            confirmButtonColor: "#1faae6",
            showCancelButton: false, 
            closeOnConfirm: true
        }, function(){
            show_alert_message("warning", txt+ " was successfully deleted...", action);
            if(action != '' || action != null || action != 'undefined'){ setTimeout(function(){ hide_popup("modal-lg"); },4000); }
        });
    });
}

function swal_sa_draft_data(id, action){
    swal({   
        title: "Are you sure?",   
        text: "Save is Draft.",   
        type: "warning",   
        closeOnConfirm: true,   
        confirmButtonColor: "#1faae6",   
        confirmButtonText: "Yes", 
        cancelButtonText: "No",
        showCancelButton: true
        }, function(){
           show_alert_message("success", "Data was successfully save in Draft...", action);
           if(action != '' || action != null || action != 'undefined'){ setTimeout(function(){ hide_popup("modal-lg"); },4000); }
        });
}
   
function eligibilityComplete(msg_text,callback_func) {
    swal({
            title: "Congratulations!",   
            text: msg_text,   
            type: "success",   
            showCancelButton: false,    
            confirmButtonColor: "#1faae6",
            confirmButtonText: "Let's Go",  
            closeOnConfirm: true ,
            html:true  
        },
        function(){
            callback_func();
    });
}

function eligibilityNoComplete(msg_text,callback_func){
    
    swal({
        title: "",   
        text: msg_text,
        type: "warning",   
        showCancelButton: false,    
        confirmButtonColor: "#1faae6",
        confirmButtonText: "Retake the test",  
        closeOnConfirm: true,
        html:true
        },
    function(){
        callback_func();
    });
}
/* End: Function of SweetAlert - plugins/sweetalert/jquery.sweet-alert.custom.js */
    
/* Start All function of 'recent_discussions.php' Page */
/*function topic_notify(){
    $("#show_topic_notify").removeClass("hidden");
    $("#hide_topic_notify").addClass("hidden");
    $(".on_notify").addClass("hidden");
    $(".off_notify").removeClass("hidden");
}
function topic_notify_hide(){
    $('#hide_topic_notify').removeClass("hidden");
    $('#show_topic_notify').addClass("hidden");
    $(".on_notify").removeClass("hidden");
    $(".off_notify").addClass("hidden");
}
function topic_notify_show(){
    $('#show_topic_notify').removeClass("hidden");
    $('#hide_topic_notify').addClass("hidden");
    $(".on_notify").addClass("hidden");
    $(".off_notify").removeClass("hidden");
} 
*/
function going_event_list(){
    $("#upcoming_event_list").removeClass("hidden");
    $("#create_event").addClass("hidden");
}
function topic_report_show(){
    $("#hide_topic_report").addClass("hidden");
    $(".r_discus_and_profile").removeClass("hidden");
}
/* End All function of 'recent_discussions.php' Page */
  
/* Start function for Change button text */
function change_btn(ele, chng_txt){
    var change_txt;
    if(chng_txt === "" || chng_txt === undefined || chng_txt === null){
        change_txt = "Saving...";
    }else{
        change_txt = chng_txt+"...";
    }
    var get = $(ele).html();
    if( !($(ele).hasClass('disabled')) ){
        $(ele).html(change_txt);
        $(ele).after('<i class="fa fa-spin fa-spinner m-l-10 text-muted"></i>');
    }
    setTimeout(function(){
        $(ele).html(get)
        $(ele).parent().find('i.fa.fa-spin.fa-spinner').addClass("hidden");
    }, 3000);
}
/* Start function for Change button text */

function goBrandConnect(){
    $("#brand_connection").addClass("hidden");
    $("#brand_connecting").removeClass("hidden");
    
    var text = ["Requesting for transfer data...", "Transferring data...", "At good, hold tight..."];
    var counter = 0;
    var elem = document.getElementById("changeText");
    var inst = setInterval(function () {
      elem.innerHTML = text[counter];
      counter++;
      if (counter >= text.length) {
        counter = 0;
        clearInterval(inst);
      }
    }, 1000);

    var countdown_seconds = 5;
    var countdown_mseconds = ((countdown_seconds + 1) * 1000);
    countDown(countdown_seconds);
    /*Temp. set timeout*/
    setTimeout(function() {
        hide_popup("modal-lg-itn-odc");
/*        window.location.reload(); */
    }, countdown_mseconds);
    /*Temp. set timeout*/
}

/* Start function for time-count, Here (format -> mm:ss and counter -> sencond) */
function countDown(counter){
    var time = $("#count_time");
    var interval = setInterval(function(){	
        var minutes = ((counter / 60) | 0) + "";
        var seconds = (counter % 60) + "";
        var format = ""
                    + new Array(3-minutes.length).join("0") + minutes 
                    + ':' 
                    + new Array(3-seconds.length).join("0") + seconds;

        time.html(format);
        counter--;
        if(counter === 0){
            clearInterval(interval);
        }
    },1000);            
}
/* End function for time-count */

/* Start function for hide/show content-field - "Additional Information" step */
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
            $("#j1_program_field .j1_old_program :input").prop("disabled",false);
            $(".add_new_j1_field").hide();
        } else {
            $("#j1_program_field .j1_old_program").hide();
            $("#j1_program_field .j1_old_program :input").prop("disabled",true);
            $(".add_new_j1_field").show();
        }
    }
}
/* End function for hide/show content-field - "Additional Information" step */

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
        format: "mm/dd/yyyy hh:ii",
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
/* End  Function of Display Switch */

/* Start Function of Like/Unlick, Follow/Unfollow, Comment : Post Topic */
function count_post_topic(id_name){
    var c_name =  $("#"+id_name).attr("class").split(' ')[1];
    if(id_name == "comment"){
        $(".post_comment").focus();
    }else if(c_name == "like" || c_name == "follow"){
        $("#"+id_name).text("Un"+id_name);
        $("#"+id_name+"_count").text("1");
        $("#"+id_name).removeClass(id_name).addClass("un"+id_name);
    }else{
        $("#"+id_name).text(id_name);
        $("#"+id_name+"_count").text("5");
        $("#"+id_name).removeClass("un"+id_name).addClass(id_name);
    }
}
/* End Function of Like/Unlick, Follow/Unfollow, Comment : Post Topic */

/* Start Function of Toggle open/close Timeline-Step List on @media max-width:767px */
function toggle_timeline_stps(){
    $(".timeline-xs-r-width").toggleClass("open-pnl");
    $(".open-timeline-panel").toggleClass("open-pnl");
    $(".open-timeline-panel i").toggleClass("ti-angle-left");
     $('html,body').animate({ scrollTop: $('#all_tab_data').position().top + 75}, 400);
}

/* End Function of Toggle open/close Timeline-Step List on @media max-width:767px */

/* Start Function: Go to timeline */
function go_to_timeline(){
    $("#empty_timeline").hide();
    $("#timeline_detail").show();
}
/* End Function: Go to timeline */

/* Start Function: Hide/Show Loader of Page Load Content*/
function load_page_content(id){
    
    $("#"+id).hide();
    $("#page_loader_show").show();
     setTimeout(function() {
        $("#"+id).show();
        $("#page_loader_show").hide();
    }, 5000);
}
/* End Function: Hide/Show Loader of Page Load Content */

/* Start Function: Hide/Show Notification/Post Notification */
function hide_notification(id){
    $(".hide_notification_row_"+id).show();
    $(".show_notification_row_"+id).hide();
}

function show_notification(id){
    $(".hide_notification_row_"+id).hide();
    $(".show_notification_row_"+id).show();
}

function off_notification_post(id){
    $(".show_notification_row_"+id).hide();
    $(".off_notification_row_"+id).show();
}

function on_notification_post(id){
    $(".show_notification_row_"+id).show();
    $(".off_notification_row_"+id).hide();
}
/* Start Function: Hide/Show Notification/Post Notification */

/* Start Function: Accept Friend Request */
function accept_req(id)
{
    $("#acc_req_"+id).hide();
}
/* End Function: Accept Friend Request */

/* Start Function: set disable btn/input after onclick */
function set_loader(ele)
{
    $(ele).attr("disabled",true);
}
/* End Function: set disable btn/input after onclick */
  
/* Show loader when form submit using ajax */
function showLoader(element, chng_txt){
    var change_txt;
    if(chng_txt === "" || chng_txt === undefined || chng_txt === null){
        change_txt = "Saving...";
    }else{
        change_txt = chng_txt+"...";
    } 
    if( !($(element).hasClass('disabled')) ){
        $(element).html(change_txt);
        $(element).after('<i class="fa fa-spin fa-spinner m-l-10 text-muted"></i>');
    } 
} 
/* Hide loader when form response getting using ajax */
function hideLoader(element, chng_txt){  
    $(element).html(chng_txt) 
    $(element).parent().find('i.fa.fa-spin.fa-spinner').addClass("hidden"); 
}
 
/* store user notification status */
function store_user_notification_status(obj,status,ID){
    var value = 0;
    if(obj.checked) {
        value = 1;
    } 
    $.ajax({
        type: 'post',
        url: 'AjaxRequest',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data:  { 
            value: value,
            status: status,
            notify_type_id: ID, 
            action: 'storeUserNotificationStatus'
        },
        success: function(response) { 
            /* Success */
        }
    });
}

function initSlimScroll(selector,options)
{
    var default_option = {
            height: '100%',
            position: 'right',
            size: "7px",
            touchScrollStep : 50,
            color: 'rgba(0,0,0,0.3)'
        };
        
    if(options == "" || options == "undefined" || options == null)
    {
        options = default_option;
    }
    
    if(selector != "" && selector != "undefined" && selector != null)
    {
        $(selector).slimScroll(options);
    }
}

function initPieProgress(selector)
{
    if(selector == "" || selector == "undefined" || selector == null)
    {
        selector = ".pie_progress";
    }
    
    $(selector).asPieProgress({
        namespace: 'pie_progress',
        trackcolor: '#c9c9c9',
    });
    $(selector).asPieProgress('start');
}

function initDropify(selector){
    
    if(selector != "" && selector != "undefined" && selector != null)
    {
        $(selector).each(function (){
            var replace_txt = $(this).attr('alt');
            $(this).dropify({
                messages: {
                    default: replace_txt,
                    replace: replace_txt,
                    remove:  'X'
                }
            });
        });
    }
    else
        return false;
}

function initCollapsiblePanel(document)
{
    var panelSelector = '[data-perform="panel-collapse"]';
    $(panelSelector).each(function () {
        var collapseOpts = {
                toggle: false
            },
            parent = $(this).closest('.panel'),
            wrapper = parent.find('.panel-wrapper'),
            child = $(this).children('i');
        if (!wrapper.length) {
            wrapper = parent.children('.panel-heading').nextAll().wrapAll('<div/>').parent().addClass('panel-wrapper');
            collapseOpts = {};
        }
        wrapper.collapse(collapseOpts).on('hide.bs.collapse', function () {
            child.removeClass('fa-chevron-up').addClass('fa-chevron-down');
        }).on('show.bs.collapse', function () {
            child.removeClass('fa-chevron-down').addClass('fa-chevron-up');
        });
    });

    /* ===== Collapse Panels ===== */

    $(document).on('click', panelSelector, function (e) {
        e.preventDefault();
        var parent = $(this).closest('.panel'),
            wrapper = parent.find('.panel-wrapper');
        wrapper.collapse('toggle');
    });
}

function setFormBtnAction(form_id,btn_action)
{
    $("#"+form_id+" input[name='btn_action']").val(btn_action);
    return true;
} 
/**
 * Function ajaxFormValidator(selector,submit_function)
  @param selector string of selector e.g.: Class => '.exampleClass', Id => '#exampleId'
  @param submit_function A callback function on submit form
 * **/
function ajaxFormValidator(selector,submit_function,turn_off){
    
    if(turn_off != "off"){
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

/**
 * Function startCountdown
 * @param selector string of selector e.g.: Class => '.exampleClass', Id => '#exampleId'
 * @param finalDate string of date in format like 'YYYY/MM/DD hh:mm:ss'
 * @param finish_callback is callback function call on end of countdown timer.
 * **/
function startCountdown(selector,finalDate,finish_callback){

    var event_counter = 1;
    $(selector).countdown(finalDate,function(event) {
        var countdown_str = "";
        var totalDays = event.offset.totalDays;
        var totalSeconds = event.offset.totalSeconds;
        

        if(totalDays > 0){
            if(totalDays == 1){
                countdown_str = event.strftime('%D day %H:%M:%S');
            }
            else{
                countdown_str = event.strftime('%D days %H:%M:%S');
            }
        
            $(this).text(countdown_str);
        }
        else if(totalSeconds > 0){
            countdown_str = event.strftime('%H:%M:%S');
            $(this).text(countdown_str);
        }
        else{
            $(this).text(countdown_str);
            if(typeof finish_callback === "function" && event_counter == 1)
                finish_callback(this);
        }
        
        event_counter++;
    }).on('finish.countdown', function(){
        if(typeof finish_callback === "function")
            finish_callback(this);
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

function loadingButton(ele, action, btn_text){
    var change_txt;
    if(btn_text === "" || btn_text === undefined || btn_text === null){
        change_txt = "Saving... <i class='fa fa-spin fa-refresh m-l-10'></i>";
    }else{
        change_txt = btn_text+"... <i class='fa fa-spin fa-refresh m-l-10'></i>";
    }

    if(action == "start")
    {
        $(ele).attr("disabled",true).html(change_txt);
    }
    else if(action == "stop")
    {
        $(ele).removeAttr("disabled").html(btn_text);
    }
}

function trackSubmitBtn(selector){
    $(selector).find("input[type=submit],button[type=submit]").on('click',function() {
        $(selector).find("input[type=submit],button[type=submit]").removeAttr("clicked");
        $(this).attr("clicked", "true");
    });
}


function getUserFavoriteTopic(){ 
    $.ajax({
        type: 'post',
        url: '/AjaxRequest',
        dataType: "json",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }, 
        data: {   
            'action': 'getUserFavoriteTopic'
        },
        dataType: "json",
        success: function(response) {
            $( "li#usefavouritetopic" ).replaceWith( response.data ); 
        }
    });
}

function stageComplete(nextStageNum,text)
{
    swal({
        title: "Congratulations",   
        text: text,
        type: "success",
        confirmButtonColor: "#1faae6",
        confirmButtonText: "Let's Go",
    },function(btn){
        if(btn === true){
            navigateStages(nextStageNum);
        }
    });
}

function popup_viewArrivalCheckInInfo(activityId,flightOrder)
{
    show_popup('modal-lg');
    var extra_data = { activityId: activityId, flightOrder: flightOrder };
    get_common_ajax("viewArrivalCheckInInfo","modal-lg",extra_data);
}

function popup_viewMontlyCheckInInfo(activityId,monthlyCheckInNo, plaId)
{
    show_popup('modal-lg');
    var extra_data = { activityId: activityId, monthlyCheckInNo: monthlyCheckInNo, plaId: plaId };
    get_common_ajax("viewMonthlyCheckInInfo","modal-lg",extra_data);
}

function viewPendingMonthlyCheckInInfo(plaId,monthlyCheckInNo)
{
    show_popup('modal-lg');
    var extra_data = { plaId: plaId, monthlyCheckInNo: monthlyCheckInNo };
    get_common_ajax("viewPendingMonthlyCheckInInfo","modal-lg",extra_data);
}

function popup_viewParticipantEvaluationInfo(activityId,pcType)
{
    show_popup('modal-lg');
    var extra_data = { activityId: activityId, pcType: pcType};
    get_common_ajax("viewParticipantEvaluationInfo","modal-lg",extra_data);
}

function popup_viewSupervisorEvaluationInfo(activityId,pcType)
{
    show_popup('modal-lg');
    var extra_data = { pcType: pcType, activityId:activityId };
    get_common_ajax("viewSupervisorEvaluationInfo","modal-lg",extra_data);
} 

function loadURLInIframe(ele)
{
    var url = $(ele).attr("url");
    var title = $(ele).attr("title");
    var activekey = $("#"+$(ele).attr("id")).val();
    show_popup('modal-lg');
    var extra_data = { url: url, title:title, activekey:activekey};
    get_common_ajax("loadURLInIframe","modal-lg",extra_data);
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

function confirmAlert(ele,msg,type,title,btntext,callback_func)
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

function app_procedure_list_toggle(no){
    $("#toggle_angle_"+no).toggleClass('fa-angle-up fa-angle-down');
    $("#app_procedure_list_"+no).slideToggle("slow");
}

function userTourGuide(guide_id){
    var introguide = introJs();  
    var uservisited = 0;
    swal({   
        title: "Application Procedure",   
        text: "If you are interested in knowing J1 guidelines, opt for <strong>Access Guide!</strong> button.",   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#41b3f9",   
        confirmButtonText: "Access Guide!",   
        cancelButtonText: "No, cancel pls!",   
        closeOnConfirm: true,   
        closeOnCancel: true,
        html:true
    }, function(isConfirm){   
        if (isConfirm) { 
            introguide.setOption('overlayOpacity','0.9').setOption('skipLabel','Exit').start(); 
            uservisited = 1;
            /*introguide.oncomplete(function () {
                updateUserGuide(1,1);
            });*/
        } else {   
            uservisited = 0;
        } 
        updateUserGuide(guide_id,uservisited);
    });
}

function updateUserGuide(guide_id,uservisited){
    $.ajax({
        type: 'post',
        url: '/AjaxRequest', 
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: 'action=updateUserGuide&guide_id='+guide_id+'&guide_is_visited='+uservisited,
        dataType: 'json'
    });
}

function btnLoader(ele, btn_type, chng_txt){
    
    var change_txt, type;
    if(btn_type == "" || btn_type == "undefined" || btn_type == null){
       btn_type = 'show';
    }
     
    if(chng_txt === "" || chng_txt === undefined || chng_txt === null){
        change_txt = "Saving...";
    }else{
        change_txt = chng_txt+"...";
    } 
    
    if(btn_type=='show'){
        $(ele).attr("disabled", true);
        $(ele).html(change_txt);
        $(ele).after('<i class="fa fa-spin fa-spinner m-l-10 text-muted"></i>');
    }else{
        $(ele).attr("disabled", false);
        $(ele).html(chng_txt)
        $(ele).parent().find('i.fa.fa-spin.fa-spinner').addClass("hidden");
    }
} 

function recaptchaCallback() {
    var url = $("#resend_activation_email").data("url");
    setTimeout(function(){ $("#modal-default .close").click(); }, 3000);
    window.location = url;
};