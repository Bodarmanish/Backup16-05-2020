<script type="text/javascript">
    var active_stage = "{{ $active_stage }}";
    var active_step_key = "{{ $active_step_key }}";
    var active_step = "{{ $active_step }}";
    var current_stage = "{{ $current_stage }}";

    $(document).ready(function () {
        $('.timeline_stp_desc .panel.panel-default').not('.active').hide();
        $(".disable-content .disable-control").attr("disabled",true);

        /* Start: Load Application Status Popover *
        if ($(document).width() > 1024) {
            $('#menu_application_status').popover('show');
            setTimeout(function(){
                $('#menu_application_status').popover('hide');
            },4000);
        } 
        /* End: Load Application Status Popover */

        /** Show hide information on Additional Information tab 8 on Registration Stage  **/
        $("ul.addition_info_list li a").on('click', function() {
            var page = $(this).data('page');
            $("#desc_stp_8 .infotab:not('.hide')").stop().fadeOut('fast', function() {
                $(this).addClass('hide');
                $('#desc_stp_8 .infotab[data-page="'+page+'"]').fadeIn('slow').removeClass('hide');
            });
        });

        /** Support document process on 9th tab on registration Stage  **/
        var cnt = 0;
        $('.support_docs').on('change', function(ele){
            var id = $(this).attr("id");
            if($('#'+id).val()){ cnt++; }
            if(cnt==5){
                $('#submit_document').prop('disabled', false);
            }
        });
        /** Support document process on 4th tab on Hiring Stage  **/
        var placement_cnt = 0;
        $('.support_placement_docs').on('change', function(ele){
            var id = $(this).attr("id");
            if($('#'+id).val()){ placement_cnt++; }
            if(placement_cnt==4){
               $('#submit_document_2').prop('disabled', false);
            } 
        });

        /** Load Processbar of left side tab  **/
        initPieProgress();

        /** Start: Load dropify on load page '9 tab of Registration Stage' &  4 tab of Placement Stage **/
        ///initDropify('.support_docs, .support_placement_docs');

        //scrollSteps(active_step_key);
        totalProgress();

        //makeTabsToAccordion();
    }); 

    function loadStepContent(active_step_key,callback_success){
        var stage_id = "stage_content";

        var url = "{{ route('navigatestage') }}";
        show_inner_loader(".timeline_stp_desc","#all_tab_data");

        $.ajax({
            url: url,
            type: 'post',
            data: { action:"navigate_step", active_step_key: active_step_key },
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            /*statusCode: {
                404: function(jqXHR,textStatus,errorThrown) {
                    alert("Page not found");
                },
                500: function(jqXHR,textStatus,errorThrown) {
                    alert("Internal server error");
                },
            },*/
            success: function(response){
                hide_inner_loader('.timeline_stp_desc',"#all_tab_data");

                if(response.type == "success"){
                    var data = response.data;

                    $("#"+stage_id+" .timeline_stp_desc .panel-body").html(data.step_content);

                    scrollSteps(active_step_key);
                    initDropify('.support_docs, .support_placement_docs');
                    load_datepicker();
                    load_datetimepicker();
                    makeTabsToAccordion();

                    if(typeof callback_success === "function")
                    {
                        callback_success();
                    }
                }
            },

        });
    }

    function scrollSteps(step_key){

        var step_id = "stp_"+step_key;
        var stage_id = "stage_content";
        var selector = '#'+stage_id+' #'+step_id;

        $(selector).animatescroll({ element:'#'+stage_id+' .timeline_stp',padding:40 });  
        var movescroll = $(selector).outerHeight()+'px'; 
        $('#'+stage_id+' .slimScrollDiv .slimScrollBar').css('top',movescroll);
    }

    function navigateStages(stage_number,step_key,update_current_stage){

        var url = "{{ route('navigatestage') }}";
        var data = {
                    action:"navigate_stage",
                    active_stage: stage_number, 
                    request_step_key: step_key, 
                };
        show_inner_loader(".timeline_stp_desc","#all_tab_data");

        if(update_current_stage != "" && update_current_stage != "undefined" && update_current_stage != null)
        {
            current_stage = update_current_stage;
        }

        if(current_stage >= 3)
        {
            if((active_stage == 1 || active_stage == 2) && (stage_number == 3 || stage_number == 4))
            {
                @if(!empty($user->sponsor))
                    popupBranding('sponsor_branding');
                @endif
            }
            else if((stage_number == 1 || stage_number == 2) && (active_stage == 3 || active_stage == 4))
            {
                @if(!empty($user->partner))
                    popupBranding('partner_branding');
                @endif
            }
        }

        $.ajax({
            url: url,
            type: 'post',
            data: data,
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response){

                if(response.type == "success" && typeof response.data === 'object')
                {
                    var data = response.data;

                    active_stage = data.active_stage;
                    active_step = data.active_step;
                    active_step_key = data.active_step_key;
                    application_status_content = data.application_status_content;
                    page_title = data.page_title;
                    page_sub_title = data.page_sub_title;

                    hide_inner_loader('.timeline_stp_desc',"#all_tab_data");

                    $("#all_tab_data").html(application_status_content);

                    $('.timeline_stp_desc .panel.panel-default').not('.active').hide();
                    $(".disable-content .disable-control").attr("disabled",true);

                    $("ul.wizard-steps li").removeClass("active");
                    $("#main_stage_"+active_stage).parent("li").addClass("active"); 

                    $(".application_status_progress .page-title, .app_stats_breadcrumb li.active").html(page_title);
                    $(".application_status_progress .page-sub-title").html(page_sub_title);

                    var scroll_options = { height: "450px", position: "right", size: "7px", touchScrollStep : 50, color: "rgba(0,0,0,0.3)" };
                    initSlimScroll(".timeline_stp, .timeline_stp_desc .panel .panel-wrapper", scroll_options);
                    initDropify('.support_docs, .support_placement_docs');
                    initCollapsiblePanel();
                    initTooltip();
                    scrollSteps(active_step_key);
                    initPieProgress();
                    totalProgress();
                    load_datepicker();
                    load_datetimepicker();
                }

            },
        });
    }

    function uploadDocument(doc_type,stage_num,step_key){
        show_inner_loader(".timeline_stp_desc","#all_tab_data");

        var form_id = "frm_upload_"+doc_type;
        var form_ele = document.getElementById(form_id);
        var form_data = new FormData(form_ele);

        $.ajax({
            url: "{-- route('uploaddocument') --}",
            type: 'post',
            data: form_data,
            dataType: 'json',
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response){
                notifyResponseTimerAlert(response.message,response.type,response.type.charAt(0).toUpperCase()+ response.type.slice(1),3000);
                if(response.type == "success"){
                    if(stage_num != "" && stage_num != "undefined" && stage_num != null 
                        && step_key != "" && step_key != "undefined" && step_key != null)
                    {
                        setTimeout(function(){
                            loadStepContent(step_key);
                        },3000);
                    }
                }
                else
                {
                    hide_inner_loader(".timeline_stp_desc","#all_tab_data");
                }
            },
        });
    }

    function updateSkype(ele){
        show_inner_loader(".timeline_stp_desc","#all_tab_data");

        var url = "{-- route('updateskype') --}";
        var formdata = $("#update_skype").serialize();
        var data = { 
                        _token: "{-- csrf_token() --}",
                        formdata: formdata,
                    }

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            dataType: 'json',
            /*statusCode: {
                404: function(jqXHR,textStatus,errorThrown) {
                    alert("Page not found");
                },
                500: function(jqXHR,textStatus,errorThrown) {
                    alert("Internal server error");
                },
            }, */
            success: function(response) {
                if(response.type == "success")
                {
                    if(response.data != "" && response.data != "undefined" && response.data != null)
                    {

                    }
                    else {

                    }
                }
                hide_inner_loader('.timeline_stp_desc',"#all_tab_data");
            },

        });
        return false;
    }

    function loadAddInfoContent(addinfo_step){

        if(active_stage == 1 && active_step == 8)
        {
            $('.addinfo_tab').addClass('hide');
            $('#addinfo_tab'+addinfo_step).removeClass('hide');
        }
        else if(active_stage == 1 && active_step != 8)
        {
            loadStepContent('1_additional_info',function(){
                loadAddInfoContent(addinfo_step);
            });
        }
    }

    function nextAddInfoStep(mainTab,currentID,LastId){

        var NextID = Number(currentID) + Number(1);

        var next_id = "addinfo_tab"+NextID;

        $(".addinfo_tab").addClass('hide');
        $(".addinfo_notify").html("").removeClass();
        $("#"+next_id).removeClass('hide');

        $('#addinfo_list_'+currentID).addClass('done').prepend('<i class="fa fa-check text-success m-r-5"></i>');
    }

    function backAddInfoStep(mainTab,currentID,LastId){

        var NextID = Number(currentID) - Number(1);

        var next_id = "addinfo_tab"+NextID;

        $(".addinfo_tab").addClass('hide');
        $(".addinfo_notify").html("").removeClass();
        $("#"+next_id).removeClass('hide');
    }

    /* Start Function Left Sidebar(toggle) */
    function toggle_sidebar(type) {
        var width = this.screen.width;
        var toggleclass = "showhidetimelineicon";
        if (width >= 768) {
            $("#"+type+" .timeline_stps").toggleClass(toggleclass); 
        }
    }

   /** Display toast in J1 Resume Approval Tab**/
    function resume_approval_toast(){
        $.toast().reset('all');
        $.toast({
           text: 'New update to your application. Unfortunately your resume needs a tweak.',
           position: 'bottom-left',
           icon: 'info',
           hideAfter: 3000,
           stack: 6
       }); 
    }

    /** Display toast in J1 INTERVIEW Tab **/
    function itn_interview_toast(){ 
        $.toast().reset('all');
        $.toast({
           text: 'New update to your application. J1 agreement is ready for your signature.',
           position: 'bottom-left',
           icon: 'info',
           hideAfter: 3000,
           stack: 6
       },
        function(){
            setTimeout(function(){
           //     NextStep(1,5);
            }, 2000); 
        });
    }

    function showUploadInstruction(doc_req){

        var extra_data = { doc_req: doc_req };

        show_popup('modal-lg');
        get_common_ajax("document_instruction","modal-lg",extra_data);

    }

    function viewUploadHistory(doc_type){
        var extra_data = { doc_type: doc_type };

        show_popup('modal-lg');
        get_common_ajax("view_document_history","modal-lg",extra_data);
    }

    /* accordion.js */
    function makeTabsToAccordion() 
    {
        /*$(window).resize(function () { location.reload(); });*/
        var screen = 768;
        if ($(window).width() < screen) 
        {
            var element_id = ".placement_tabs";
            var concat = '';
            obj_tabs = $( element_id + " li" ).toArray();  
            obj_cont = $( ".tab-content .tab-pane" ).toArray();
            jQuery.each( obj_tabs, function( n, val ) 
            { 
                var liClassName = $(this).attr('class');
                concat += '<div id="' + n + '" class="panel panel-default">';
                concat += '<div class="panel-heading ' + liClassName + '" role="tab" id="heading' + n + '">';
                concat += '<h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#application_status_accordion" href="#collapse' + n + '" aria-expanded="false" aria-controls="collapse' + n + '">' + val.innerText + '</a></h4>';
                concat += '</div>';
                concat += '<div id="collapse' + n + '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading' + n + '">';
                concat += '<div class="panel-body">' + obj_cont[n].innerHTML + '</div>';
                concat += '</div>';
                concat += '</div>';
            });
            $("#application_status_accordion").html(concat);
            $("#application_status_accordion").find('.panel-collapse:first').addClass("in");
            $("#application_status_accordion").find('.panel-title a:first').attr("aria-expanded","true");
            $(element_id).remove();
            $(".tab-content").remove();
        }	
    }

    /* Start function for Count Process - Applicaton Staus(Step) Page */
    function totalProgress(Tab_No,Sub_Step_No){

        if(Tab_No != "" && Tab_No != "undefined" && Tab_No != null 
                && Sub_Step_No != "" && Sub_Step_No != "undefined" && Sub_Step_No != null)
        {
            if(Tab_No == 1){ var Tot_Step = 9;} 
            if(Tab_No == 2){ var Tot_Step = 4;} 
            if(Tab_No == 3){ var Tot_Step = 7;} 
            var ProPer = parseInt((Sub_Step_No*100)/Tot_Step); 
            if(ProPer){
                var ProPer = ProPer+"%";
            }   
            $('#main_stage_'+Tab_No+' .pie_progress').asPieProgress('go', ProPer);
            if(ProPer == "100%"){
                $('#main_stage_'+Tab_No+' .pie_progress__content').append("<i class='fa fa-check font-13 db done-step'></i>");
            }
        }
        else{
            var url = "{{ route('asprogress') }}";

            $.ajax({
                url: url,
                type: "post",
                dataType: "json",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: { action : "get_total_progress" },
                success:function(response){
                    if(response.type == "success"){
                        var stage_data = response.data;
                        var stage = "";
                        for(stage in stage_data)
                        {
                            var stage_alert = stage_data[stage].stage_alert;
                            var stage_num = stage_data[stage].stage_num;
                            var step_progress_percent = stage_data[stage].step_progress_percent;
                            var total_steps = stage_data[stage].total_steps;
                            var total_steps_str = "("+total_steps+" steps)";

                            $('#main_stage_'+stage_num+' .pie_progress').asPieProgress('go', step_progress_percent);
                            $('#main_stage_'+stage_num+' .step-count').html(total_steps_str);

                            if(step_progress_percent == "100%")
                            {
                                $('#main_stage_'+stage_num+' .pie_progress__content').append("<i class='fa fa-check font-13 db done-step'></i>");
                            }
                            if(stage_alert == 1)
                            {
                                $('#main_stage_'+stage_num+' .stage-alert').show();
                            }
                        }
                    }
                }
            });
        }
    }
    /* End function for Count Process - Applicaton Staus(Step) Page */
</script>