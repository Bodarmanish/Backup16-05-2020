$(document).ready(function () {
        /* https://codepen.io/anon/pen/qvVgdv */
    var $sticky = $('.sticky-top');
    var screensize = $(window).width();
    if (screensize > 768) {
        if (!!$sticky.offset()) {

            var generalSidebarHeight = $sticky.innerHeight();
            var stickyTop = $sticky.offset().top;
            var stickOffset = 0;

            $(window).scroll(function () {
                var windowTop = $(window).scrollTop();
                if (stickyTop < windowTop + stickOffset) {
                    $sticky.css({position: 'fixed', top: stickOffset});
                } else {
                    $sticky.css({position: 'absolute', top: 'initial'});
                }
            });
            var windowTop = $(window).scrollTop();
            if (stickyTop < windowTop + stickOffset) {
                $sticky.css({position: 'fixed', top: stickOffset});
            } else {
                $sticky.css({position: 'absolute', top: 'initial'});
            }
        }
    }
    $(".page_top").click(function () {
        $('html, body').animate({scrollTop: 0}, 'slow');
    });
    
    $(".sticky-top a").click(function () {
        var href_id = $(this).attr("href");
        var tab_name = href_id.charAt(1);
        
        $('html, body').animate({
            scrollTop: $("#" + tab_name + "_all_tab").offset().top
        }, 'slow');
        $(href_id + "_user_tab").click();
    }); 
    
    var hashparameter = $(location).attr('hash');
    if( hashparameter != null && hashparameter != undefined && hashparameter != ""){
        var href_id = hashparameter;
        var tab_name = href_id.charAt(1);
        
        $('html, body').animate({
            scrollTop: $("#" + tab_name + "_all_tab").offset().top
        }, 'slow');
        $(href_id + "_user_tab").click();
    }
    
    /*start Hide form of Resume Builder
        $("#resume_form_2").hide();
        $("#resume_form_3").hide();
        $("#resume_form_4").hide();
        $("#resume_form_5").hide();
        $("#resume_form_6").hide();
        
        $(document).on("click","#sbt_1",function() {
            $("#resume_form_2").show();
        });
        
        $(document).on("click","#sbt_2",function() {
            $("#resume_form_3").show();
        });
        
        $(document).on("click","#sbt_3",function() {
            $("#resume_form_4").show();
        });
        
        $(document).on("click","#sbt_4",function() {
            $("#resume_form_5").show();
        });
        
        $(document).on("click","#sbt_5",function() {
            $("#resume_form_6").show();
        });
    end Hide form of Resume Builder*/
    
    /*Start hide form of Additional Information
        $("#additionalInfo_form_2").hide();
        $("#additionalInfo_form_3").hide();
        $("#additionalInfo_form_4").hide();
        $("#additionalInfo_form_5").hide();
        $("#additionalInfo_form_6").hide();
        $("#additionalInfo_form_7").hide();
        $("#additionalInfo_form_8").hide();
        $("#additionalInfo_form_9").hide();
        
        $(document).on("click","#add_sbt_1",function() {
            $("#additionalInfo_form_2").show();
        });
        
        $(document).on("click","#add_sbt_2",function() {
            $("#additionalInfo_form_3").show();
        });
        
        $(document).on("click","#add_sbt_3",function() {
            $("#additionalInfo_form_4").show();
        });
        
        $(document).on("click","#add_sbt_4",function() {
            $("#additionalInfo_form_5").show();
        });
        
        $(document).on("click","#add_sbt_5",function() {
            $("#additionalInfo_form_6").show();
        });
        
        $(document).on("click","#add_sbt_6",function() {
            $("#additionalInfo_form_7").show();
        });
        
        $(document).on("click","#add_sbt_7",function() {
            $("#additionalInfo_form_8").show();
        });
        
        $(document).on("click","#add_sbt_8",function() {
            $("#additionalInfo_form_9").show();
        });
    End hide form of Additional Information*/
    
    
    /*START SET CURRENT TAB ID ON URL*/
    $(document).on("click",".nav-link",function(e) {
        
        e.preventDefault();
        var url = window.location.href;
        var id = url.substring(url.lastIndexOf('/') + 1);
        
        if(id != $(this).attr('href')){
             window.location = $(this).attr('href');     
        }
    });
    /*END SET CURRENT TAB ID ON URL*/
     
}); 