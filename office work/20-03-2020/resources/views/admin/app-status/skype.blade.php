@php
    $skype_id = (!empty($step_verified_data['skype_id']))?@$step_verified_data['skype_id']:"";
    $skype_save_class = (!empty($skype_id))?"hidden":"";
    $skype_edit_class = (!empty($skype_id))?"":"hidden";
@endphp
<div class="row">
    <div class="col-xs-12">
        <h3>User Skype ID</h3> 
        <p>The interview will take place using Skype.</p>
    </div>
    @if(empty($step_status))
    <div class="col-xs-12">
        <p>This step is disable until user will reach to step.</p>
    </div>
    @else
        <div id="{{ $notify_id }}" class="col-xs-12"></div>
        <form name="frm_update_skype" id="frm_update_skype">
            <div class="col-xs-12">
                <div class="form-group">
                    <label class="control-label"></label>
                    <input type="text" name="skype_id" id="skype_id" value="{{ $skype_id }}" class="form-control" placeholder="Click here to add your Skype ID" data-nowhitespace="nowhitespace" required="" @if(!empty($skype_id)) disabled @endif> 
                    <div class="help-block with-errors font-11"></div> 
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
            <small class="text-muted">(Next Step: Schedule an interview with J1)</small>
            <p>With Skype you can make video and audio calls, exchange chat messages and do much more using Skype's software on your computer, mobile phone, tablet and other devices.</p>
        </div>
        @endif
    @endif
</div> 
<script>
    var notify_id = "{{ $notify_id }}";
    $(document).ready(function(){

        var form_selector = "#frm_update_skype";
        var user_id = $('meta[name="user_token"]').attr('content');
        
        @if($is_step_locked == 1)
            $(form_selector)
                .find('input, select, textarea, button[type=submit]')
                .attr("disabled",true);
        @else
            ajaxFormValidator(form_selector,function(ele,event){

                event.preventDefault();

                showLoader("#full-overlay");

                var form_data = new FormData(ele);
                form_data.append('user_id',user_id);
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
                            setTimeout(function(){ navigateStages('1','{{ $active_step_key }}'); }, 3000);
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
                        hideLoader("#full-overlay");
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
</script>