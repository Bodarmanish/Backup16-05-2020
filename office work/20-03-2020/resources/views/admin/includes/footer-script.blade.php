
<!-- Menu Plugin JavaScript -->
<script src="{{ asset($plugin_path.'sidebar-nav/dist/sidebar-nav.min.js') }}"></script>
<!--slimscroll JavaScript -->
<script src="{{ asset($js_path.'jquery.slimscroll.js') }}"></script>
<!--Wave Effects -->
<script src="{{ asset($js_path.'waves.js') }}"></script>
<!-- Custom Theme JavaScript -->
<script src="{{ asset($js_path.'custom.min.js') }}"></script> 
<!--Style Switcher -->
<script src="{{ asset($plugin_path.'styleswitcher/jQuery.style.switcher.js') }}"></script>
<script src="{{ asset($plugin_path.'switchery/dist/switchery.min.js') }}"></script>

<!-- Validator JavaScript -->
<script src="{{ asset($js_path.'validator.js') }}"></script> 
<!--DataTable Js-->
<script src="{{ asset($plugin_path.'datatables/jquery.dataTables.min.js') }}"></script>
<!-- Jquery Sortable  -->
<script src="{{ asset($js_path.'jquery-sortable.js') }}"></script>
<!-- Sweet-Alert  -->
<script src="{{ asset($plugin_path.'sweetalert/sweetalert.min.js') }}"></script>
<!-- Bootstrap Tokenfield JavaScript  -->
<script type="text/javascript" src="{{ asset($plugin_path.'bootstrap-tokenfield/bootstrap-tokenfield.js') }}" charset="UTF-8"></script>
<!-- Typeahead Bundle JavaScript  -->
<script type="text/javascript" src="{{ asset($plugin_path.'typeahead.js-master/dist/typeahead.bundle.min.js') }}" charset="UTF-8"></script>
<!-- Jquery UI JavaScript  -->
<script type="text/javascript" src="{{ asset($plugin_path.'jquery-ui/jquery-ui.js') }}" charset="UTF-8"></script>
<!-- Bootstrap Core JavaScript -->
<script src="{{ asset($css_path.'bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- Jquery ckeditor  -->
<script type="text/javascript" src="{{ asset($plugin_path.'templateEditor/ckeditor/ckeditor.js') }}" charset="UTF-8"></script>
<!-- Image crop js -->
<script src="{{ asset($plugin_path.'croppic/croppic.js') }}"></script>

<!-- Date Picker Plugin JavaScript -->
<script src="{{ asset($plugin_path.'moment/moment.js') }}"></script>
<script src="{{ asset($plugin_path.'bootstrap-datetimepicker-master/js/bootstrap-admin-datetimepicker.min.js') }}"></script>
<script src="{{ asset($plugin_path.'bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>

<!-- Stylish Tab js -->
<script src="{{ asset($js_path.'cbpFWTabs.js') }}"></script>

<!-- Start javascript for Drop-file/image -->
<script src="{{ asset($plugin_path.'dropify/dist/js/dropify.min.js') }}"></script>

<!-- Custom admin js --> 
<script src="{{ asset($js_path.'custom-admin.js') }}"></script>

<script>
function deleteDocument(doc_id,callback_func) {
    showLoader("#full-overlay");
    confirmAlert("On confirm document will be deleted.","warning","Are you sure?","Confirm",function(r,i){
        if(i){
            documentAction(doc_id,"delete",callback_func);
        }
        else{
            hideLoader("#full-overlay");
        }
    });
};

function documentAction(doc_id,action_type,callback_func){
    if(callback_func == "" || callback_func == "undefined" || callback_func == null)
        callback_func = "";

    var user_id = $('meta[name="user_token"]').attr('content');
    
    showLoader("#full-overlay");
    $.ajax({
        url:  "{{ route('document.action') }}",
        type: 'post',
        data: {  
            'action_type': action_type,
            'doc_id': doc_id,
            'user_id': user_id,
        },
        dataType: 'json',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response){
            if(response.type == "success"){
                var Html = '<div class="alert swl-alert-success"><ul><li>'+response.message+'</li></ul></div>';
                notifyResponseTimerAlert(Html,"success","Success");
                if(typeof callback_func === "function"){
                    callback_func();
                }
            }
            else
            {
                var Html = '<div class="alert swl-alert-danger"><ul><li>'+response.message+'</li></ul></div>';
                notifyResponseTimerAlert(Html,"success","Success");
            }
            hideLoader("#full-overlay");
        },
    });
} 
         
function rejectDocumentReason(url,doc_id,active_step_key,active_stage_key){
    show_popup();
    get_common_ajax(url,{
        action: "reject_document_reason_form", 
        doc_id: doc_id,
        active_stage_key: active_stage_key,
        active_step_key: active_step_key
    });
}
 
function uploadDocument(doc_type,callback_func){
    if(callback_func == "" || callback_func == "undefined" || callback_func == null)
        callback_func = "";
    
    showLoader("#full-overlay");
    var form_id = "frm_upload_"+doc_type;
    var form_ele = document.getElementById(form_id);
    var form_data = new FormData(form_ele);
    var user_id = $('meta[name="user_token"]').attr('content');
    form_data.append('user_id',user_id);
    
    $.ajax({
        url: "{{ route('uploaddocument') }}",
        type: 'post',
        data: form_data,
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response){
            notifyResponseTimerAlert(response.message,response.type,response.type.charAt(0).toUpperCase()+ response.type.slice(1),3000);
            if(response.type == "success"){
                if(typeof callback_func === "function"){
                    callback_func();
                }
            }
            hideLoader("#full-overlay");
        },
    });
}

function cancelInterview(active_step_key){
    showLoader("#full-overlay");
    confirmAlert("You have not completed all the steps of the J1 Interview Schedule process.","warning","Do you want to continue now?","Confirm",function(r,i){
        if(i){
            navigateStages(1,active_step_key);
        }
        else{
            hideLoader("#full-overlay");
        }
    });
}

function showUploadInstruction(doc_req_id){
    show_popup();
    get_common_ajax("{{ route('upload.document.instruction') }}",
    { 
        doc_req_id: doc_req_id 
    });
}

function viewUploadHistory(doc_type_id){
    var user_id = $('meta[name="user_token"]').attr('content');
    show_popup();
    get_common_ajax("{{ route('document.history') }}",
    {
        doc_type_id: doc_type_id,
        user_id: user_id
    });
}

function store(name, val) {
    if (typeof (Storage) !== "undefined") {
        localStorage.setItem(name, val);
    } else {
        window.alert('Please use a modern browser to properly view this template!');
    }
}

$(document).ready(function () {
    var currentStyle = 'default';
    $("*[data-theme]").click(function (e) {
        e.preventDefault();
        currentStyle = $(this).attr('data-theme');
        store('theme', currentStyle); 
        $('#theme').attr({
            href: '{{ asset("assets/css/colors/") }}/'+currentStyle+'.css'
        });
        $.ajax({
            type: "post",
            url: '{{ route("setcolor") }}',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                'setcolor':currentStyle
            },
            success: function(data){

            },
            error: function(data){
                alert("Error")
            }
        });
        var currentTheme = get('theme');
        if (currentTheme) {
            $('#theme').attr({
                href: '{{ asset("assets/css/colors/") }}' + currentTheme + '.css'
            });
        }
    });
    /* color selector */
    $('#themecolors').on('click', 'a', function () {
        $('#themecolors li a').removeClass('working');
        $(this).addClass('working')
    });
});
</script>