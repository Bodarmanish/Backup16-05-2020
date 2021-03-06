@php
    $arrival_timezone_list = @$step_verified_data['arrival_timezone_list'];
    $step_status = @$step_verified_data['step_status'];
    $flight_data = @$step_verified_data['flight_data'];
    $step_success = @$step_verified_data['is_step_success'];
    $class = $step_success == 1 ? "" : "hide";
@endphp
<div class="row"> 
    <div class="col-sm-12">
        <h3>When is your flight?</h3> 
        @if(empty($step_status)) 
            <p>This step is disable until user will reach to step.</p>  
        @else
            <p>Once everything is in place and you have your visa stamp on your passport. <strong>Please provide your flight information.</strong></p>
            <div id="{{ $notify_id }}"></div>
            @if($step_success == 2)
                <div id="flight_info">
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
                    <button type="button" class="btn btn-info" onclick="flightInfofrm('showfrm')">Change Flight Information</button>
                </div>
            @endif
            <div id="flight_info_form" class="row {{$class}}">
                <form name="frm_flight_info" id="frm_flight_info" method="post" class="m-b-10 custom_form">
                    <input type="hidden" name="action" value="flight_info" id="action">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Arrival Airport <small class="text-danger">*</small></label>
                                    <select class="form-control" name="arrival_airport" required="">
                                        <option value="">-- Select Airport --</option>
                                        @if(!empty($airports))
                                        @foreach($airports as $airport)
                                        <option value="{{ $airport->ap_abbr }}" {{ is_selected(@$flight_data->arrival_airport,$airport->ap_abbr) }}>{{ $airport->airport_label }}</option>
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
                                    <input type="text" name="airline" value="{{@$flight_data->airline}}" class="form-control" />
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Flight Number</label>
                            <input type="text" name="flight" value="{{@$flight_data->flight}}" class="form-control" />
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
                                        @if(!empty($timezones))
                                        @foreach($timezones as $zone)
                                        <option value="{{ $zone->zone_id }}" {{ is_selected(@$flight_data->departure_timezone,$zone->zone_id) }}>{{ $zone->zone_label }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Departure Date (User date)</label>
                                    <input  type="text" name="departure_date" class="form-control datetimepicker" value="{{!empty($flight_data->departure_date) ? dateformat($flight_data->departure_date,'m/d/Y H:i A') : ''}}" autocomplete="off"/>
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
                                        <option value="{{ $zone->zone_id }}" {{ is_selected(@$flight_data->arrival_timezone,$zone->zone_id) }}>{{ $zone->zone_label }}</option>
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
                                    <input type="text" name="arrival_date" class="form-control datetimepicker" value="{{!empty(@$flight_data->arrival_date) ? dateformat($flight_data->arrival_date,'m/d/Y H:i A') : ''}}" required autocomplete="off" />
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Additional Info</label>
                            <textarea class="form-control" name="additional_info">{{@$flight_data->additional_info}}</textarea>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-actions">
                            <button type="submit" name="save" class="btn btn-info" value="Save">Submit</button>
                            <button type="reset" class="btn btn-danger" onclick="flightInfofrm('flightdata')">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>

<script>
    function flightInfofrm(type)
    {
        showLoader("#full-overlay");
        if(type == 'showfrm')
        {
            $("#flight_info_form").removeClass( "hide" );
            $("#flight_info").addClass( "hide" );
            hideLoader("#full-overlay");
        }
        else{
            $(".with-errors").empty();
            $("#flight_info_form").find('.has-error').removeClass("has-error");
            $("#flight_info_form").find('.has-success').removeClass("has-success");

            if("{{$step_success}}" == 2)
            {
                $("#flight_info_form").addClass( "hide" );
                $("#flight_info").removeClass( "hide" );
            }
            hideLoader("#full-overlay");
        }
    }
    
    var notify_id = "{{ $notify_id }}";
    $(document).ready(function(){
        
        $( ".datetimepicker" ).keydown(function( event ) {
            return false;
        });
        
        var form_selector = "#frm_flight_info";
        @if(@$is_step_locked == 1)
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
                            notifyResponseTimerAlert(Html,"error","Error");
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