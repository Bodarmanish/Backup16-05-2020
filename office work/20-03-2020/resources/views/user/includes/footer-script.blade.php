    <!-- Menu Plugin JavaScript -->
    <script src="{{ asset($plugin_path.'sidebar-nav/dist/sidebar-nav.min.js') }}"></script> 
    <!--slimscroll JavaScript -->
    <script src="{{ asset($js_path.'jquery.slimscroll.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset($js_path.'waves.js') }}"></script>
    <!-- Start javascript for Animated scroll to top -->
    <script src="{{ asset($js_path.'animatescroll.js') }}"></script>
    
    <!-- Sweet-Alert  -->
    <script src="{{ asset($plugin_path.'sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset($plugin_path.'sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <!-- Custom Theme JavaScript -->
    <script src="{{ asset($js_path.'validator.js') }}"></script> 
    <!-- Form validator Example: http://1000hz.github.io/bootstrap-validator/ -->
    
    <!--Style Switcher -->
    <script src="{{ asset($plugin_path.'switchery/dist/switchery.min.js') }}"></script>
    <script src="{{ asset($plugin_path.'styleswitcher/jQuery.style.switcher.js') }}"></script>
    <script src="{{ asset($js_path.'chat.js') }}"></script>
    
    <!-- Start javascript for Calendar display --> 
    <script src="{{ asset($plugin_path.'moment/moment.js') }}"></script>
    <script src="{{ asset($plugin_path.'moment-timezone/moment-timezone.js') }}"></script>
    <script src='{{ asset($plugin_path.'calendar/dist/fullcalendar.min.js') }}'></script>
    <script src="{{ asset($plugin_path.'calendar/dist/cal-init.js') }}"></script>
    <script src="{{ asset($plugin_path.'calendar/dist/jquery.fullcalendar.js') }}"></script>
    <!-- End javascript for Calendar display -->
    
    <!-- Date Picker Plugin JavaScript -->
    <script src="{{ asset($plugin_path.'bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset($plugin_path.'bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js') }}"></script>
    <!-- Notification JavaScript -->
    <script src="{{ asset($plugin_path.'toast-master/js/jquery.toast.js') }}"></script>
    <!-- Start javascript for Block Content(loader/overlay) -->
    <script src="{{ asset($plugin_path.'blockUI/jquery.blockUI.js') }}"></script>
    <!-- End javascript for Block Content(loader/overlay) -->
    
    <!-- Start javascript for Circle Progress -->
    <script src="{{ asset($js_path.'jquery-asPieProgress.js') }}"></script>
    <!-- End javascript for Circle Progress -->
    
    <!-- Start javascript for Drop-file/image -->
    <script src="{{ asset($plugin_path.'dropify/dist/js/dropify.min.js') }}"></script>
    <!-- End javascript for Drop-file/image -->
    
    <!-- Start javascript for Start-Timer -->
    <script src="{{ asset($js_path.'jquery.countdown.min.js') }}"></script>
    <!-- End javascript for Start-Timer -->
    
    <!-- Typehead Plugin JavaScript -->
    <script src="{{ asset($plugin_path.'typeahead.js-master/dist/typeahead.bundle.min.js') }}"></script>
    <script src="{{ asset($plugin_path.'typeahead.js-master/dist/typeahead-init.js') }}"></script>
    
    <!-- Profile picture crop js -->
    <script src="{{ asset($plugin_path.'croppic/croppic.js') }}"></script>
    
    <!-- Form Wizard JavaScript -->
    <script src="{{ asset($plugin_path.'jquery-wizard-master/dist/jquery-wizard.min.js') }}"></script>
    
    <!-- Infinite Scroll js --> 
    <script src="{{ asset($js_path.'jquery.jscroll.min.js') }}"></script>
    
    <!-- Custom js --> 
    <script src="{{ asset($js_path.'custom.js') }}"></script> 
    <script src="{{ asset($js_path.'custom-user.js') }}"></script>
    
    <script type="text/javascript">
        $(document).ready(function () {
            load_datepicker();
            load_datetimepicker();
            load_timepicker();
            load_switch();
            
            
            /*start ajax login validation*/
            ajaxFormValidator("#model_loginform",checkLogin);

            function checkLogin(element,e){
                e.preventDefault();
                var formData = new FormData(element);
                $.ajax({
                    type: 'post',
                    url: "{{route('login')}}", 
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response.type=='error')
                        {
                            var messages = response.message;
                            if( "login_error" in messages){
                               $( "#login_error" ).removeClass("hide")
                               $( "#login_error" ).empty().text(messages.login_error);
                            }else{
                                $( "#login_error" ).addClass("hide")
                                serverValidator(element,messages);
                            }
                        }                       
                        else{ 
                            location.reload(true);
                        }
                    }
                }); 
            }  
            
            /*end ajax login validation*/
        });
    
    function localDateTime(date){
        var gmtDateTime = moment.utc(date, "YYYY-MM-DD HH:mm")
        var local = gmtDateTime.local().format('YYYY-MM-DD HH:mm');
        return local;
    }
    
    /*START FORUM PAGES FUNCTIONS*/
    function selectSubCategory(parentID){ 
        $("#forumsubcat").html('');
        $("#forumtag").html('');
        $.ajax({
            type: 'post',
            url: "{{route('forumajaxrequest')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { 
                'catId': parentID, 
                'action': 'getSubCatByParentCatId'
            }, 
            dataType: 'json',
            success: function(response) {
                if(response.type=='success')
                {
                    var Html = response.data;
                    $( "#forumsubcat" ).html(Html); 
                } 
            }
        });
    }
    
    
    function selectForumTagList(subCatId){
        $("#forumtag").html('');
        $.ajax({
            type: 'post',
            url: "{{route('forumajaxrequest')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { 
                'subCatId': subCatId, 
                'action': 'ForumTagListBySubCatId'
            },
            dataType: 'json', 
            success: function(response) {
                if(response.type=='success')
                {
                    var Html = response.data;
                    $( "#forumtag" ).html(Html); 
                } 
            }
        });
    }
    
    function addFavoriteMenu(topicId,status,element){ 
        $.ajax({
            type: 'post',
            url: "{{route('forumajaxrequest')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }, 
            data: {  
                'topicId': topicId,
                'status': status,
                'action': 'addToFavorite'
            },
            dataType: "json",
            success: function(response) {  
                if(response.type=="success"){
                    $("#favorite_menu_"+response.data.topicId).removeClass("hide"); 
                    $("#favorite_menu_"+response.data.topicId).removeClass("hidden"); 
                    $("#favorite_topic_"+response.data.topicId).empty();
                    if(status==1){ 
                        $(element).closest("li").html('<a href="javascript:void(0)" role="menuitem" onclick="addFavoriteMenu(\''+topicId+'\',0,this)">Remove from Favorites</a>'); 
                        $("#favorite_topic_"+response.data.topicId).append(response.data.html);
                    }else{
                        $(element).closest("li").html('<a href="javascript:void(0)" role="menuitem" onclick="addFavoriteMenu(\''+topicId+'\',1,this)">Add to Favorites</a>');
                         $("#favorite_topic_"+response.data.topicId).append(response.data.html);
                    } 
                }
            }
        }); 
    }

    function popupReportTopic(topicId)
    {
        var extra_data = { topicId: topicId, action: "reportTopic" };
        show_popup('modal-md');
        get_common_ajax("{{route('forumajaxrequest')}}",extra_data,'modal-md'); 
    }

    function submitReport(topicId,element)
    {
        var reason = $(element).closest("div").text();
        $.ajax({
            type: 'post',
            url: "{{route('forumajaxrequest')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }, 
            data: {  
                'topicId': topicId,
                'reason': reason,
                'action': 'submitReport'
            },
            dataType: "json",
            success: function(response) { 
                if(response.type=="success"){
                    reportThanks(topicId);
                }
            }
        });
    }

    function undoReport(topicId){
        $.ajax({
            type: 'post',
            url: "{{route('forumajaxrequest')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }, 
            data: {  
                'topicId': topicId, 
                'status': 0, 
                'action': 'submitReport'
            },
            dataType: "json",
            success: function(response) { 
                if(response.type=="success"){
                    $("#topicId_"+topicId).removeClass("hide");
                    $("#report_topic_"+topicId).addClass("hide");
                }
            }
        }); 
    }

    function reportThanks(topicId){
        swal({   
           title: "Thanks",   
           text: "Thanks for your feedback! Your response will help us show you better ads.",
           confirmButtonColor: "#1faae6",
           confirmButtonText: "Done",
           closeOnConfirm: true
       },
       function(){
            hide_popup("modal-md");
            $("#topicId_"+topicId).addClass("hide");
            $("#report_topic_"+topicId).removeClass("hide");
       });
    }  

    function notifyTopics(topicId,status,element){ 
        $.ajax({
            type: 'post',
            url: "{{route('forumajaxrequest')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }, 
            data: {  
                'topicId': topicId,
                'status': status,
                'action': 'notifyTopics'
            },
            dataType: "json",
            success: function(response) { 
                if(response.type=="success"){ 
                    if(status==1){ 
                        $(element).closest("li").html('<a href="javascript:void(0)" role="menuitem" onclick="notifyTopics(\''+topicId+'\',0,this);">Turn off notifications for this topic</a>');
                        $("#off_topic_notify_"+topicId).removeClass("hide");
                        $("#on_topic_notify_"+topicId).addClass("hide");
                    }else{
                        $(element).closest("li").html('<a href="javascript:void(0)" role="menuitem" onclick="notifyTopics(\''+topicId+'\',1,this);">Turn on notifications for this topic</a>');
                        $("#on_topic_notify_"+topicId).removeClass("hide");
                        $("#off_topic_notify_"+topicId).addClass("hide");
                    }  
                }
            }
        }); 
    }


    function deleteComment(commentId,topicId){

        showLoader("#full-overlay");
        confirmAlert('',"On confirm comment will be deleted.","warning","Are you sure?","Confirm",function(r,i){
            if(i){
            $.ajax({
                type: 'post',
                url: "{{route('forumajaxrequest')}}",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }, 
                data: {  
                    'commentId': commentId,
                    'topicId': topicId,
                    'action': 'deleteComment'
                },
                dataType: "json",
                success: function(response) { 

                    if(response.type=="success"){ 
                        $("#"+commentId).addClass("hide");
                        if(typeof topicId !== "undefined" && topicId)
                        {
                            $("#comment_count").html(response.topic_comment_count);
                        }
                    }
                }
            }); 
            }
            else{
                hideLoader("#full-overlay");
            }
        });
    }
    /*END FORUM PAGES FUNCTIONS*/
    </script>
    
    