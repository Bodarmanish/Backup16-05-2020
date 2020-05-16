@php 
    $interview_data = @$step_verified_data['interview_data']; 
    $position_types = @$step_verified_data['position_types'];
    $program_length = config('common.program_length'); 
    $english_level = config('common.english_level');
@endphp

<h3>J1 Interview</h3> 
<p>The purpose of our pre-screening interview is to evaluate your English level and gather additional information regarding your application.</p>
<div class="row"> 
    <div class="col-sm-12">
        @if(empty($step_status)) 
            <p>This step is disable until user will reach to step.</p>  
        @else
            <div class="panel-group" id="interview" aria-multiselectable="true" role="tablist"> 
                @if($is_step_success==2 && !empty($interview_data))
                <div class="row"> 
                    <div class="col-md-12">
                        <h3>Admin Detail</h3>
                    </div> 
                </div>

                <div class="row">
                    <div class="col-md-6 col-xs-12 b-r"> <strong>Interview DateTime</strong>
                        <br>
                        <p class="text-muted"> 
                            {{ (strtotime($interview_data->date_interview_admin) > 0)?dateformat($interview_data->date_interview_admin, DISPLAY_DATETIME):'N/A' }} 
                        </p>
                    </div>
                    <div class="col-md-6 col-xs-12 b-r"> <strong>Interview Timezone</strong>
                        <br>
                        <p class="text-muted">
                            {{ !empty($interview_data->admin_timezone_name)?$interview_data->admin_timezone_name:'N/A' }}
                        </p>
                    </div> 
                </div> 
                <hr/>
                <div class="row"> 
                    <div class="col-md-12">
                        <h3>User Detail</h3>
                    </div> 
                </div> 
                <div class="row">
                    <div class="col-md-4 col-xs-12 b-r"> <strong>Interview DateTime</strong>
                        <br>
                        <p class="text-muted">{{ (strtotime($interview_data->date_interview_user) > 0)? dateformat($interview_data->date_interview_user, DISPLAY_DATETIME):'N/A' }}</p>
                    </div>
                    <div class="col-md-4 col-xs-12 b-r"> <strong>Interview Timezone</strong>
                        <br>
                        <p class="text-muted">
                            {{ !empty($interview_data->user_timezone_name)?$interview_data->admin_timezone_name:'N/A' }} 
                        </p>
                    </div>
                    <div class="col-md-4 col-xs-12 b-r"> <strong>Interview Scheduled By</strong>
                        <br>
                        <p class="text-muted">
                            {{ (!empty($interview_data->interview_schedule_admin->first_name))?$interview_data->interview_schedule_admin->first_name:'' }}
                            {{ (!empty($interview_data->interview_schedule_admin->last_name))?$interview_data->interview_schedule_admin->last_name:'' }}
                        </p>
                    </div> 
                </div>

                <div class="row">
                    <div class="col-md-4 col-xs-12 b-r"> <strong>Graduation Date</strong>
                        <br>
                        <p class="text-muted">{{ dateformat($interview_data->graduation_date, DISPLAY_DATE) }}</p>
                    </div>
                    <div class="col-md-4 col-xs-12 b-r"> <strong>Availability Date</strong>
                        <br>
                        <p class="text-muted">
                            {{ dateformat($interview_data->availability_date, DISPLAY_DATE) }}
                        </p>
                    </div>
                    <div class="col-md-4 col-xs-12 b-r"> <strong>Availability Type</strong>
                        <br>
                        <p class="text-muted">
                            @if($interview_data->availability_type==1)
                            Flexible
                            @elseif($interview_data->availability_type==2)
                            Mandatory
                            @elseif($interview_data->availability_type==3)
                            No later than
                            @endif 
                        </p>
                    </div> 
                </div>

                <div class="row">
                    <div class="col-md-4 col-xs-12 b-r"> <strong>Program Length</strong>
                        <br>
                        <p class="text-muted">{{ $interview_data->preferred_program_length }}</p>
                    </div>
                    <div class="col-md-4 col-xs-12 b-r"> <strong>Training Position 1</strong>
                        <br>
                        <p class="text-muted">
                            @foreach(@$position_types as $pt)
                                @if($pt->id == $interview_data->preferred_position_1)
                                {{ $pt->position_type_name }}
                                @endif 
                            @endforeach
                        </p>
                    </div>
                    <div class="col-md-4 col-xs-12 b-r"> <strong>Training Position 2</strong>
                        <br>
                        <p class="text-muted">
                            @foreach($position_types as $pt)
                                @if($pt->id == $interview_data->preferred_position_2)
                                {{ $pt->position_type_name }}
                                @endif 
                            @endforeach
                        </p>
                    </div> 
                </div> 

                <div class="row">
                    <div class="col-md-4 col-xs-12 b-r"> <strong>English Level</strong>
                        <br>
                        <p class="text-muted">{{ (!empty($interview_data->english_level))?$english_level[$interview_data->english_level]["title"]:"" }}</p>
                    </div>
                    <div class="col-md-4 col-xs-12 b-r"> <strong>Has Passport</strong>
                        <br>
                        <p class="text-muted">
                            {{ ($interview_data->has_passport==1)?'Yes':'No' }}
                        </p>
                    </div>
                    <div class="col-md-4 col-xs-12 b-r"> <strong>Previous US Visas</strong>
                        <br>
                        <p class="text-muted">
                            {{ ($interview_data->previous_us_visas==1)?'Yes':'No' }}
                        </p>
                    </div> 
                </div>

                <div class="row">
                    <div class="col-md-4 col-xs-12 b-r"> <strong>Registration Fee Status</strong>
                        <br>
                        <p class="text-muted">{{ ($interview_data->reg_fee_status==1)?"($50) - Charge":"($50) - Postpone" }}</p>
                    </div>
                    <div class="col-md-4 col-xs-12 b-r"> <strong>Interviewed By</strong>
                        <br>
                        <p class="text-muted">
                            {{ (!empty($interview_data->interviewed_admin->first_name))?$interview_data->interviewed_admin->first_name:'' }}
                            {{ (!empty($interview_data->interviewed_admin->last_name))?$interview_data->interviewed_admin->last_name:'' }}
                        </p>
                    </div>
                    <div class="col-md-4 col-xs-12 b-r"> <strong>Interview Status</strong>
                        <br>
                        <p class="text-muted">
                            @if($interview_data->interview_status==1)
                            Interview Scheduled
                            @elseif($interview_data->interview_status==2)
                            Interview Finished
                            @else
                            Pending To Review
                            @endif
                        </p>
                    </div> 
                </div>
                
                <div class="row">
                    <div class="col-md-4 col-xs-12 b-r"> <strong>Interview Date</strong>
                        <br>
                        <p class="text-muted">
                            {{ dateformat($interview_data->interview_date, DISPLAY_DATE) }}
                        </p>
                    </div>
                    <div class="col-md-4 col-xs-12 b-r"> <strong>Interview Additonal Info</strong>
                        <br>
                        <p class="text-muted">
                            {{ $interview_data->interview_additonal_info }}
                        </p>
                    </div>   
                </div>
                @else
                    <div class="panel">
                        <div class="panel-heading" id="schedule_interview_tab" role="tab"> <a class="panel-title" data-toggle="collapse" href="#schedule_interview" data-parent="#interview" aria-expanded="true" aria-controls="schedule_interview">Schedule Pre-Screening Interview</a> </div>
                        <div class="panel-collapse collapse {{ (empty($interview_data))?'in':'' }}" id="schedule_interview" aria-labelledby="exampleHeadingDefaultOne" role="tabpanel">
                            <div class="panel-body">
                                <form id="schedule_interview_form" method="post" novalidate="true" class="form-horizontal"> 
                                    <div class="interview_preview">
                                        <input type="hidden" name="action" value="interview_preview" />
                                        <input type="hidden" name="active_step_key" value="{{ @$active_step_key}}" />
                                        {{ csrf_field() }} 
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group {{ $errors->has('timezone') ? 'has-error' : '' }}">
                                                    <label class="col-md-12">Timezone <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <select name="time_zone_admin" id="time_zone_admin" class="form-control" required="">
                                                            <option value="">-- Select Timezone --</option>
                                                                @foreach($timezones as $zone)
                                                                    <option {{ is_selected(@$interview_data->time_zone_admin,$zone->zone_id) }} {{ is_selected(session('admin_timezone'),$zone->zone_id) }}  value="{{ $zone->zone_id }}">{{ $zone->zone_label }}</option>
                                                                @endforeach
                                                        </select> 
                                                         <div class="help-block with-errors">
                                                            @if ($errors->has('timezone')){{ $errors->first('timezone') }}@endif
                                                        </div> 
                                                    </div>
                                                </div>
                                            </div>    
                                            <div class="col-sm-6">
                                                <div class="form-group {{ $errors->has('datetime') ? 'has-error' : '' }}">
                                                    <label class="col-md-12">Select Date and Time <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="date_interview_admin" placeholder="Date and Time" class="form-control datetimepicker" required autocomplete="off" value="{{ @$interview_data->date_interview_admin }}">
                                                        <div class="help-block with-errors"></div> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <button type="reset" class="btn btn-danger" onclick="cancelInterview('{{ $active_step_key }}')">Cancel</button>
                                                        <button type="submit" class="btn btn-info">Preview J1 Interview</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form> 
                            </div>
                        </div> 
                    </div>

                    @if($is_step_success==1)
                        <div class="panel">
                            <div class="panel-heading" id="start_interview_tab" role="tab"> <a class="panel-title collapsed" data-toggle="collapse" href="#start_interview" data-parent="#interview" aria-expanded="false" aria-controls="start_interview">Start Pre-Screening Interview</a> </div>
                            <div class="panel-collapse collapse {{ (!empty($interview_data))?'in':'' }}" id="start_interview" aria-labelledby="exampleHeadingDefaultTwo" role="tabpanel">
                                <div class="panel-body"> 
                                    <form id="start_prescreen_interview" method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="action" value="start_prescreen_interview" />
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group {{ $errors->has('timezone') ? 'has-error' : '' }}">
                                                    <label class="col-md-12">Program Type <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <select name="program_type" id="program_type" class="form-control" required="">
                                                            <option value="">-- Select Program Type --</option>
                                                                @foreach($programs as $program)
                                                                    <option value="{{ $program->id }}">{{ $program->program_name }}</option>                             
                                                                @endforeach
                                                        </select> 
                                                        <div class="clearfix"></div>
                                                        <div class="help-block with-errors">
                                                            @if ($errors->has('program_type')){{ $errors->first('program_type') }}@endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                                                    <label class="col-md-12">Field studied / Presently studying <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="field_studied" placeholder="Field studied / Presently studying" class="form-control" required>
                                                        <div class="clearfix"></div>
                                                        <div class="help-block with-errors">
                                                            @if ($errors->has('field_studied')){{ $errors->first('field_studied') }}@endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>    
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group {{ $errors->has('date_of_graduation') ? 'has-error' : '' }}">
                                                    <label class="col-md-12">Date of Graduation <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="date_of_graduation" placeholder="Date of Graduation" class="form-control datepicker" required autocomplete="off">
                                                        <div class="clearfix"></div>
                                                        <div class="help-block with-errors">
                                                            @if ($errors->has('date_of_graduation')){{ $errors->first('date_of_graduation') }}@endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group {{ $errors->has('gender') ? 'has-error' : '' }}">
                                                    <label class="col-md-12">Gender <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <input type="radio" name="gender" id="p_male" placeholder="Gender" required value="1"> <label for="p_male">Male</label>
                                                        <input type="radio" name="gender" id="p_female" placeholder="Gender" required value="2"> <label for="p_female">Female</label>
                                                        <div class="clearfix"></div>
                                                        <div class="help-block with-errors">
                                                            @if ($errors->has('gender')){{ $errors->first('gender') }}@endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group {{ $errors->has('date_of_availibility') ? 'has-error' : '' }}">
                                                    <label class="col-md-12">Date of availability <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="date_of_availibility" placeholder="Date of availability" class="form-control datepicker" required autocomplete="off">
                                                        <div class="clearfix"></div>
                                                        <div class="help-block with-errors">
                                                            @if ($errors->has('date_of_availibility')){{ $errors->first('date_of_availibility') }}@endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group {{ $errors->has('date_of_availibility') ? 'has-error' : '' }}">
                                                    <label class="col-md-12">&nbsp;</label>
                                                    <div class="col-md-12">
                                                        <input type="radio" name="availability_date_type" id="flexible_date" value="1" checked="checked"> 
                                                        <label for="flexible_date"> Flexible </label>
                                                        <input type="radio" name="availability_date_type" id="mandatory_date" value="2">
                                                        <label for="mandatory_date"> Mandatory </label>
                                                        <input type="radio" name="availability_date_type" id="availability_date_type_3" value="3">
                                                        <label for="availability_date_type"> No later than </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group {{ $errors->has('program_length') ? 'has-error' : '' }}">
                                                    <label class="col-md-12">Program Length <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <select name="program_length" id="program_length" class="form-control" required="">
                                                            <option value="">-- Select Program Length --</option>
                                                            @foreach($program_length as $pl)
                                                                <option value="{{ $pl }}">{{ $pl }}</option>
                                                            @endforeach
                                                        </select> 
                                                        <div class="clearfix"></div>
                                                        <div class="help-block with-errors">
                                                            @if ($errors->has('program_length')){{ $errors->first('program_length') }}@endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group {{ $errors->has('preferred_position_type_1') ? 'has-error' : '' }}">
                                                    <label class="col-md-12">Training Position 1 <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <select name="preferred_position_type_1" class="form-control" required="">
                                                            <option value="">Select Training Position One</option>
                                                            @foreach($position_types as $pt)
                                                                <option value="{{ $pt->id }}">{{ $pt->position_type_name }}</option>
                                                            @endforeach
                                                        </select> 
                                                        <div class="clearfix"></div>
                                                        <div class="help-block with-errors">
                                                            @if ($errors->has('preferred_position_type_1')){{ $errors->first('preferred_position_type_1') }}@endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group {{ $errors->has('preferred_position_type_2') ? 'has-error' : '' }}">
                                                    <label class="col-md-12">Training Position 2 <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <select name="preferred_position_type_2" class="form-control" required="">
                                                            <option value="">Select Training Position Two</option>
                                                            @foreach($position_types as $pt)
                                                                <option value="{{ $pt->id }}">{{ $pt->position_type_name }}</option>
                                                            @endforeach
                                                        </select> 
                                                        <div class="clearfix"></div>
                                                        <div class="help-block with-errors">
                                                            @if ($errors->has('preferred_position_type_2')){{ $errors->first('preferred_position_type_2') }}@endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group {{ $errors->has('english_level') ? 'has-error' : '' }}">
                                                    <label class="col-md-12">English Level<span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <select name="english_level" class="form-control" required="">
                                                            <option value="">Select English Level</option>
                                                            @foreach($english_level as $key=>$el)
                                                            <option value="{{ $key }}" title="{{ $el['desc'] }}">{{ $el['title'] }}</option>
                                                            @endforeach
                                                        </select> 
                                                        <div class="clearfix"></div>
                                                        <div class="help-block with-errors">
                                                            @if ($errors->has('english_level')){{ $errors->first('english_level') }}@endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group {{ $errors->has('has_passport') ? 'has-error' : '' }}">
                                                    <label class="col-md-12">Candidate Own a passport<span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <input type="radio" name="has_passport" id="has_passport_yes" value="1" required=""> <label for="has_passport_yes"> Yes </label>
                                                        <input type="radio" name="has_passport" id="has_passport_no" value="0" required=""> <label for="has_passport_no"> No </label>
                                                        <div class="clearfix"></div>
                                                        <div class="help-block with-errors">
                                                            @if ($errors->has('has_passport')){{ $errors->first('has_passport') }}@endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group {{ $errors->has('prev_j1_visa') ? 'has-error' : '' }}">
                                                    <label class="col-md-12">Previous J1 Visa Program<span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <input type="radio" name="prev_j1_visa" id="prev_j1_visa_yes" value="1" required=""> <label for="prev_j1_visa_yes"> Yes </label>
                                                        <input type="radio" name="prev_j1_visa" id="prev_j1_visa_no" value="0" required=""> <label for="prev_j1_visa_no"> No </label>
                                                        <div class="clearfix"></div>
                                                        <div class="help-block with-errors">
                                                            @if ($errors->has('prev_j1_visa')){{ $errors->first('prev_j1_visa') }}@endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group {{ $errors->has('registration_fee_status') ? 'has-error' : '' }}">
                                                    <label class="col-md-12">Registration Fee<span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <select name="registration_fee_status" class="form-control" required="">
                                                            <option value="1">($50) - Charge</option>
                                                            <option value="2">($50) - Postpone</option> 
                                                        </select> 
                                                        <div class="clearfix"></div>
                                                        <div class="help-block with-errors">
                                                            @if ($errors->has('registration_fee_status')){{ $errors->first('registration_fee_status') }}@endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group {{ $errors->has('interview_additonal_info') ? 'has-error' : '' }}">
                                                    <label class="col-md-12">Additional Info<span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <textarea name="interview_additonal_info" class="form-control" required=""></textarea> 
                                                        <div class="clearfix"></div>
                                                        <div class="help-block with-errors">
                                                            @if ($errors->has('interview_additonal_info')){{ $errors->first('interview_additonal_info') }}@endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <button type="reset" class="btn btn-danger" onclick="cancelInterview('{{ $active_step_key }}')">Cancel</button>
                                                        <button type="submit" class="btn btn-info">Submit</button>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> 
                    @endif

                    <script type="text/javascript">
                        $(document).ready(function(){
                            var user_id = $('meta[name="user_token"]').attr('content');
                            var form_selector = "#schedule_interview_form, #start_prescreen_interview";
                            
                            ajaxFormValidator(form_selector,function(ele,event){
                                event.preventDefault();
                                
                                var form_data = new FormData(ele);
                                    form_data.append('user_id',user_id);
                                
                                $.ajax({
                                    url: "{{ route('schedule.prescreen.interview') }}", 
                                    type: 'post',
                                    data: form_data,
                                    dataType: 'json',
                                    processData: false,
                                    contentType: false,
                                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                    success: function(response) {
                                        if(response.type=='success'){
                                            if(response.isreplace==1){
                                                $('.interview_preview').html(response.data);
                                            }
                                            else{
                                                var Html = '<div class="alert swl-alert-success"><ul><li>'+ response.message+ '</li></ul></div>'; 
                                                notifyResponseTimerAlert(Html,"success","Success");
                                                setTimeout(function(){
                                                    navigateStages(1,'{{$active_step_key}}');
                                                }, 3000); 
                                            }
                                        }
                                        else{
                                            var Html = '<div class="alert swl-alert-danger"><ul>'; 
                                            $.each( response.message, function( key, value ) {
                                                Html += '<li>' + value+ '</li>';  
                                            });
                                            Html += '</ul></div>';  
                                            notifyResponseTimerAlert(Html,"error","Error");
                                        } 
                                    }
                                });
                            }); 
                        });
                    </script> 
                @endif 
            </div>
        @endif
    </div>
</div> 