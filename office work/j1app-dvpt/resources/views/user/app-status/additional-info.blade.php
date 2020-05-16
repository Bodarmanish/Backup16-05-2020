@php
    $addinfo_data = @$user->portfolio->userGeneral;
    $countries = get_countries();
  
    $institution_type_list = config('common.institution_type');
    $study_level_list = config('common.study_level');
    $relationship = config('common.relation');
@endphp
<div class="row">
    <div class="col-md-12">
        <div class="addinfo_tab" id="addinfo_tab1">
            <div class="row">
                <div class="col-sm-12">
                    <h3>Additional Information (Contact Information 1/9)</h3>
                </div>
            </div>
            <div class="addinfo_notify"></div>
            <form class="m-b-10 custom_form" name="addinfo_form_1" id="addinfo_form_1">
                <input type="hidden" name="addinfo_form_step" value="1" />
                <input type="hidden" name="btn_action" value="" />
                <div class="row m-b-10">
                    <div class="col-sm-12">
                        <label class="control-label">Full Name</label>
                        <div class="row">
                            <div class="col-sm-4 m-b-10">
                                <div class="form-group">
                                    <input type="text" name="fname" value="{{ @$user->first_name }}" class="form-control" placeholder="First name" required=""> 
                                    <div class="help-block with-errors"></div>
                                    <i class="form-control-feedback inner-feedback"></i>
                                </div>
                            </div>
                            <div class="col-sm-4 m-b-10">
                                <div class="form-group">
                                    <input type="text" name="mname" value="{{ @$user->middle_name }}" class="form-control" placeholder="Middle name">
                                    <div class="help-block with-errors"></div>
                                    <i class="form-control-feedback inner-feedback"></i>
                                </div>
                            </div>
                            <div class="col-sm-4 m-b-10">
                                <div class="form-group">
                                    <input type="text" name="lname" value="{{ @$user->last_name }}" class="form-control" placeholder="Last/Family Name" required="">
                                    <div class="help-block with-errors"></div>
                                    <i class="form-control-feedback inner-feedback"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label" for="gender">Select your Gender</label>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="radio radio-info">
                                        <input type="radio" name="gender" id="Male" value="1" {{ is_checked(@$addinfo_data['gender'],'1') }} required="" />
                                        <label for="Male"> Male</label>
                                    </div> 
                                </div>
                                <div class="col-sm-4">  
                                    <div class="radio radio-info">
                                        <input type="radio" name="gender" id="Female" value="2" {{ is_checked(@$addinfo_data['gender'],'2') }} />
                                        <label for="Female"> Female</label>
                                    </div> 
                                </div>
                                <div class="col-sm-4">  
                                    <div class="radio radio-info">
                                        <input type="radio" name="gender" id="Other" value="3" {{ is_checked(@$addinfo_data['gender'],'3') }} />
                                        <label for="Other"> Other</label>
                                    </div> 
                                </div>
                            </div>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-sm-12"> 
                        <div class="form-group">
                            <label class="control-label">Phone Number <small class="text-danger">*</small></label>
                            <input type="text" name="phone_number" value="{{ @$addinfo_data['phone_number'] }}" class="form-control" placeholder="Phone number" data-nowhitespace="nowhitespace" required="">
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div> 
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Alternative Phone Number <small class="text-muted">(optional)</small></label>
                            <input type="text" name="alternate_phone_number" value="{{ @$addinfo_data['phone_number_two'] }}" class="form-control" placeholder="Alternative Phone Number">
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group"> 
                            <label class="control-label">Second Email Address <small class="text-muted">(optional)</small></label>
                            <input type="email" name="alternate_email" value="{{ @$addinfo_data['secondary_email'] }}" class="form-control" placeholder="Second Email Address "> 
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group"> 
                            <label class="control-label">What is the Best Time to Call You? <small class="text-muted"> Monday to Friday.</small> <small class="text-danger">*</small></label>
                            <div class="row"> 
                                <div class="col-sm-4"> 
                                    <div class="radio radio-info">
                                        <input type="radio" name="best_call_time" id="morning" value="1" {{ is_checked(@$addinfo_data['best_call_time'],'1') }} required="" />
                                        <label for="morning"> Morning<br/><small class="text-muted">(8am to 11am)</small></label>
                                    </div> 
                                </div>
                                <div class="col-sm-4">  
                                    <div class="radio radio-info">
                                        <input type="radio" name="best_call_time" id="afternoon" value="2" {{ is_checked(@$addinfo_data['best_call_time'],'2') }} />
                                        <label for="afternoon"> Afternoon<br/><small class="text-muted">(12pm to 5pm)</small></label>
                                    </div> 
                                </div>
                                <div class="col-sm-4">  
                                    <div class="radio radio-info">
                                        <input type="radio" name="best_call_time" id="evening" value="3" {{ is_checked(@$addinfo_data['best_call_time'],'3') }} />
                                        <label for="evening"> Evening<br/><small class="text-muted">(5pm to 8pm)</small></label>
                                    </div> 
                                </div>
                            </div>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    @if($user->is_timeline_locked == 1)
                        <button type="button" name="next_form" value="next_form" class="btn btn-sm btn-info next_form" onclick="return nextAddInfoStep('8','1');">Next</button>
                    @else
                        <button type="submit" name="save" value="save" class="btn btn-sm btn-info" onclick="return setFormBtnAction('addinfo_form_1',this.value);">Save</button>
                        <button type="submit" name="save_next" value="save_next" class="btn btn-sm btn-info" onclick="return setFormBtnAction('addinfo_form_1',this.value);">Save & Next</button>
                    @endif
                </div>
            </form>
        </div>
        <div class="addinfo_tab hide" id="addinfo_tab2">
            <div class="row">
                <div class="col-sm-12">
                    <h3>Additional Information (Address 2 of 9) </h3>
                </div>
            </div>
            <div class="addinfo_notify"></div>
            <form class="m-b-10 custom_form" name="addinfo_form_2" id="addinfo_form_2">
                <input type="hidden" name="addinfo_form_step" value="2" />
                <input type="hidden" name="btn_action" value="" />
                <div class="row m-b-10">
                    <div class="col-sm-12">
                        <div class="form-group"> 
                            <label class="text-muted control-label">In Care Of / Delivered to (Optional)</label>
                            <div class="row"> 
                                <div class="col-sm-12">
                                    <div class="checkbox checkbox-success">
                                        <input type="checkbox" name="deliver_my_name" id="deliver_my_name" value="1" {{ is_checked(@$addinfo_data['deliver_my_name'],'1') }} />
                                        <label for="deliver_my_name">Deliver to my name</label>
                                    </div> 
                                </div>
                            </div>
                            <input type="text" name="in_care_of" id="in_care_of" value="{{ @$addinfo_data['in_care_of'] }}" class="form-control" placeholder="in care of" @if(is_checked(@$addinfo_data['deliver_my_name'],'1',true)) readonly @endif/>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group"> 
                            <label class="text-muted control-label">Street Address <small class="text-danger">*</small></label>
                            <input type="text" name="address_street" data-notempty="notempty" value="{{ @$addinfo_data['street'] }}" class="form-control" placeholder="Your street address" required=""> 
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div> 
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group"> 
                            <label class="text-muted control-label">Street Address 2</label>
                            <input type="text" name="address_street_2" value="{{ @$addinfo_data['street_2'] }}" class="form-control" placeholder="Your street address 2"> 
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div> 
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">City <small class="text-danger">*</small></label>
                            <input type="text" name="address_city" value="{{ @$addinfo_data['city'] }}" required class="form-control" data-notempty="notempty" placeholder="City"/>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Zip / Postal Code <small class="text-danger">*</small></label>
                            <input type="text" name="address_zip" value="{{ @$addinfo_data['zip_code'] }}" required class="form-control" data-nowhitespace="nowhitespace" placeholder="Zip/Postal Code"/>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Country <small class="text-danger">*</small></label>
                            <select name="address_country" id="address_country" class="form-control" required>
                                <option value="">-- Select Country --</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->country_id }}" {{ is_selected($country->country_id,@$addinfo_data['country']) }} >{{ $country->country_name }}</option>
                                @endforeach
                            </select>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">State <small class="text-danger">*</small></label>
                            <input type="text" name="state" value="{{ @$addinfo_data['state'] }}" data-notempty="notempty" required class="form-control" placeholder="State"/>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    @if($user->is_timeline_locked == 1)
                        <button type="button" name="back" value="back" class="btn btn-sm btn-info next_form" onclick="return backAddInfoStep('8','2');">Back</button>
                        <button type="button" name="next_form" value="next_form" class="btn btn-sm btn-info next_form" onclick="return nextAddInfoStep('8','2');">Next</button>
                    @else
                        <button type="button" name="back" value="back" class="btn btn-sm btn-info next_form" onclick="return backAddInfoStep('8','2');">Back</button>
                        <button type="submit" name="save" value="save" class="btn btn-sm btn-info" onclick="return setFormBtnAction('addinfo_form_2',this.value);">Save</button>
                        <button type="submit" name="save_next" value="save_next" class="btn btn-sm btn-info" onclick="return setFormBtnAction('addinfo_form_2',this.value);">Save & Next</button>
                    @endif
                </div>
            </form>
        </div>
        <div class="addinfo_tab hide" id="addinfo_tab3">
            <div class="row">
                <div class="col-sm-12">
                    <h3>Additional Information (Passport Information 3 of 9)</h3>
                </div>
            </div>
            <div class="addinfo_notify"></div>
            <form class="m-b-10 custom_form" name="addinfo_form_3" id="addinfo_form_3">
                <input type="hidden" name="addinfo_form_step" value="3" />
                <input type="hidden" name="btn_action" value="" />

                <div class="row m-b-10">
                    <div class="col-sm-12">
                        <div class="form-group"> 
                            <label class="text-muted control-label">Passport Number <small class="text-danger">*</small></label> 
                            <input type="text" name="passport_number" value="{{ @$addinfo_data['passport_number'] }}" class="form-control" data-nowhitespace="nowhitespace" placeholder="Click here to add your passport number" required=""> 
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6"> 
                                <div class="form-group">
                                    <label class="control-label text-muted">Passport Issuing Date <small class="text-danger">*</small></label>
                                    <input type="text" name="passport_issued" value="{{ dateformat(@$addinfo_data['passport_issued']) }}" class="form-control datepicker" placeholder="MM/DD/YYYY" required="" autocomplete="off"> 
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div> 
                            </div> 
                            <div class="col-sm-6"> 
                                <div class="form-group"> 
                                    <label class="control-label text-muted">Passport Expiration Date <small class="text-danger">*</small></label>
                                    <input type="text" name="passport_expires" value="{{ dateformat(@$addinfo_data['passport_expires']) }}" class="form-control datepicker" placeholder="MM/DD/YYYY" required="" autocomplete="off"> 
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>  
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="col-sm-12"> 
                        <div class="row">
                            <div class="col-sm-6"> 
                                <div class="form-group">  
                                    <label class="control-label text-muted">Date of Birth <small class="text-danger">*</small></label>
                                    <input type="text" name="birth_date" value="{{ dateformat(@$addinfo_data['birth_date']) }}" class="form-control datepicker" placeholder="MM/DD/YYYY" required="" autocomplete="off">    
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div> 
                            </div> 
                            <div class="col-sm-6"> 
                                <div class="form-group"> 
                                    <label class="control-label text-muted">City of Birth <small class="text-danger">*</small></label>
                                    <input type="text" name="birth_city" value="{{ @$addinfo_data['birth_city'] }}" class="form-control" data-notempty="notempty" placeholder="City of Birth" required=""> 
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>  
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group"> 
                            <label class="text-muted control-label">Country of Birth <small class="text-danger">*</small></label> 
                            <select name="birth_country" id="birth_country" class="form-control" required>
                                <option value="">-- Select Country --</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->country_id }}" {{ is_selected($country->country_id,@$addinfo_data['birth_country']) }} >{{ $country->country_name }}</option>
                                @endforeach
                            </select>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group"> 
                            <label class="text-muted control-label">Citizen of <small class="text-danger">*</small></label> 
                            <select name="country_citizen" id="country_citizen" class="form-control" required>
                                <option value="">-- Select Country --</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->country_id }}" {{ is_selected($country->country_id,@$addinfo_data['country_citizen']) }} >{{ $country->country_name }}</option>
                                @endforeach
                            </select>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group"> 
                            <label class="text-muted control-label">Legal Permanent Resident <small class="text-danger">*</small></label> 
                            <select name="country_resident" id="country_resident" class="form-control" required>
                                <option value="">-- Select Country --</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->country_id }}" {{ is_selected($country->country_id,@$addinfo_data['country_resident']) }} >{{ $country->country_name }}</option>
                                @endforeach
                            </select>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group"> 
                            <label class="text-muted control-label">Passport Issuing Country <small class="text-danger">*</small></label> 
                            <select name="country_issuer" id="country_issuer" class="form-control" required>
                                <option value="">-- Select Country --</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->country_id }}" {{ is_selected($country->country_id,@$addinfo_data['country_issuer']) }} >{{ $country->country_name }}</option>
                                @endforeach
                            </select>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    @if($user->is_timeline_locked == 1)
                        <button type="button" name="back" value="back" class="btn btn-sm btn-info next_form" onclick="return backAddInfoStep('8','3');">Back</button>
                        <button type="button" name="next_form" value="next_form" class="btn btn-sm btn-info next_form" onclick="return nextAddInfoStep('8','3');">Next</button>
                    @else
                        <button type="button" name="back" value="back" class="btn btn-sm btn-info next_form" onclick="return backAddInfoStep('8','3');">Back</button>
                        <button type="submit" name="save" value="save" class="btn btn-sm btn-info" onclick="return setFormBtnAction('addinfo_form_3',this.value);">Save</button>
                        <button type="submit" name="save_next" value="save_next" class="btn btn-sm btn-info" onclick="return setFormBtnAction('addinfo_form_3',this.value);">Save & Next</button>
                    @endif
                </div>
            </form>
        </div>
        <div class="addinfo_tab hide" id="addinfo_tab4">
            <div class="row">
                <div class="col-sm-12">
                    <h3>Additional Information (Previous J1 Program 4 of 9) </h3>
                </div>
            </div>
            <div class="addinfo_notify"></div>
            <form class="m-b-10 custom_form" name="addinfo_form_4" id="addinfo_form_4">
                <input type="hidden" name="addinfo_form_step" value="4" />
                <input type="hidden" name="btn_action" value="" />
                <div class="row m-b-10">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Have you participated in a {{ __('application_term.exchange_visitor_program') }} in the past? <small class="text-danger">*</small></label>
                            <div class="row"> 
                                <div class="col-sm-6"> 
                                    <div class="radio radio-info">
                                        <input type="radio" name="previously_participated" id="previously_participated_Yes" value="1" {{ is_checked(@$addinfo_data['previously_participated'],'1') }} required="" onclick="return toggle_j1_program_field(true, '1');" />
                                        <label for="previously_participated_Yes"> Yes</label>
                                    </div> 
                                </div>
                                <div class="col-sm-6">  
                                    <div class="radio radio-info">
                                        <input type="radio" name="previously_participated" id="previously_participated_No" value="2" {{ is_checked(@$addinfo_data['previously_participated'],'2') }} onclick="return toggle_j1_program_field(false, '1');">
                                        <label for='previously_participated_No'> No</label>
                                    </div> 
                                </div>
                            </div>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-12 disable-content m-b-20" id="j1_program_field" @if(!is_checked(@$addinfo_data['previously_participated'],'1',true)) style="display: none;" @endif>
                        <div class="row m-b-10 j1_recent_program">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Recent Program Name <small class="text-danger">*</small></label>
                                    <input type="text" name="j1_recent_program_name" value="{{ @$addinfo_data['j1_first_name'] }}" data-notempty="notempty" @if(@$addinfo_data['previously_participated'] == 1) class="form-control" @else class="form-control disable-control" @endif placeholder="Click here to add your most recent J1 program name" required/>
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Start Date <small class="text-danger">*</small></label>
                                    <input type="text" name="j1_recent_program_start" value="{{ dateformat(@$addinfo_data['j1_first_started']) }}" @if(@$addinfo_data['previously_participated'] == 1) class="form-control datepicker" @else class="form-control datepicker disable-control" @endif  placeholder="mm/dd/yyyy" required autocomplete="off"/>
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">End Date <small class="text-danger">*</small></label>
                                    <input type="text" name="j1_recent_program_end" value="{{ dateformat(@$addinfo_data['j1_first_ended']) }}" @if(@$addinfo_data['previously_participated'] == 1) class="form-control datepicker" @else class="form-control datepicker disable-control" @endif placeholder="mm/dd/yyyy" required autocomplete="off"/>
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <hr class="full_dotted_line m-b-20" />
                        <a class="text-info m-b-20 add_new_j1_field cpointer" onclick="toggle_j1_program_field(true,'2');" @if(!empty(@$addinfo_data['j2_second_name'])) style="display:none;" @endif >
                            <i class="fa fa-plus text-info m-r-5"></i>add another
                        </a>
                        <div class="row m-b-10 j1_old_program" @if(empty(@$addinfo_data['j2_second_name'])) style="display: none;" @endif>
                            <div class="col-xs-10 p-l-0">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label">Oldest Program Name <small class="text-danger">*</small></label>
                                        <input type="text" name="j1_old_program_name" value="{{ @$addinfo_data['j2_second_name'] }}" data-notempty="notempty" @if(!empty(@$addinfo_data['j2_second_name'])) class="form-control" @else class="form-control disable-control" @endif  placeholder="Click here to add your oldest J1 program name" required/>
                                        <div class="help-block with-errors"></div>
                                        <div class="form-control-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Start Date <small class="text-danger">*</small></label>
                                        <input type="text" name="j1_old_program_start" value="{{ dateformat(@$addinfo_data['j2_second_started']) }}" @if(!empty(@$addinfo_data['j2_second_name'])) class="form-control datepicker" @else class="form-control datepicker disable-control" @endif placeholder="mm/dd/yyyy" required autocomplete="off"/>
                                        <div class="help-block with-errors"></div>
                                        <div class="form-control-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">End Date <small class="text-danger">*</small></label>
                                        <input type="text" name="j1_old_program_end" value="{{ dateformat(@$addinfo_data['j2_second_ended']) }}" @if(!empty(@$addinfo_data['j2_second_name'])) class="form-control datepicker" @else class="form-control datepicker disable-control" @endif placeholder="mm/dd/yyyy" required autocomplete="off"/>
                                        <div class="help-block with-errors"></div>
                                        <div class="form-control-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <hr class="vl m-r-20 min-height130" />
                                <a class="text-danger cpointer" onclick="toggle_j1_program_field(false,'2');" rel="tooltip" title="Delete"><i class="fa fa-trash-o"></i></a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="form-actions">
                    @if($user->is_timeline_locked == 1)
                        <button type="button" name="back" value="back" class="btn btn-sm btn-info next_form" onclick="return backAddInfoStep('8','4');">Back</button>
                        <button type="button" name="next_form" value="next_form" class="btn btn-sm btn-info next_form" onclick="return nextAddInfoStep('8','4');">Next</button>
                    @else
                        <button type="button" name="back" value="back" class="btn btn-sm btn-info next_form" onclick="return backAddInfoStep('8','4');">Back</button>
                        <button type="submit" name="save" value="save" class="btn btn-sm btn-info" onclick="return setFormBtnAction('addinfo_form_4',this.value);">Save</button>
                        <button type="submit" name="save_next" value="save_next" class="btn btn-sm btn-info" onclick="return setFormBtnAction('addinfo_form_4',this.value);">Save & Next</button>
                    @endif
                </div>
            </form>
        </div>
        <div class="addinfo_tab hide" id="addinfo_tab5">
            <div class="row">
                <div class="col-sm-12">
                    <h3>Additional Information (Spouse 5 of 9) </h3>
                </div>
            </div>
            <div class="addinfo_notify"></div>
            <form class="m-b-10 custom_form" name="addinfo_form_5" id="addinfo_form_5">
                <input type="hidden" name="addinfo_form_step" value="5" />
                <input type="hidden" name="btn_action" value="" />
                <div class="row m-b-10">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Marital Status <small class="text-danger">*</small></label>
                            <div class="row">
                                <div class="col-sm-3"> 
                                    <div class="radio radio-info">
                                        <input type="radio" name="marital_status" id="maritalSingle" value="1" {{ is_checked(@$addinfo_data['material_status'],'1') }} onclick="return toggle_fields(false, 'category_spouse');" required/>
                                        <label for="maritalSingle"> Single</label>
                                    </div> 
                                </div>
                                <div class="col-sm-3">  
                                    <div class="radio radio-info">
                                        <input type="radio" name="marital_status" id="maritalMarried" value="2" {{ is_checked(@$addinfo_data['material_status'],'2') }} onclick="return toggle_fields(true, 'category_spouse');"  />
                                        <label for='maritalMarried'> Married</label>
                                    </div> 
                                </div>
                                <div class="col-sm-3">  
                                    <div class="radio radio-info">
                                        <input type="radio" name="marital_status" id="maritalDivorced" value="3" {{ is_checked(@$addinfo_data['material_status'],'3') }} onclick="return toggle_fields(false, 'category_spouse');" />
                                        <label for='maritalDivorced'> Divorced</label>
                                    </div> 
                                </div>
                                <div class="col-sm-3">  
                                    <div class="radio radio-info">
                                        <input type="radio" name="marital_status" id="maritalWidowed" value="4" {{ is_checked(@$addinfo_data['material_status'],'4') }} onclick="return toggle_fields(false, 'category_spouse');" />
                                        <label for='maritalWidowed'> Widowed</label>
                                    </div> 
                                </div>
                            </div> 
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-12 disable-content" id="category_spouse" @if(!is_checked(@$addinfo_data['material_status'],'2',true)) style="display: none;" @endif>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Will your spouse need J-2 Visa to enter U.S.? <small class="text-danger">*</small></label>
                                    <div class="row">
                                        <div class="col-sm-6">  
                                            <div class="radio radio-info">
                                                <input type="radio" name="spouse_dep_needs_j2" id="spouse_dep_needs_j2_yes" value="1" {{ is_checked(@$addinfo_data['spouse_dep_needs_j2'],'1') }} @if(!is_checked(@$addinfo_data['material_status'],'2',true)) class="disable-control" @endif  required />
                                                <label for='spouse_dep_needs_j2_yes'> No</label>
                                            </div> 
                                        </div>
                                        <div class="col-sm-6">  
                                            <div class="radio radio-info">
                                                <input type="radio" name="spouse_dep_needs_j2" id="spouse_dep_needs_j2_no" value="2" {{ is_checked(@$addinfo_data['spouse_dep_needs_j2'],'2') }} @if(!is_checked(@$addinfo_data['material_status'],'2',true)) class="disable-control" @endif  />
                                                <label for='spouse_dep_needs_j2_no'> Yes</label>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label">Spouse Family Name <small class="text-danger">*</small></label>
                                            <input type="text" name="spouse_dep_last_name" id="spouse_dep_last_name" data-nowhitespace="nowhitespace" value="{{ @$addinfo_data['spouse_dep_last_name'] }}" @if(!is_checked(@$addinfo_data['material_status'],'2',true)) class="form-control disable-control"  @else class="form-control" @endif required/>
                                            <div class="help-block with-errors"></div>
                                            <div class="form-control-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label">Spouse Given Name <small class="text-danger">*</small></label>
                                            <input type="text" name="spouse_dep_first_name" id="spouse_dep_first_name" data-nowhitespace="nowhitespace" value="{{ @$addinfo_data['spouse_dep_first_name'] }}" @if(!is_checked(@$addinfo_data['material_status'],'2',true)) class="form-control disable-control"  @else class="form-control" @endif  required/>
                                            <div class="help-block with-errors"></div>
                                            <div class="form-control-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label">Spouse Middle Name <small class="text-danger">*</small></label>
                                            <input type="text" name="spouse_dep_middle_name" id="spouse_dep_middle_name" data-nowhitespace="nowhitespace" value="{{ @$addinfo_data['spouse_dep_middle_name'] }}" @if(!is_checked(@$addinfo_data['material_status'],'2',true)) class="form-control disable-control"  @else class="form-control" @endif  value="" required/>
                                            <div class="help-block with-errors"></div>
                                            <div class="form-control-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Spouse Gender <small class="text-danger">*</small></label>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="radio radio-info">
                                                <input type="radio" name="spouse_dep_gender" id="spouse_dep_gender_male" value="1" {{ is_checked(@$addinfo_data['spouse_dep_gender'],'1') }} @if(!is_checked(@$addinfo_data['material_status'],'2',true)) class="disable-control"  @endif required />
                                                <label for="spouse_dep_gender_male"> Male</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="radio radio-info">
                                                <input type="radio" name="spouse_dep_gender" id="spouse_dep_gender_female" value="2" {{ is_checked(@$addinfo_data['spouse_dep_gender'],'2') }} @if(!is_checked(@$addinfo_data['material_status'],'2',true)) class="disable-control"  @endif />
                                                <label for="spouse_dep_gender_female"> Female</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Spouse Birth date <small class="text-danger">*</small></label>
                                    <input type="text" name="spouse_dep_birth_date" id="spouse_dep_birth_date" value="{{ dateformat(@$addinfo_data['spouse_dep_birth_date']) }}" @if(!is_checked(@$addinfo_data['material_status'],'2',true)) class="form-control disable-control datepicker"  @else class="form-control datepicker" @endif required autocomplete="off"/>
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Spouse City of Birth <small class="text-danger">*</small></label>
                                    <input type="text" name="spouse_dep_birth_city" id="spouse_dep_birth_city" data-notempty="notempty" value="{{ @$addinfo_data['spouse_dep_birth_city'] }}" @if(!is_checked(@$addinfo_data['material_status'],'2',true)) class="form-control disable-control"  @else class="form-control" @endif  required/>
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Spouse Country of Birth <small class="text-danger">*</small></label>
                                    <select name="spouse_dep_birth_country" id="spouse_dep_birth_country" @if(!is_checked(@$addinfo_data['material_status'],'2',true)) class="form-control disable-control"  @else class="form-control" @endif required>
                                        <option value="">-- Select Country --</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->country_id }}" {{ is_selected($country->country_id,@$addinfo_data['spouse_dep_birth_country']) }} >{{ $country->country_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Do you have any other dependents? <small class="text-danger">*</small></label>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="radio radio-info">
                                                <input type="radio" name="other_dependants" id="other_dependants_yes" value="1" {{ is_checked(@$addinfo_data['other_dependants'],'1') }} @if(!is_checked(@$addinfo_data['material_status'],'2',true)) class="disable-control"  @endif required />
                                                <label for="other_dependants_yes"> Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="radio radio-info">
                                                <input type="radio" name="other_dependants" id="other_dependants_no" value="2" {{ is_checked(@$addinfo_data['other_dependants'],'2') }} @if(!is_checked(@$addinfo_data['material_status'],'2',true)) class="disable-control"  @endif />
                                                <label for="other_dependants_no"> No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Spouse enters U.S. at same time with you <small class="text-danger">*</small></label>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="radio radio-info">
                                                <input type="radio" name="spouse_dep_us_entry_together" id="spouse_dep_us_entry_together_yes" value="1" {{ is_checked(@$addinfo_data['spouse_dep_us_entry_together'],'1') }} @if(!is_checked(@$addinfo_data['material_status'],'2',true)) class="disable-control" @endif required onclick="return toggle_fields(false, 'spouse_us_entry_date');" />
                                                <label for="spouse_dep_us_entry_together_yes"> Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="radio radio-info">
                                                <input type="radio" name="spouse_dep_us_entry_together" id="spouse_dep_us_entry_together_no" value="2" {{ is_checked(@$addinfo_data['spouse_dep_us_entry_together'],'2') }} @if(!is_checked(@$addinfo_data['material_status'],'2',true)) class="disable-control" @endif onclick="return toggle_fields(true, 'spouse_us_entry_date');" />
                                                <label for="spouse_dep_us_entry_together_no"> No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12" id="spouse_us_entry_date" @if(@$addinfo_data['spouse_dep_us_entry_together'] != 2)style="display: none;"@endif>
                                <div class="form-group">
                                    <label class="control-label">If not same time, date spouse enters U.S <small class="text-danger">*</small></label>
                                    <input type="text" name="spouse_dep_entry_date" id="spouse_dep_entry_date" data-notempty="notempty" value="{{ dateformat(@$addinfo_data['spouse_dep_entry_date']) }}" @if(!is_checked(@$addinfo_data['material_status'],'2',true)) class="form-control disable-control datepicker"  @else class="form-control datepicker" @endif autocomplete="off" required="" @if(@$addinfo_data['spouse_dep_us_entry_together'] != 2 && !is_checked(@$addinfo_data['material_status'],'2',true)) disabled @endif />
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    @if($user->is_timeline_locked == 1)
                        <button type="button" name="back" value="back" class="btn btn-sm btn-info next_form" onclick="return backAddInfoStep('8','5');">Back</button>
                        <button type="button" name="next_form" value="next_form" class="btn btn-sm btn-info next_form" onclick="return nextAddInfoStep('8','5');">Next</button>
                    @else
                        <button type="button" name="back" value="back" class="btn btn-sm btn-info next_form" onclick="return backAddInfoStep('8','5');">Back</button>
                        <button type="submit" name="save" value="save" class="btn btn-sm btn-info" onclick="return setFormBtnAction('addinfo_form_5',this.value);">Save</button>
                        <button type="submit" name="save_next" value="save_next" class="btn btn-sm btn-info" onclick="return setFormBtnAction('addinfo_form_5',this.value);">Save & Next</button>
                    @endif
                </div>
            </form>
        </div>
        <div class="addinfo_tab hide" id="addinfo_tab6">
            <div class="row">
                <div class="col-sm-12">
                    <h3>Additional Information (Education 6 of 9) </h3>
                </div>
            </div>
            <div class="addinfo_notify"></div>
            <form class="m-b-10 custom_form" name="addinfo_form_6" id="addinfo_form_6">
                <input type="hidden" name="addinfo_form_step" value="6" />
                <input type="hidden" name="btn_action" value="" />
                <div class="row m-b-10">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Are you currently a full time student? <small class="text-danger">*</small></label>
                            <div class="row">
                                <div class="col-sm-6"> 
                                    <div class="radio radio-info">
                                        <input type="radio" name="currently_student" id="currently_student_yes" value="1" {{ is_checked(@$addinfo_data['currently_student'],'1') }} onclick="return toggle_fields(true, 'category_current_student');" required />
                                        <label for="currently_student_yes"> Yes</label>
                                    </div> 
                                </div>
                                <div class="col-sm-6">  
                                    <div class="radio radio-info">
                                        <input type="radio" name="currently_student" id="currently_student_no" value="2" {{ is_checked(@$addinfo_data['currently_student'],'2') }} onclick="return toggle_fields(false, 'category_current_student');" />
                                        <label for='currently_student_no'> No</label>
                                    </div> 
                                </div>
                            </div>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-12 disable-content" id="category_current_student" @if(!is_checked(@$addinfo_data['currently_student'],'1',true)) style="display: none;" @endif >
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Educational institution last or presently attending <small class="text-danger">*</small></label>
                                    <input type="text" name="institution" id="institution" value="{{ @$addinfo_data['institution'] }}" data-notempty="notempty" @if(!is_checked(@$addinfo_data['currently_student'],'1',true)) class="form-control disable-control" @else class="form-control" @endif required />
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Educational institution type <small class="text-danger">*</small></label>
                                    <select name="institution_type" id="institution_type" @if(!is_checked(@$addinfo_data['currently_student'],'1',true)) class="form-control disable-control" @else class="form-control" @endif required>
                                        <option value="">-- Select Option --</option>
                                        @if(!empty($institution_type_list))
                                            @foreach($institution_type_list as $key => $value)
                                            <option value="{{ $key }}" {{ is_selected($key,@$addinfo_data['institution_type']) }} >{{ $value }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Field studied / presently studying <small class="text-danger">*</small></label>
                                    <input type="text" name="field_studied" id="field_studied" data-notempty="notempty" value="{{ @$addinfo_data['field_studied'] }}" @if(!is_checked(@$addinfo_data['field_studied'],'1',true)) class="form-control" @else class="form-control" @endif required />
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Study level <small class="text-danger">*</small></label>
                                    <select name="study_level" class="form-control" required>
                                        <option value="">-- Select Option --</option>
                                        @if(!empty($study_level_list))
                                        @foreach($study_level_list as $key => $value)
                                        <option value="{{ $key }}" {{ is_selected($key,@$addinfo_data['study_level']) }} >{{ $value }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Date started studying <small class="text-danger">*</small></label>
                                    <input type="text" name="program_start" id="program_start" value="{{ dateformat(@$addinfo_data['program_start']) }}" @if(!is_checked(@$addinfo_data['currently_student'],'1',true)) class="form-control disable-control datepicker" @else class="form-control datepicker" @endif required  autocomplete="off" />
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Date Graduated (estimated or actual) <small class="text-danger">*</small></label>
                                    <input type="text" name="program_end" id="program_end" value="{{ dateformat(@$addinfo_data['program_end']) }}" @if(!is_checked(@$addinfo_data['currently_student'],'1',true)) class="form-control disable-control datepicker" @else class="form-control datepicker" @endif  required/>
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Advance Course worked Completed (if applicable)</label>
                                    <input type="text" name="advance_completed" id="advance_completed" value="{{ @$addinfo_data['advance_completed'] }}" @if(!is_checked(@$addinfo_data['currently_student'],'1',true)) class="form-control  disable-control" @else class="form-control" @endif />
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Year of experience in the field</label>
                                    <input type="text" name="experience_year" id="experience_year" value="{{ @$addinfo_data['experience_year'] }}" @if(!is_checked(@$addinfo_data['currently_student'],'1',true)) class="form-control  disable-control" @else class="form-control" @endif />
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    @if($user->is_timeline_locked == 1)
                        <button type="button" name="back" value="back" class="btn btn-sm btn-info next_form" onclick="return backAddInfoStep('8','6');">Back</button>
                        <button type="button" name="next_form" value="next_form" class="btn btn-sm btn-info next_form" onclick="return nextAddInfoStep('8','6');">Next</button>
                    @else
                        <button type="button" name="back" value="back" class="btn btn-sm btn-info next_form" onclick="return backAddInfoStep('8','6');">Back</button>
                        <button type="submit" name="save" value="save" class="btn btn-sm btn-info" onclick="return setFormBtnAction('addinfo_form_6',this.value);">Save</button>
                        <button type="submit" name="save_next" value="save_next" class="btn btn-sm btn-info" onclick="return setFormBtnAction('addinfo_form_6',this.value);">Save & Next</button>
                    @endif
                </div>
            </form>
        </div>
        <div class="addinfo_tab hide" id="addinfo_tab7">
            <div class="row">
                <div class="col-sm-12">
                    <h3>Additional Information (Work Experience 7 of 9) </h3>
                </div>
            </div>
            <div class="addinfo_notify"></div>
            <form class="m-b-10 custom_form" name="addinfo_form_7" id="addinfo_form_7">
                <input type="hidden" name="addinfo_form_step" value="7" />
                <input type="hidden" name="btn_action" value="" />
                <div class="row m-b-10">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Are you Currently Employed? <small class="text-danger">*</small></label>
                            <div class="row">
                                <div class="col-sm-6"> 
                                    <div class="radio radio-info">
                                        <input type="radio" name="currently_employed" id="currently_employed_yes" value="1" {{ is_checked(@$addinfo_data['currently_employed'],'1') }} onclick="return toggle_fields(true, 'category_employeed');" required/>
                                        <label for="currently_employed_yes"> Yes</label>
                                    </div> 
                                </div>
                                <div class="col-sm-6">  
                                    <div class="radio radio-info">
                                        <input type="radio" name="currently_employed" id="currently_employed_no" value="2" {{ is_checked(@$addinfo_data['currently_employed'],'2') }} onclick="return toggle_fields(false, 'category_employeed');"/>
                                        <label for='currently_employed_no'> No</label>
                                    </div> 
                                </div>
                            </div>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-12 disable-content" id="category_employeed" @if(!is_checked(@$addinfo_data['currently_employed'],'1',true)) style="display: none;" @endif>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Name of company <small class="text-danger">*</small></label>
                                    <input type="text" name="employer_name" id="employer_name" value="{{ @$addinfo_data['employer_name'] }}" data-notempty="notempty" @if(!is_checked(@$addinfo_data['currently_employed'],'1',true)) class="form-control disable-control" @else class="form-control" @endif required/>
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Company address <small class="text-danger">*</small></label>
                                    <input type="text" name="employer_address" id="employer_address" value="{{ @$addinfo_data['employer_address'] }}" data-notempty="notempty" @if(!is_checked(@$addinfo_data['currently_employed'],'1',true)) class="form-control disable-control" @else class="form-control" @endif required />
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Total Number of employees <small class="text-danger">*</small></label>
                                    <input type="text" name="total_employees" id="total_employees" value="{{ @$addinfo_data['total_employees'] }}" data-nowhitespace="nowhitespace" @if(!is_checked(@$addinfo_data['currently_employed'],'1',true)) class="form-control disable-control" @else class="form-control" @endif required  />
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Your {{ __('application_term.position') }} <small class="text-danger">*</small></label>
                                    <input type="text" name="position" id="position" value="{{ @$addinfo_data['position'] }}" data-notempty="notempty" @if(!is_checked(@$addinfo_data['currently_employed'],'1',true)) class="form-control disable-control" @else class="form-control" @endif required />
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Full Name of supervisor / owner <small class="text-danger">*</small></label>
                                    <input type="text" name="sup_name" id="sup_name" value="{{ @$addinfo_data['sup_name'] }}" data-notempty="notempty" @if(!is_checked(@$addinfo_data['currently_employed'],'1',true)) class="form-control disable-control" @else class="form-control" @endif required />
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Phone Number <small>(with Country and Area code)</small> <small class="text-danger">*</small></label>
                                    <input type="text" name="employer_phone" id="employer_phone" value="{{ @$addinfo_data['employer_phone'] }}" data-notempty="notempty" @if(!is_checked(@$addinfo_data['currently_employed'],'1',true)) class="form-control disable-control" @else class="form-control" @endif required />
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Fax Number <small>(with Country and Area code)</small> <small class="text-danger">*</small></label>
                                    <input type="text" name="employer_fax" id="employer_fax" value="{{ @$addinfo_data['employer_fax'] }}" data-nowhitespace="nowhitespace" @if(!is_checked(@$addinfo_data['currently_employed'],'1',true)) class="form-control disable-control" @else class="form-control" @endif required />
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Employment start date <small class="text-danger">*</small></label>
                                    <input type="text" name="emp_start_date" id="emp_start_date" value="{{ dateformat(@$addinfo_data['emp_start_date']) }}" @if(!is_checked(@$addinfo_data['currently_employed'],'1',true)) class="form-control datepicker disable-control" @else class="form-control datepicker" @endif required  autocomplete="off" />
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">Computer program skills <small>(coma separated)</small> <small class="text-danger">*</small></label>
                                    <input type="text" name="computer_programs" id="computer_programs" value="{{ @$addinfo_data['computer_programs'] }}" data-notempty="notempty" @if(!is_checked(@$addinfo_data['currently_employed'],'1',true)) class="form-control disable-control" @else class="form-control" @endif required />
                                    <div class="help-block with-errors"></div>
                                    <div class="form-control-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    @if($user->is_timeline_locked == 1)
                        <button type="button" name="back" value="back" class="btn btn-sm btn-info next_form" onclick="return backAddInfoStep('8','7');">Back</button>
                        <button type="button" name="next_form" value="next_form" class="btn btn-sm btn-info next_form" onclick="return nextAddInfoStep('8','7');">Next</button>
                    @else
                        <button type="button" name="back" value="back" class="btn btn-sm btn-info next_form" onclick="return backAddInfoStep('8','7');">Back</button>
                        <button type="submit" name="save" value="save" class="btn btn-sm btn-info" onclick="return setFormBtnAction('addinfo_form_7',this.value);">Save</button>
                        <button type="submit" name="save_next" value="save_next" class="btn btn-sm btn-info" onclick="return setFormBtnAction('addinfo_form_7',this.value);">Save & Next</button>
                    @endif
                </div>
            </form>
        </div>
        <div class="addinfo_tab hide" id="addinfo_tab8">
            <div class="row">
                <div class="col-sm-12">
                    <h3>Additional Information (Emergency Contacts 8 of 9) </h3>
                </div>
            </div>
            <div class="addinfo_notify"></div>
            <form class="m-b-10 custom_form" name="addinfo_form_8" id="addinfo_form_8">
                <input type="hidden" name="addinfo_form_step" value="8" />
                <input type="hidden" name="btn_action" value="" />
                <div class="row m-b-10">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Emergency Contact First Name <small class="text-danger">*</small></label>
                            <input type="text" name="contact_name_first" id="contact_name_first" value="{{ @$addinfo_data['contact_name_first'] }}" data-notempty="notempty" class="form-control"  required />
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Emergency Contact Last Name <small class="text-danger">*</small></label>
                            <input type="text" name="contact_name_last" id="contact_name_last" value="{{ @$addinfo_data['contact_name_last'] }}" data-notempty="notempty" class="form-control" required/>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Phone Number <small>(with Country and Area Code)</small> <small class="text-danger">*</small></label>
                            <input type="text" name="contact_phone" id="contact_phone" value="{{ @$addinfo_data['contact_phone'] }}" data-notempty="notempty" class="form-control"  required/>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Alternate Phone Number <small>(with Country and Area Code)</small></label>
                            <input type="text" name="contact_phone_alternative" id="contact_phone_alternative" value="{{ @$addinfo_data['contact_phone_alternative'] }}" class="form-control" />
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Relationship <small class="text-danger">*</small></label>
                            <select name="contact_relationship" id="contact_relationship" class="form-control" required>
                                <option value="">-- Select Option --</option>
                                @if(!empty($relationship))
                                @foreach($relationship as $key => $value)
                                    <option value="{{ $key }}" {{ is_selected($key,@$addinfo_data['contact_relationship']) }} >{{$value }}</option>
                                @endforeach
                                @endif
                            </select>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Contact Location Country <small class="text-danger">*</small></label>
                            <select name="contact_country" id="contact_country" class="form-control" required>
                                <option value="">-- Select Country --</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->country_id }}" {{ is_selected($country->country_id,@$addinfo_data['contact_country']) }} >{{ $country->country_name }}</option>
                                @endforeach
                            </select>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Contact is English Speaking <small class="text-danger">*</small></label>
                            <div class="row">
                                <div class="col-sm-6"> 
                                    <div class="radio radio-info">
                                        <input type="radio" name="contact_english_speaking" id="contact_english_speaking_yes" value="1" {{ is_checked(@$addinfo_data['contact_english_speaking'],'1') }} required />
                                        <label for="contact_english_speaking_yes"> Yes</label>
                                    </div> 
                                </div>
                                <div class="col-sm-6">  
                                    <div class="radio radio-info">
                                        <input type="radio" name="contact_english_speaking" id="contact_english_speaking_no" value="2" {{ is_checked(@$addinfo_data['contact_english_speaking'],'2') }}  />
                                        <label for='contact_english_speaking_no'> No</label>
                                    </div> 
                                </div>
                            </div>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Language spoken <small class="text-danger">*</small></label>
                            <input type="text" name="contact_language" id="contact_language" value="{{ @$addinfo_data['contact_language']}}" data-nowhitespace="nowhitespace" class="form-control" required />
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Email Address <small class="text-danger">*</small></label>
                            <input type="email" name="contact_email" id="contact_email" value="{{ @$addinfo_data['contact_email']}}"  class="form-control" required />     
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    @if($user->is_timeline_locked == 1)
                        <button type="button" name="back" value="back" class="btn btn-sm btn-info next_form" onclick="return backAddInfoStep('8','8');">Back</button>
                        <button type="button" name="next_form" value="next_form" class="btn btn-sm btn-info next_form" onclick="return nextAddInfoStep('8','8');">Next</button>
                    @else
                        <button type="button" name="back" value="back" class="btn btn-sm btn-info next_form" onclick="return backAddInfoStep('8','8');">Back</button>
                        <button type="submit" name="save" value="save" class="btn btn-sm btn-info" onclick="return setFormBtnAction('addinfo_form_8',this.value);">Save</button>
                        <button type="submit" name="save_next" value="save_next" class="btn btn-sm btn-info" onclick="return setFormBtnAction('addinfo_form_8',this.value);">Save & Next</button>
                    @endif
                </div>
            </form>
        </div>
        <div class="addinfo_tab hide" id="addinfo_tab9">
            <div class="row">
                <div class="col-sm-12">
                    <h3>Additional Information (Criminal Background 9 of 9) </h3>
                </div>
            </div>
            <div class="addinfo_notify"></div>
            <form class="m-b-10 custom_form" name="addinfo_form_9" id="addinfo_form_9">
                <input type="hidden" name="addinfo_form_step" value="9" />
                <input type="hidden" name="btn_action" value="" />

                <div class="row m-b-10">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Have you ever been convicted of a crime? <small class="text-danger">*</small></label>
                            <div class="row">
                                <div class="col-sm-6"> 
                                    <div class="radio radio-info">
                                        <input type="radio" name="criminal_record" id="criminal_record_yes" value="1" {{ is_checked(@$addinfo_data['criminal_record'],'1') }} required onclick="return toggle_fields(true, 'category_convict');"/>
                                        <label for="criminal_record_yes"> Yes</label>
                                    </div> 
                                </div>
                                <div class="col-sm-6">  
                                    <div class="radio radio-info">
                                        <input type="radio" name="criminal_record" id="criminal_record_no" value="2" {{ is_checked(@$addinfo_data['criminal_record'],'2') }} onclick="return toggle_fields(false, 'category_convict');"/>
                                        <label for='criminal_record_no'> No</label>
                                    </div> 
                                </div>
                            </div>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                    <div class="col-sm-12 disable-content" id="category_convict" @if(!is_checked(@$addinfo_data['criminal_record'],'1',true)) style="display: none;" @endif>
                        <div class="form-group">
                            <label class="control-label">If you selected yes Please explain <small class="text-danger">*</small></label>
                            <textarea name="criminal_explanation" id="criminal_explanation" @if(!is_checked(@$addinfo_data['currently_employed'],'1',true)) class="form-control disable-control" @else class="form-control" @endif data-notempty="notempty" required>{!! @$addinfo_data['criminal_explanation'] !!}</textarea>
                            <div class="help-block with-errors"></div>
                            <div class="form-control-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    @if($user->is_timeline_locked == 1)
                        <button type="button" name="back" value="back" class="btn btn-sm btn-info next_form" onclick="return backAddInfoStep('8','9');">Back</button>
                        <button type="button" name="next_form" value="next_form" class="btn btn-sm btn-info next_form" onclick="return navigateStages(1,'{{ $next_step_key }}');">Next Step</button>
                    @else
                        <button type="button" name="back" value="back" class="btn btn-sm btn-info next_form" onclick="return backAddInfoStep('8','9');">Back</button>
                        <button type="submit" name="save" value="save" class="btn btn-sm btn-info" onclick="return setFormBtnAction('addinfo_form_9',this.value);">Save</button>
                        <button type="submit" name="save_next" value="save_next" class="btn btn-sm btn-info" onclick="return setFormBtnAction('addinfo_form_9',this.value);">Save & Next</button>
                    @endif
                </div>
            </form>
        </div>
        <div>
            @if($addinfo_data->lock_additional_info == 1)
                <button type="button" class="btn btn-info" onclick="navigateStages('2')">Next Stage</button>
            @endif
        </div>
    </div>
