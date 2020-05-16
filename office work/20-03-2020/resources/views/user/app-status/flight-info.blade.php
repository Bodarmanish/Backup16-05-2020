@php
    $airport_list = @$step_verified_data['airport_list'];
    $timezone_list = @$step_verified_data['timezone_list'];
    $arrival_timezone_list = @$step_verified_data['arrival_timezone_list'];
    $step_status = @$step_verified_data['step_status'];
    $flight_data = @$step_verified_data['flight_data'];
@endphp
<div class="row"> 
    <div class="col-sm-12">
        <h3>When is your flight?</h3>  
        <p>Once everything is in place and you have your visa stamp on your passport. <strong>Please provide your flight information.</strong></p>
    </div>
</div>
<div id="{{ $notify_id }}"></div>
@if($step_status == 2)
<div class="alert alert-success">Thanks for providing Flight Arrival Information. If you need to update your arrival information, please contact your administrator to inform him/her about this change.</div>
<table class="table color-table info-table">
    <thead>
        <tr>
            <th><strong>Arrival Airport</strong></th>
            <td>{{ $flight_data->airport_data->airport_label }}</td>
        </tr>
        <tr>
            <th><strong>Airline</strong></th>
            <td>{{ $flight_data->airline }}</td>
        </tr>
        <tr>
            <th><strong>Flight Number</strong></th>
            <td>{{ $flight_data->flight }}</td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <th><strong>Departure Time zone</strong></th>
            <td>{{ $flight_data->dep_timezone_data->zone_label }}</td>
        </tr>
        <tr>
            <th><strong>Departure Date (cand time)</strong></th>
            <td>{{ dateformat($flight_data->departure_date,DISPLAY_DATETIME) }}</td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <th><strong>Arrival Time zone</strong></th>
            <td>{{ $flight_data->arr_timezone_data->zone_label }}</td>
        </tr>
        <tr>
            <th><strong>Arrival Date (US time)</strong></th>
            <td>{{ dateformat($flight_data->arrival_date,DISPLAY_DATETIME) }}</td>
        </tr>
    </thead>
</table>
@elseif($step_status == 1)
<div id="flight_info_form" class="row">
    <form name="frm_flight_info" id="frm_flight_info" method="post" class="m-b-10 custom_form">
        <input type="hidden" name="action" value="flight_info" id="action">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label class="control-label">Arrival Airport <small class="text-danger">*</small></label>
                        <select class="form-control" name="arrival_airport" required="">
                            <option value="">-- Select Airport --</option>
                            @if(!empty($airport_list))
                                @foreach($airport_list as $airport)
                                <option value="{{ $airport->ap_abbr }}">{{ $airport->airport_label }}</option>
                                @endforeach
                            @endif
                        </select>
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label class="control-label">Airline</label>
                        <input type="text" name="airline" value="" class="form-control" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label class="control-label">Flight Number</label>
                <input type="text" name="flight" value="" class="form-control" />
                <div class="help-block with-errors"></div>
                <div class="form-control-feedback"></div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label class="control-label">Departure Timezone</label>
                        <select name="departure_timezone" id="departure_timezone" class="form-control">
                            <option value="">-- Select Timezone --</option>
                            @if(!empty($timezone_list))
                                @foreach($timezone_list as $zone)
                                    <option value="{{ $zone->zone_id }}">{{ $zone->zone_label }}</option>
                                @endforeach
                            @endif
                        </select>
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label class="control-label">Departure Date (cand date)</label>
                        <input  type="text" name="departure_date" class="form-control datetimepicker" value="" autocomplete="off"/>
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label class="control-label">Arrival Timezone <small class="text-danger">*</small></label>
                        <select name="arrival_timezone" id="arrival_timezone" class="form-control" required>
                            <option value="">-- Select Timezone --</option>
                            @if(!empty($arrival_timezone_list))
                                @foreach($arrival_timezone_list as $zone)
                                    <option value="{{ $zone->zone_id }}">{{ $zone->zone_label }}</option>
                                @endforeach
                            @endif
                        </select>
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label class="control-label">Arrival Date (US date) <small class="text-danger">*</small></label>
                        <input type="text" name="arrival_date" class="form-control datetimepicker" value="" required autocomplete="off" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label class="control-label">Additional Info</label>
                <textarea class="form-control" name="additional_info"></textarea>
                <div class="help-block with-errors"></div>
                <div class="form-control-feedback"></div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-actions">
                <button type="submit" name="save" class="btn btn-sm btn-info" value="Save">Submit</button>
            </div>
        </div>
    </form>
</div>
@endif

@if(($step_status == 2 && !empty($next_step_key)) || $user->is_timeline_locked == 1)
    <button type="button" class="btn btn-info" onclick="loadStepContent('{{ $next_step_key }}')">Next Step</button>
@endif
<script>
    var notify_id = "{{ $notify_id }}";
    $(document).ready(function(){
        
        $( ".datetimepicker" ).keydown(function( event ) {
            return false;
        });
        
        var form_selector = "#frm_flight_info";
        @if($user->is_timeline_locked == 1)
            $(form_selector)
                .find('input, select, textarea, button[type=submit]')
                .attr("disabled",true);
        @else
            ajaxFormValidator(form_selector,function(ele,event){
                event.preventDefault();
                clearValidatorErr(ele);
                showLoader("#full-overlay");

                var url = " {{ route('visa.stage') }} ";
                var user_id = $('meta[name="user_token"]').attr('content');
                var form_data = new FormData(ele);
                form_data.append('user_id',user_id);

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
                        if(response.type == "success"){
                            swal({
                                title: "",   
                                text: "Thank you for submitting your flight information.",   
                                type: "success",
                                showCancelButton: true,
                                cancelButtonText: "Change Schedule?",
                                confirmButtonColor: "#1faae6",
                                confirmButtonText: "Let's Go to Next Step",
                            },function(btn){
                                if(btn === true){
                                    navigateStages('3','{{ $next_step_key }}');
                                }
                            });
                        }
                        else if(response.type == "validation_error"){
                            var Html = '<div class="alert swl-alert-danger"><ul>'; 
                            $.each( messages, function( key, value ) {
                                Html += '<li>' + value+ '</li>';  
                            });
                            Html += '</ul></div>';  
                            notifyResponseTimerAlert(Html,"error","Error",10000);
                        }
                        else{
                            notifyResponse("#"+notify_id,messages,response.type);
                        }
                        hideLoader("#full-overlay");
                    },
                });
            });
        @endif
    });

</script>