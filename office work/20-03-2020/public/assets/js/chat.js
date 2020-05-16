/*jslint browser: true*/
/*global $, jQuery, alert*/

$(document).ready(function () {

    "use strict";

    $('.chat-left-inner > .chatonline').slimScroll({
        height: '100%',
        position: 'right',
        size: "10px",
        alwaysVisible: true,
        touchScrollStep : 50,
        color: '#dcdcdc'

    });
    $(function () {
        var chat_list_height;
        $(window).on("load", function () { // On load
            var width = this.screen.width;
            if (width >= 768) {
                 chat_list_height = 470;
            }else{
                 chat_list_height = 285;
                 $('.chat-list').css({'min-height': (($(window).height()) - 100) + 'px'});
            }
            $('.chat-list').css({
                'height': (($(window).height()) - chat_list_height) + 'px'
            });
        });
        $(window).on("resize", function () { // On resize
            var width = this.screen.width;
            if (width >= 768) {
                 chat_list_height = 470;
            }else{
                 chat_list_height = 285
                 $('.chat-list').css({'min-height': (($(window).height()) - 100) + 'px'});
                 
            }
            $('.chat-list').css({
                'height': (($(window).height()) - chat_list_height) + 'px'
            });
        });
    });

    // this is for the left-aside-fix in content area with scroll

    $(function () {
        var chat_left_inner_height;
        $(window).on("load", function () { // On load
            var width = this.screen.width;
            if (width >= 768) {
                 chat_left_inner_height = 350;
            }else{
                 chat_left_inner_height = 120;
                 $('.chat-left-inner').css({'min-height': (($(window).height()) - 200) + 'px'});
            }
            $('.chat-left-inner').css({
                'height': (($(window).height()) - chat_left_inner_height) + 'px'
            });
        });
        $(window).on("resize", function () { // On resize
            var width = this.screen.width;
            if (width >= 768) {
                 chat_left_inner_height = 350;
            }else{
                 chat_left_inner_height = 120;
                 $('.chat-left-inner').css({'min-height': (($(window).height()) - 200) + 'px'});
            }
            $('.chat-left-inner').css({
                'height': (($(window).height()) - chat_left_inner_height) + 'px'
            });
        });
    });


    $(".open-panel").on("click", function () {
        $(".chat-left-aside").toggleClass("open-pnl");
        $(".open-panel i").toggleClass("ti-angle-left");
    });

});