</div>
<script>

    $(document).ready(function(){
        var notify_id = "addinfo_notify";
        var user_name = "{{ $user->first_name }} {{ $user->last_name }}";
        $("#deliver_my_name").click(function(){
            if($(this).is(":checked")) {
                $("#in_care_of").attr('readonly',true).val(user_name);
            }
            else {
                $("#in_care_of").removeAttr('readonly').val("");
            }
        });

        var addinfo_form_selector = "#addinfo_form_1,#addinfo_form_2,#addinfo_form_3,#addinfo_form_4,#addinfo_form_5,#addinfo_form_6,#addinfo_form_7,#addinfo_form_8,#addinfo_form_9";

        @if($user->is_timeline_locked == 1)
            $(addinfo_form_selector)
                .find('input, select, textarea, button[type=submit]')
                .attr("disabled",true);
        @else
            ajaxFormValidator(addinfo_form_selector,function(ele,event){
                event.preventDefault();
                show_inner_loader(".timeline_stp_desc","#all_tab_data");

                var btn_action = $(ele).find("input[name='btn_action']").val();
                if(btn_action == "" || btn_action == "undefined" || btn_action == null)
                {
                    return false;
                }

                var form_id = $(ele).attr('id');
                if(form_id == "addinfo_form_9" && btn_action == "save_next")
                {
                    $(ele).find(".form-actions").hide();
                }

                var form_data = new FormData(ele);

                $(ele).find("input[name='btn_action']").val("");

                $.ajax({
                    url: "{{ route('addinfo') }}",
                    type: 'post',
                    data: form_data,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response){
                        var messages = response.message;
                        if(response.type == "success"){
                            if(response.btn_action == "save_next"){
                                if(response.action_step != 9){
                                    //notifyResponse("."+notify_id,response.message,response.type);
                                    notifyResponseTimerAlert(response.message,response.type,response.type.charAt(0).toUpperCase()+ response.type.slice(1));
                                    nextAddInfoStep('8',response.action_step);
                                }
                                else{
                                    //notifyResponse("."+notify_id,"Additional Information saved successfully",response.type);
                                    notifyResponseTimerAlert(response.message,response.type,response.type.charAt(0).toUpperCase()+ response.type.slice(1));
                                    setTimeout(function(){
                                        notifyResponseTimerAlert("Hold On! we are redirecting to next step","warning");
                                        setTimeout(function(){
                                            navigateStages(2);
                                        },3000);
                                    },3000);

                                    $('#addinfo_list_'+response.action_step).addClass('done').prepend('<i class="fa fa-check text-success m-r-5"></i>');
                                }
                            }
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
                            notifyResponseTimerAlert(messages,response.type);
                        }
                        hide_inner_loader(".timeline_stp_desc","#all_tab_data");
                    },
                });
            });
        @endif
        
        $( ".datepicker" ).keydown(function( event ) {
            return false;
        });
    });
</script>