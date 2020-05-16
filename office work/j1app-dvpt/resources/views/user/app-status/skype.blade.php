@php
    $skype_id = (!empty($step_verified_data['skype_id']))?@$step_verified_data['skype_id']:"";
    $skype_save_class = (!empty($skype_id))?"hidden":"";
    $skype_edit_class = (!empty($skype_id))?"":"hidden";
    $step_status = @$step_verified_data['step_status'];
@endphp
<div class="row">
    <div class="col-xs-12">
        <h3>What is your Skype ID?</h3> 
        <p>The interview will take place using Skype.</p>
    </div>
    <div id="{{ $notify_id }}" class="col-xs-12"></div>
    <form name="frm_update_skype" id="frm_update_skype">
        <div class="col-xs-12">
            <div class="form-group">
                <label class="control-label"></label>
                <input type="text" name="skype_id" id="skype_id" value="{{ $skype_id }}" class="form-control" placeholder="Click here to add your Skype ID" data-nowhitespace="nowhitespace" required="" @if(!empty($skype_id)) disabled @endif> 
                <div class="help-block with-errors font-11"></div>
                <div class="form-control-feedback"></div>
            </div>
        </div>  
        <div class="col-xs-12">
            <div class="form-actions">
                @if($is_step_locked != 1)
                <button type="submit" name="skype_submit" id="skype_submit" class="btn btn-md btn-info m-b-10 {{ $skype_save_class }}">Save</button>
                <button type="button" name="skype_edit" id="skype_edit" class="btn btn-md btn-info m-b-10 {{ $skype_edit_class }}">Edit</button>
                @endif
            </div>
        </div>
    </form>
    
    @if(!empty($skype_id) && ($step_status == 1 || $step_status == 2))
    <div class="col-xs-12 m-b-20">
        @if(!empty($next_step_key) && $step_status == 2)
            <button type="button" class="btn btn-info" onclick="navigateStages('1','{{ $next_step_key }}')">Next Step</button>
        @endif
        <small class="text-muted">(Next Step: Schedule an interview with J1)</small>
        <p>With Skype you can make video and audio calls, exchange chat messages and do much more using Skype's software on your computer, mobile phone, tablet and other devices. </p>
    </div>
    @endif
    <div class="col-xs-12">
        <hr class="full_dotted_line m-b-20" />
        <p>Don't have Skype? <a href="https://www.skype.com/en/download-skype/skype-for-computer/" class="text-info" target="_blank">Download Now... </a></p>
        <p>After you download Skype from the link above, please follow the steps below:</p>
        <ul>
            <li>Setup Skype and create a user ID and password.</li>
            <li>After you have created your Skype ID, come to this page and add your new ID in the field above.</li>
            <li>Click 'Save' button and you will be redirected to the new page.</li>
        </ul>
    </div>
</div> 
<script>
    var notify_id = "{{ $notify_id }}";
    $(document).ready(function(){

        var form_selector = "#frm_update_skype";
        
        @if($is_step_locked == 1)
            $(form_selector)
                .find('input, select, textarea, button[type=submit]')
                .attr("disabled",true);
        @else
            ajaxFormValidator(form_selector,function(ele,event){

                event.preventDefault();

                show_inner_loader(".timeline_stp_desc","#all_tab_data");

                var form_data = new FormData(ele);
                var url = "{{ route('updateskype') }}";

                $.ajax({
                    url: url,
                    type: 'post',
                    data: form_data,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response){
                        var messages = response.message;
                        if(response.type == "success")
                        {
                            notifyResponseTimerAlert(messages,response.type,response.type.charAt(0).toUpperCase()+ response.type.slice(1));
                            notifyResponse("#"+notify_id,messages,response.type);
                            $("#skype_submit").addClass('hidden');
                            $("#skype_id").prop('disabled',true);
                            setTimeout(function(){ navigateStages('1','{{ $next_step_key }}'); }, 3000);
                        }
                        else if(response.type == "validation_error"){
                            var Html = '<div class="alert swl-alert-danger"><ul>'; 
                            $.each( messages, function( key, value ) {
                                Html += '<li>' + value+ '</li>';  
                            });
                            Html += '</ul></div>';  
                            notifyResponseTimerAlert(Html,"error","Error");
                        }
                        else{
                            notifyResponse("#"+notify_id,messages,response.type);
                        }
                        hide_inner_loader('.timeline_stp_desc',"#all_tab_data");
                    },
                });
            });

            $("#skype_edit").click(function(){
                $("#skype_submit").removeClass('hidden');
                $("#skype_id").prop('disabled',false);
                $("#skype_edit").addClass('hidden');
            });
        @endif
    });
    function setNextStep(){
        $("#skype_id").removeAttr('disabled');
        $("#frm_update_skype").submit();
    }
</script>