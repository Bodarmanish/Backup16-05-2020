@php
    $countries = get_countries();
    
    $resume_data = @$resume_data;
    $education = @$resume_data->education;
    $employment = @$resume_data->employment;
    $certificates = @$resume_data->certificates;
    $awards = @$resume_data->awards;
    
    $upload_file_size   = config('common.upload_file_size');
    $allow_file_ext     = collect(config('common.allow_doc_ext'))->implode(', ');
    $upload_img_size    = config('common.upload_img_size');
    $allow_image_ext    = collect(config('common.allow_image_ext'))->implode(', ');
    
    $passport_photo = @$resume_data['passport_photo'];
    $passport_photo_path = config('common.user-documents').DS.$user->id.DS.$passport_photo;
    
    $passport_photo_url = empty(get_url($passport_photo_path)) 
                    ? url("assets/images/noavatar.png") 
                    : "storage".DS.$passport_photo_path;
@endphp
@if($action == "form_rb_1")
    <form id="{{ $action }}" name="{{ $action }}" enctype="multipart/form-data">
        <input type="hidden" name="rb_step" value="1" />
        <input type="hidden" name="btn_action" value="" />
        <div class="col-sm-12">
            <h3>Candidate Information</h3>
            <div class="notify"></div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label class="control-label">First Name <small class="text-danger">*</small></label>
                <input type="text" name="first_name" class="form-control" value="{{ @$user->first_name }}" data-notempty="notempty" required placeholder="First Name" />
                <div class="help-block with-errors"></div>
                <div class="form-control-feedback"></div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label class="control-label">Last Name <small class="text-danger">*</small></label>
                <input type="text" name="last_name" class="form-control" value="{{ @$user->last_name }}" data-notempty="notempty" required placeholder="Last Name" />
                <div class="help-block with-errors"></div>
                <div class="form-control-feedback"></div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <label class="control-label">Mailing Address <small class="text-danger">*</small></label>
                <textarea name="address" class="form-control" required placeholder="Mailing Address" data-notempty="notempty">{{ @$resume_data->address }}</textarea>
                <div class="help-block with-errors"></div>
                <div class="form-control-feedback"></div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label class="control-label">Country <small class="text-danger">*</small></label>
                <select name="country" class="form-control" required>
                    <option value="">-- Select Country --</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->country_id }}" {{ is_selected($country->country_id,@$resume_data->country_id) }} >{{ $country->country_name }}</option>
                    @endforeach
                </select>
                <div class="help-block with-errors"></div>
                <div class="form-control-feedback"></div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label class="control-label">Phone Number <small class="text-danger">*</small></label>
                <input type="text" name="phone_no" class="form-control" value="{{ @$resume_data->primary_phone }}" data-nowhitespace="nowhitespace" required placeholder="Phone no" />
                <div class="help-block with-errors"></div>
                <div class="form-control-feedback"></div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label class="control-label">Secondary Con. Number </label>
                <input type="text" name="mobile_no" class="form-control" value="{{ @$resume_data->secondary_phone }}" placeholder="Phone no" />
                <div class="help-block with-errors"></div>
                <div class="form-control-feedback"></div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label class="control-label">Skype<small class="text-danger">*</small></label>
                <input type="text" name="skype" class="form-control" value="{{ @$resume_data->skype }}" data-nowhitespace="nowhitespace" required  placeholder="Skype"/>
                <div class="help-block with-errors"></div>
                <div class="form-control-feedback"></div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label class="control-label">Email Address<small class="text-danger">*</small></label>
                @if(!empty($user->email))
                    <input type="email" name="email" class="form-control" value="{{ @$user->email }}" readonly="" required />
                @else
                    <input type="email" name="email" class="form-control" value="{{ @$resume_data->email }}" required />
                @endif
                <div class="help-block with-errors"></div>
                <div class="form-control-feedback"></div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label class="control-label">Objective  <small class="text-danger">*</small></label>
                <textarea name="objective" class="form-control" required  placeholder="Objective (Information)" data-notempty="notempty">{{ @$resume_data->objective }}</textarea>
                <div class="help-block with-errors"></div>
                <div class="form-control-feedback"></div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label class="control-label">Summary </label>
                <textarea name="summary" class="form-control" placeholder="Summary" >{{ @$resume_data->summary }}</textarea>
                <div class="help-block with-errors"></div>
                <div class="form-control-feedback"></div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <label class="control-label">Passport style picture <small class="text-danger">*</small> </label>
                @if(!empty(@$resume_data->passport_photo))
                <div id="rb_existing_psp">
                    <label>{{ @$resume_data->passport_photo }} <!--<button type="button" class="text-danger" onclick="reNewPSP();"><i class="fa fa-times"></i></button>--></label>
                    <input type="hidden" name="file_check" value="1" />
                </div>
                @endif
                <div id="rb_upload_psp" @if(!empty(@$resume_data->passport_photo)) style="display:none; " @endif>
                    <input type="file" name="passport_photo" value="" required="" @if(!empty(@$resume_data->passport_photo)) disabled @endif/>
                    <small class="text-orange">(Supported image formats: {{$allow_image_ext}} and maximum file Size {{$upload_img_size}} MB.)</small>
                </div>
                <div class="help-block with-errors"></div>
                <div class="form-control-feedback"></div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12">
            <div class="form-actions">
                <button type="submit" name="save_next" value="save_next" class="btn btn-info" onclick="return setFormBtnAction('{{ $action }}',this.value)">Save & Next</button>
                <button type="button" name="cancel" class="btn btn-danger" onclick="return resumeBuilder('hide');">Cancel</button>
            </div>
        </div>
    </form>
@elseif($action == "form_rb_2")
    <form id="{{ $action }}" name="{{ $action }}">
        <input type="hidden" name="rb_step" value="2" />
        <input type="hidden" name="btn_action" value="" />
        <div class="col-sm-12">
            <h3>Skills & Abilities</h3>
            <div class="notify"></div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label class="control-label">Computer skills</label>
                <input type="text" name="skill_computer_skills" class="form-control" value="{{ @$resume_data->skill_computer_skills }}" placeholder="Computer skills" />
                <div class="help-block with-errors"></div>
                <div class="form-control-feedback"></div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label class="control-label">Computer Programs</label>
                <input type="text" name="skill_computer_programs" class="form-control" value="{{ @$resume_data->skill_computer_programs }}" placeholder="Computer Programs" />
                <div class="help-block with-errors"></div>
                <div class="form-control-feedback"></div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label class="control-label">Industry specific programs</label>
                <input type="text" name="skill_industry_programs" class="form-control" value="{{ @$resume_data->skill_industry_programs }}" placeholder="Industry specific programs" />
                <div class="help-block with-errors"></div>
                <div class="form-control-feedback"></div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label class="control-label">Languages Spoken <small class="text-danger">*</small></label>
                <input type="text" name="skill_language_spoken" class="form-control" value="{{ @$resume_data->skill_language_spoken }}" placeholder="Languages Spoken" required />
                <div class="help-block with-errors"></div>
                <div class="form-control-feedback"></div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label class="control-label">Other skills</label>
                <input type="text" name="skill_other_skills" class="form-control" value="{{ @$resume_data->skill_other_skills }}" placeholder="Other skills" />
                <div class="help-block with-errors"></div>
                <div class="form-control-feedback"></div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12">
            <div class="form-actions">
                <button type="button" name="back" value="2" class="btn btn-info" onclick="return gotoRBStep(this.value,'prev')">Back</button>
                <button type="submit" name="save_next" value="save_next" class="btn btn-info" onclick="return setFormBtnAction('{{ $action }}',this.value)">Save & Next</button>
                <button type="button" name="cancel" class="btn btn-danger" onclick="return resumeBuilder('hide');">Cancel</button>
            </div>
        </div>
    </form>
@elseif($action == "form_rb_3")
    <form id="{{ $action }}" name="{{ $action }}">
        <input type="hidden" name="rb_step" value="3" />
        <input type="hidden" name="btn_action" value="" />
        <input type="hidden" name="remove_existing_id" value="" />
        <div class="col-sm-12">
            <h3>Education</h3>
            <div class="notify"></div>
        </div>

        <div id="rb_edu_section_1" class="rb_section">
            <div class="col-sm-12">
                <div class="section_title">
                    <input type="checkbox" id="check_rb_edu_1" name="education_1" class="rb_section_checkbox hide" value="1" checked  onclick="return false;">
                    <span>Education #1</span>
                </div>
            </div>
            <div class="rb_section_content">
                <input type="hidden" name="education[0][education_id]" value="{{ @$education[0]->id }}"/>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">School Name/Location <small class="text-danger">*</small></label>
                        <input type="text" name="education[0][school]" class="form-control" value="{{ @$education[0]->school }}" data-notempty="notempty" required placeholder="School Name/Location" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Degree Name <small class="text-danger">*</small></label>
                        <input type="text" name="education[0][degree]" class="form-control" value="{{ @$education[0]->degree }}" required placeholder="Degree Name" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Start Date <small class="text-danger">*</small></label>
                        <input type="text" name="education[0][start_date]" class="form-control datepicker" value="{{ dateformat(@$education[0]->start_date) }}" required placeholder="Start Date" autocomplete="off" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">End Date <small class="text-danger">*</small></label>
                        <input type="text" name="education[0][end_date]" class="form-control datepicker" value="{{ dateformat(@$education[0]->end_date) }}" required placeholder="End Date" autocomplete="off" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label">Concentration or Minor </label>
                        <input type="text" name="education[0][minor]" class="form-control" value="{{ @$education[0]->minor }}" placeholder="Concentration or Minor, if applicable" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label">Brief description of degree or courses taken </label>
                        <textarea name="education[0][description]" class="form-control" placeholder="Brief description of degree or courses taken" >{{ @$education[0]->description }}</textarea>
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="rb_edu_section_2" class="rb_section">
            <div class="col-sm-12">
                <div class="section_title">
                    <input type="checkbox" id="check_rb_edu_2" name="education_2"  value="1" class="rb_section_checkbox" onclick="return toggleRBSection(this,'rb_edu_section_2','{{ $action }}')" {{ is_checked(@$education[1]->id, @$education[1]->id) }}/>
                    <span>Education #2</span>
                </div>
            </div>
            <div class="rb_section_content">
                <input type="hidden" name="education[1][education_id]" class="rb_existing_id" value="{{ @$education[1]->id }}"/>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">School Name/Location <small class="text-danger">*</small></label>
                        <input type="text" name="education[1][school]" class="form-control" value="{{ @$education[1]->school }}" required placeholder="School Name/Location" data-notempty="notempty" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Degree Name <small class="text-danger">*</small></label>
                        <input type="text" name="education[1][degree]" class="form-control" value="{{ @$education[1]->degree }}" required  placeholder="Degree Name"/>
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Start Date <small class="text-danger">*</small></label>
                        <input type="text" name="education[1][start_date]" class="form-control datepicker" value="{{ dateformat(@$education[1]->start_date) }}" required placeholder="Start Date" autocomplete="off" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">End Date <small class="text-danger">*</small></label>
                        <input type="text" name="education[1][end_date]" class="form-control datepicker" value="{{ dateformat(@$education[1]->end_date) }}" required placeholder="End Date" autocomplete="off" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label">Concentration or Minor </label>
                        <input type="text" name="education[1][minor]" class="form-control" value="{{ @$education[1]->minor }}" placeholder="Concentration or Minor, if applicable" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label">Brief description of degree or courses taken </label>
                        <textarea name="education[1][description]" class="form-control" placeholder="Brief description of degree or courses taken" >{{ @$education[1]->description }}</textarea>
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="rb_edu_section_3" class="rb_section">
            <div class="col-sm-12">
                <div class="section_title">
                    <input type="checkbox" id="check_rb_edu_3" name="education_3" value="1" class="rb_section_checkbox" onclick="return toggleRBSection(this,'rb_edu_section_3','{{ $action }}')" {{ is_checked(@$education[2]->id, @$education[2]->id) }}/>
                    <span>Education #3</span>
                </div>
            </div>
            <div class="rb_section_content">
                <input type="hidden" name="education[2][education_id]" class="rb_existing_id" value="{{ @$education[2]->id }}"/>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">School Name/Location <small class="text-danger">*</small></label>
                        <input type="text" name="education[2][school]" class="form-control" value="{{ @$education[2]->school }}" required placeholder="School Name/Location" data-notempty="notempty" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Degree Name <small class="text-danger">*</small></label>
                        <input type="text" name="education[2][degree]" class="form-control" value="{{ @$education[2]->degree }}" required  placeholder="Degree Name"/>
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Start Date <small class="text-danger">*</small></label>
                        <input type="text" name="education[2][start_date]" class="form-control datepicker" value="{{ dateformat(@$education[2]->start_date) }}" required placeholder="Start Date" autocomplete="off" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">End Date <small class="text-danger">*</small></label>
                        <input type="text" name="education[2][end_date]" class="form-control datepicker" value="{{ dateformat(@$education[2]->end_date) }}" required placeholder="End Date" autocomplete="off" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label">Concentration or Minor </label>
                        <input type="text" name="education[2][minor]" class="form-control" value="{{ @$education[2]->minor }}" placeholder="Concentration or Minor, if applicable" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label">Brief description of degree or courses taken </label>
                        <textarea name="education[2][description]" class="form-control" placeholder="Brief description of degree or courses taken" >{{ @$education[2]->description }}</textarea>
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12">
            <div class="form-actions">
                <button type="button" name="back" value="3" class="btn btn-info" onclick="return gotoRBStep(this.value,'prev')">Back</button>
                <button type="submit" name="save_next" value="save_next" class="btn btn-info" onclick="return setFormBtnAction('{{ $action }}',this.value)">Save & Next</button>
                <button type="button" name="cancel" class="btn btn-danger" onclick="return resumeBuilder('hide');">Cancel</button>
            </div>
        </div>
    </form>
@elseif($action == "form_rb_4")
    <form id="{{ $action }}" name="{{ $action }}">
        <input type="hidden" name="rb_step" value="4" />
        <input type="hidden" name="btn_action" value="" />
        <input type="hidden" name="remove_existing_id" value="" />
        <div class="col-sm-12">
            <h3>Employment History</h3>
            <div class="notify"></div>
        </div>
        <div id="rb_emp_section_1" class="rb_section">
            <div class="col-sm-12">
                <div class="section_title">
                    <input type="checkbox" id="check_rb_emp_1" name="hostcompany_1" value="1" class="rb_section_checkbox hide" checked>
                    <span>{{ __('application_term.employer') }} #1</span>
                </div>
            </div>
            <div class="rb_section_content">
                <input type="hidden" name="employment[0][employment_id]" value="{{ @$employment[0]->id }}"/>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Job Title</label>
                        <input type="text" name="employment[0][title]" class="form-control" value="{{ @$employment[0]->title }}" placeholder="Job Title" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">{{ __('application_term.employer') }} Name </label>
                        <input type="text" name="employment[0][employer_name]" class="form-control" value="{{ @$employment[0]->employer_name }}" placeholder="{{ __('application_term.employer') }} Name" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Start Date </label>
                        <input type="text" name="employment[0][start_date]" class="form-control datepicker" value="{{ dateformat(@$employment[0]->start_date) }}" placeholder="Start Date" autocomplete="off" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">End Date </label>
                        <input type="text" name="employment[0][end_date]" class="form-control datepicker" value="{{ dateformat(@$employment[0]->end_date) }}" placeholder="End Date" autocomplete="off" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Location </label>
                        <input type="text" name="employment[0][location]"  class="form-control" value="{{ @$employment[0]->location }}" placeholder="Location" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Duties </label>
                        <textarea name="employment[0][duties]" class="form-control" placeholder="Duties">{{ @$employment[0]->duties }}</textarea>
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="rb_emp_section_2" class="rb_section">
            <div class="col-sm-12">
                <div class="section_title">
                    <input type="checkbox" id="check_rb_emp_2" name="hostcompany_2" value="1" class="rb_section_checkbox" onclick="return toggleRBSection(this,'rb_emp_section_2','{{ $action }}')" {{ is_checked(@$employment[1]->id, @$employment[1]->id) }}/>
                    <span>{{ __('application_term.employer') }} #2</span>
                </div>
            </div>
            <div class="rb_section_content">
                <input type="hidden" name="employment[1][employment_id]" class="rb_existing_id" value="{{ @$employment[1]->id }}"/>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Job Title</label>
                        <input type="text" name="employment[1][title]" class="form-control" value="{{ @$employment[1]->title }}"  placeholder="Job Title" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">{{ __('application_term.employer') }} Name </label>
                        <input type="text" name="employment[1][employer_name]" class="form-control" value="{{ @$employment[1]->employer_name }}" placeholder="{{ __('application_term.employer') }} Name" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Start Date </label>
                        <input type="text" name="employment[1][start_date]" class="form-control datepicker" value="{{ dateformat(@$employment[1]->start_date) }}"  placeholder="Start Date" autocomplete="off" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">End Date </label>
                        <input type="text" name="employment[1][end_date]" class="form-control datepicker" value="{{ dateformat(@$employment[1]->end_date) }}"  placeholder="End Date" autocomplete="off" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Location </label>
                        <input type="text" name="employment[1][location]"  class="form-control" value="{{ @$employment[1]->location }}"  placeholder="Location" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Duties </label>
                        <textarea name="employment[1][duties]" class="form-control" placeholder="Duties" >{{ @$employment[1]->duties }}</textarea>
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="rb_emp_section_3" class="rb_section">
            <div class="col-sm-12">
                <div class="section_title">
                    <input type="checkbox" id="check_rb_emp_3" name="hostcompany_3" value="1" class="rb_section_checkbox" onclick="return toggleRBSection(this,'rb_emp_section_3','{{ $action }}')" {{ is_checked(@$employment[2]->id, @$employment[2]->id) }}/>
                    <span>{{ __('application_term.employer') }} #3</span>
                </div>
            </div>
            <div class="rb_section_content">
                <input type="hidden" name="employment[2][employment_id]" class="rb_existing_id" value="{{ @$employment[2]->id }}"/>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Job Title </label>
                        <input type="text" name="employment[2][title]" class="form-control" value="{{ @$employment[2]->title }}"  placeholder="Job Title" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">{{ __('application_term.employer') }} Name </label>
                        <input type="text" name="employment[2][employer_name]" class="form-control" value="{{ @$employment[2]->employer_name }}"  placeholder="{{ __('application_term.employer') }} Name" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Start Date </label>
                        <input type="text" name="employment[2][start_date]" class="form-control datepicker" value="{{ dateformat(@$employment[2]->start_date) }}"  placeholder="Start Date" autocomplete="off" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">End Date </label>
                        <input type="text" name="employment[2][end_date]" class="form-control datepicker" value="{{ dateformat(@$employment[2]->end_date) }}"  placeholder="End Date" autocomplete="off" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Location </label>
                        <input type="text" name="employment[2][location]"  class="form-control" value="{{ @$employment[2]->location }}"  placeholder="Location" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Duties</label>
                        <textarea name="employment[2][duties]" class="form-control" placeholder="Duties">{{ @$employment[2]->duties }}</textarea>
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12">
            <div class="form-actions">
                <button type="button" name="back" value="4" class="btn btn-info" onclick="return gotoRBStep(this.value,'prev')">Back</button>
                <button type="submit" name="save_next" value="save_next" class="btn btn-info" onclick="return setFormBtnAction('{{ $action }}',this.value)">Save & Next</button>
                <button type="button" name="cancel" class="btn btn-danger" onclick="return resumeBuilder('hide');">Cancel</button>
            </div>
        </div>
    </form>
@elseif($action == "form_rb_5")
    <form id="{{ $action }}" name="{{ $action }}">
        <input type="hidden" name="rb_step" value="5" />
        <input type="hidden" name="btn_action" value="" />
        <input type="hidden" name="remove_existing_id" value="" />
        <div class="col-sm-12">
            <h3>Credentials & Certifications</h3>
            <div class="notify"></div>
        </div>
        <div id="rb_certi_section_1" class="rb_section">
            <div class="col-sm-12">
                <div class="section_title">
                    <input type="checkbox" id="check_rb_certi_1" name="certificate_1" value="1" class="rb_section_checkbox hide"  checked>
                    <span>Certificate #1</span>
                </div>
            </div>
            <div class="rb_section_content">
                <input type="hidden" name="certificate[0][certificate_id]" value="{{ @$certificates[0]->id }}"/>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Certificate Title </label>
                        <input type="text" name="certificate[0][title]" class="form-control" value="{{ @$certificates[0]->title }}" placeholder="Certificate Title" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Location </label>
                        <input type="text" name="certificate[0][location]" class="form-control" value="{{ @$certificates[0]->location }}" placeholder="Location" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Date </label>
                        <input type="text" name="certificate[0][date_of_certificate]" class="datepicker form-control" value="{{ dateformat(@$certificates[0]->date_of_certificate) }}" placeholder="Date" autocomplete="off" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Brief Description </label>
                        <textarea name="certificate[0][description]" class="form-control" placeholder="Brief Description" >{{ @$certificates[0]->description }}</textarea>
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="rb_certi_section_2" class="rb_section">
            <div class="col-sm-12">
                <div class="section_title">
                    <input type="checkbox" id="check_rb_certi_2" name="certificate_2" value="1" class="rb_section_checkbox" onclick="return toggleRBSection(this,'rb_certi_section_2','{{ $action }}')" @if(!empty(@$certificates[1]->id)) checked @endif/>
                    <span>Certificate #2</span>
                </div>
            </div>
            <div class="rb_section_content">
                <input type="hidden" name="certificate[1][certificate_id]" class="rb_existing_id" value="{{ @$certificates[1]->id }}"/>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Certificate Title </label>
                        <input type="text" name="certificate[1][title]" class="form-control" value="{{ @$certificates[1]->title }}" placeholder="Certificate Title" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Location </label>
                        <input type="text" name="certificate[1][location]" class="form-control" value="{{ @$certificates[1]->location }}" placeholder="Location" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Date </label>
                        <input type="text" name="certificate[1][date_of_certificate]" class="datepicker form-control" value="{{ dateformat(@$certificates[1]->date_of_certificate) }}" placeholder="Date" autocomplete="off" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Brief Description </label>
                        <textarea name="certificate[1][description]" class="form-control" placeholder="Brief Description" >{{ @$certificates[1]->description }}</textarea>
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12">
            <div class="form-actions">
                <button type="button" name="back" value="5" class="btn btn-info" onclick="return gotoRBStep(this.value,'prev')">Back</button>
                <button type="submit" name="save_next" value="save_next" class="btn btn-info" onclick="return setFormBtnAction('{{ $action }}',this.value)">Save & Next</button>
                <button type="button" name="cancel" class="btn btn-danger" onclick="return resumeBuilder('hide');">Cancel</button>
            </div>
        </div>
    </form>
@elseif($action == "form_rb_6")
    <form id="{{ $action }}" name="{{ $action }}">
        <input type="hidden" name="rb_step" value="6" />
        <input type="hidden" name="btn_action" value="" />
        <input type="hidden" name="remove_existing_id" value="" />
        <div class="col-sm-12">
            <h3>Awards & Recognitions</h3>
            <div class="notify"></div>
        </div>
        <div id="rb_award_section_1" class="rb_section">
            <div class="col-sm-12">
                <div class="section_title">
                    <input type="checkbox" id="check_rb_award_1" name="award_1" value="1" class="rb_section_checkbox hide" checked>
                    <span>Award #1</span>
                </div>
            </div>
            <div class="rb_section_content">
                <input type="hidden" name="award[0][award_id]" value="{{ @$awards[0]->id }}"/>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Award Name</label>
                        <input type="text" name="award[0][title]" class="form-control" value="{{ @$awards[0]->title }}" placeholder="Award Name" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Date</label>
                        <input type="text" name="award[0][award_date]" class="datepicker form-control" value="{{ dateformat(@$awards[0]->award_date) }}" placeholder="Date" autocomplete="off" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Brief Description</label>
                        <textarea class="form-control" name="award[0][description]" placeholder="Brief Description" rows = "2">{{ @$awards[0]->description }}</textarea>
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="rb_award_section_2" class="rb_section">
            <div class="col-sm-12">
                <div class="section_title">
                    <input type="checkbox" id="check_rb_award_2" name="award_2" value="1" class="rb_section_checkbox" onclick="return toggleRBSection(this,'rb_award_section_2','{{ $action }}')" @if(!empty(@$awards[1]->id)) checked @endif/>
                    <span>Award #2</span>
                </div>
            </div>
            <div class="rb_section_content">
                <input type="hidden" name="award[1][award_id]" class="rb_existing_id" value="{{ @$awards[1]->id }}"/>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Award Name</label>
                        <input type="text" name="award[1][title]" class="form-control" value="{{ @$awards[1]->title }}" placeholder="Award Name" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Date</label>
                        <input type="text" name="award[1][award_date]" class="datepicker form-control" value="{{ dateformat(@$awards[1]->award_date) }}" placeholder="Date" autocomplete="off" />
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Brief Description</label>
                        <textarea class="form-control" name="award[1][description]" placeholder="Brief Description" >{{ @$awards[1]->description }}</textarea>
                        <div class="help-block with-errors"></div>
                        <div class="form-control-feedback"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12">
            <div class="form-actions">
                <button type="button" name="back" value="6" class="btn btn-info" onclick="return gotoRBStep(this.value,'prev')">Back</button>
                <button type="submit" name="save_preview" value="save_preview" class="btn btn-info" onclick="return setFormBtnAction('{{ $action }}',this.value)">Save & Preview</button>
                <button type="button" name="reset" class="btn btn-info" onclick="return resetResume();">Reset</button>
                <button type="button" name="cancel" class="btn btn-danger" onclick="return resumeBuilder('hide');">Cancel</button>
            </div>
        </div>
    </form>
@elseif($action == "resume_preview")
    <style type="text/css">
        .resume_table { font-size:12px; font-family:Arial, Helvetica, sans-serif; }
        .resume_table tr td { vertical-align:top; }
        .border_top td { border-top:solid 1px #333333;border-collapse:collapse; }
        .border_bottom td { border-bottom:solid 1px #333333; }
        .title { font-weight:bold;font-size:13px; }
        .picture { max-width:150px; }
        .tr_rb_section td { padding: 5px 10px; }
        .tr_intro td label { width: 100px; font-weight: bold; }
    </style>
    <table cellpadding="10" cellspacing="0" width="100%" border="0" class="resume_table">
        <tr>
            <td colspan="2" style="padding:0px; margin-bottom: 10px;">
                <table width="100%" border="0" cellpadding="10" cellspacing="0">
                    <tr class="tr_intro">
                        <td align="left" style="vertical-align:top;">
                            <strong style="font-size:16px;">{{ @$resume_data['full_name'] }}</strong><br/>
                            <label>Address</label><span>: </span> {{ $resume_data['address'] }}<br />
                            <label>Country</label><span>: </span> {{ get_country_name($resume_data['country_id']) }}<br />
                            <label>Phone</label><span>: </span> {{ $resume_data['primary_phone'] }} /Alt.: {{ $resume_data['secondary_phone'] }}<br />
                            <label>Skype</label><span>: </span> {{ $resume_data['skype'] }}<br />
                            <label>Email</label><span>: </span> {{ $resume_data['email'] }}
                        </td>
                        <td style="padding:10px;" align="right">
                            <img class="picture" src="{{ $passport_photo_url }}" />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td align="left" class="title" style="width:250px;">Objective</td>
            <td style="text-align:left;">{!! $resume_data['objective'] !!}</td>
        </tr>
        <tr>
            <td align="left" class="title">Summary</td>
            <td >{!! $resume_data['summary'] !!}</td>
        </tr>
        <tr>
            <td align="left" class="title">Qualifications &amp; Skills</td>
            <td>
                @if(!empty($resume_data['skill_computer_skills']))
                <strong style="font-size:11px;">Computer skills:</strong> {{ $resume_data['skill_computer_skills']}} <br />
                @endif
                @if(!empty($resume_data['skill_computer_programs']))
                <strong style="font-size:11px;">Computer Programs:</strong> {{ $resume_data['skill_computer_programs']}}<br />
                @endif
                @if(!empty($resume_data['skill_industry_programs']))
                <strong style="font-size:11px;">Industry specific programs:</strong> {{ $resume_data['skill_industry_programs']}}<br />
                @endif
                @if(!empty($resume_data['skill_language_spoken']))
                <strong style="font-size:11px;">Languages Spoken:</strong> {{ $resume_data['skill_language_spoken']}}<br />
                @endif
                @if(!empty($resume_data['skill_other_skills']))
                <strong style="font-size:11px;">Other skills:</strong> {{ $resume_data['skill_other_skills']}}
                @endif
            </td>
        </tr>
        @if(!empty($education))
            <tr class="border_bottom">
                <td align="left" class="title">Education</td>
                <td >&nbsp;</td>
            </tr>
            @foreach($education as $key => $item)
                <tr class="tr_rb_section">
                    <td>
                        @if(!empty(strtotime($item->start_date)))
                            <strong>From:</strong> {{ dateformat($item->start_date,DISPLAY_DATE) }} 
                        @endif
                        @if(!empty(strtotime($item->end_date)))
                            <strong>To:</strong> {{ dateformat($item->end_date,DISPLAY_DATE) }}
                        @endif
                    </td>
                    <td>
                        <strong style="font-size:14px;">{{ $item->school }}</strong><br />
                        {{ $item->degree }}<br />
                        {{ $item->minor }}<br />
                        {{ $item->description }}
                    </td>
                </tr>
            @endforeach
        @endif

        @if(!empty($employment))
            <tr class="border_bottom">
                <td align="left" class="title">Experience</td>
                <td >&nbsp;</td>
            </tr>
            @foreach($employment as $key => $item)
                <tr class="tr_rb_section">
                    <td>
                        @if(!empty(strtotime($item->start_date)))
                            <strong>From:</strong> {{ dateformat($item->start_date,DISPLAY_DATE) }} 
                        @endif
                        @if(!empty(strtotime($item->end_date)))
                            <strong>To:</strong> {{ dateformat($item->end_date,DISPLAY_DATE) }}
                        @endif
                    </td>
                    <td>
                        <strong style="font-size:14px;">{{ $item->title }}</strong><br />
                        {{ $item->employer_name }}<br />
                        {{ $item->location }}<br />
                        {{ $item->duties }}
                    </td>
                </tr>
            @endforeach
        @endif

        @if(!empty($certificates))
            <tr class="border_bottom">
                <td align="left" class="title">Training &amp; Certificates</td>
                <td >&nbsp;</td>
            </tr>
            @foreach($certificates as $key => $item)
                <tr class="tr_rb_section">
                    <td >{{ dateformat($item->date_of_certificate,DISPLAY_DATE) }}</td>
                    <td>
                        {{ $item->title }}<br />
                        {{ $item->location }}<br />
                        {{ $item->description }}
                    </td>
                </tr>
            @endforeach
        @endif

        @if(!empty($awards))
            <tr class="border_bottom">
                <td align="left" class="title">Awards &amp; Recognitions</td>
                <td >&nbsp;</td>
            </tr>
            @foreach($awards as $key => $item)
                <tr class="tr_rb_section">
                    <td >{{ dateformat($item->award_date,DISPLAY_DATE) }}</td>
                    <td>
                        {{ $item->title }}<br />
                        {{ $item->description }}
                    </td>
                </tr>
            @endforeach
        @endif

        <tr class="border_top">
            <td colspan="2" align="center">
                <br /><strong>Reference available upon request</strong>
            </td>
        </tr>
    </table>
@elseif($action == "resume_preview_modal")
    <div class="modal-content">
        <div class="modal-header bg-info">
            <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h2 class="modal-title text-white">Resume Preview</h2>
        </div>
        <div class="modal-body">{!! @$resume_preview !!}</div>
        <div class="modal-footer">
            <button type="button" class="btn btn-info mbottom5" onclick="return buildResume(this)">Build Resume</button>
            <button type="button" class="btn btn-danger mbottom5" data-dismiss="modal" aria-hidden="true">Cancel</button>
        </div>
    </div>
    <script>
        function buildResume(ele)
        {
            loadingButton(ele,'start');

            $.ajax({
                url: "{{ route('buildresumepdf') }}",
                type: 'post',
                dataType: 'json',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response){
                    if(response.type == "success"){
                        loadingButton(ele,'stop','Build Resume');
                        navigateStages('1');
                    }
                    hide_popup('modal-lg');
                },
            });
        }
    </script>
@else
    @if($is_step_success == 0)
        <form name="resume_upload" id="resume_upload" enctype="multipart/form-data" action="">
            <div class="row">
                <div class="col-sm-12">
                    <h3>Next step is to upload your resume...</h3> 
                    <div id="{{ $notify_id }}" class="col-xs-12"></div>
                    <p>Upload a resume that can impress your {{ __('application_term.employer') }}. It is recommended that you keep your resume less than 3 pages long.</p>
                    <p class="text-muted">Resume should be a {{ $allow_file_ext }} and doesn't exceed {{ $upload_file_size }} MB.</p><br>
                    <div class="form-group m-b-10 uploadresume">
                        <div class="row">
                            <div class="col-xs-12">
                                <p class="m-r-10 pull-left">Select a file:</p>
                                <input type="file" name="resume_file" class="di" @if($is_step_locked != 1) id="resume_file" onchange="return resumeUpload('{{ $next_step_key }}');" @else disabled @endif />
                            </div>
                        </div> 
                    </div> 
                    <div class="text-center response"></div>
                </div>    
            </div>
            <div class="clearfix"></div>
            <hr class="full_dotted_line m-b-20" />
        </form>
        @if($is_step_locked != 1)
        <div id="resume_builder">
            <div class="resume_builder_activator row">
                <div class="col-sm-1">
                    <div class="fa-2x plus_circle"><i class="fa fa-plus-circle"></i></div>
                </div>
                <div class="col-sm-11"> 
                    <a class="text-info fa-1x cpointer" data-toggle="collapse" data-target="#create_resume"> Don't have a resume?</a>
                    <div id="create_resume" class="collapse">
                        <p>If you don't have one, you can use our free resume builder.</p>
                        <button type="button" class="btn btn-sm btn-info disable" onclick="return resumeBuilder('show');">Create a Resume</button>
                    </div>
                </div>
            </div>
            <div class="resume_builder_form row" style="display: none;">
                
            </div>
        </div>
        @endif
    @elseif($is_step_success == 1 || $is_step_success == 2)
        <div class="row">
            <div class="col-sm-12">
                <h3>Next step is to upload your resume...</h3> 
                <p>Upload a resume that can impress your {{ __('application_term.employer') }}. It is recommended that you keep your resume less than 3 pages long.</p>
                <p class="text-muted">Resume should be a {{$allow_file_ext}} and doesn't exceed {{$upload_file_size}} MB.</p><br>
                <div class="text-center response alert alert-{{ $step_verified_data['type'] }}">{{ $step_verified_data['message'] }}</div>
                @if($is_step_success == 1)
                    @if(!empty($next_step_key))
                        <button type="button" class="btn btn-info" onclick="loadStepContent('{{ $next_step_key }}')">Next Step</button>
                    @endif
                @endif
            </div>    
        </div>
    @endif
    <script>
        $(document).ready(function(){

        });
        
        function resumeUpload(next_step_key){
            // $("#resume_upload .uploadresume").hide(); 
            $("#resume_upload .response").html('Please wait... <i class="fa fa-spin fa-spinner"></i>');
            var fd = new FormData();
            var files = $('#resume_file')[0].files[0];
            fd.append('resume',files);

            $.ajax({
                url:  "{{ route('uploadresume') }}",
                type: 'post',
                data: fd,
                dataType: 'json',
                contentType: false,
                processData: false, 
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response){
                    $("#resume_upload").trigger("reset");
                    if(response.type == "success"){
                        $("#resume_upload .response").removeClass().addClass('text-center response alert alert-success').html(response.message);
                        setTimeout(function(){
                            $("#resume_upload .response").hide()
                                .html("").removeClass()
                                .addClass('text-center response alert alert-warning')
                                .html("Hold tight, we are processing your request...").show();

                            setTimeout(function(){
                                navigateStages(1,next_step_key);
                            }, 3000);
                        }, 3000);
                    }
                    else if(response.type == "warning"){
                        $("#resume_upload .response").removeClass().addClass('text-center response alert alert-warning').html(response.message);
                    }
                    else{
                        $("#resume_upload .response").removeClass().addClass('text-center response alert alert-danger').html(response.message);
                    }
                },
            });
        }
        
        function resumeBuilder(action){
            if(action == "show"){
                $("#resume_builder .resume_builder_form").show();
                $("#resume_upload,#resume_builder .resume_builder_activator").hide();
                loadRBForm("1");
            }
            else{
                $("#resume_builder .resume_builder_form,#resume_builder .resume_builder_form form").hide();
                $("#resume_upload,#resume_builder .resume_builder_activator,#rb_1").show();
            }
        }
        
        function loadRBForm(form_number){
            
            var action = "form_rb_"+form_number;
            var form_selector = "#"+action;
            
            $.ajax({
                url:  "{{ route('loadrbform') }}",
                type: 'post',
                data: { form_number: form_number },
                dataType: 'json',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response){
                    if(response.type == "success"){
                        $("#resume_builder .resume_builder_form").html(response.form_content);
                        
                        initAjaxValidator(form_selector);
                        initRBSection();
                        load_datepicker();
                    }
                    else{
                        
                    }
                },
            });
        }
        
        function initAjaxValidator(rb_form_selector){
            
            ajaxFormValidator(rb_form_selector,function(ele,event){

                event.preventDefault();

                var currentStepNum = $(ele).find("input[name='rb_step']").val();
                /****/
                //show_inner_loader(".timeline_stp_desc","#all_tab_data");

                var btn_action = $(ele).find("input[name='btn_action']").val();
                if(btn_action == "" || btn_action == "undefined" || btn_action == null)
                {
                    return false;
                }

                var form_data = new FormData(ele);

                $(ele).find("input[name='btn_action']").val("");

                $.ajax({
                    url: "{{ route('resumebuilder') }}",
                    type: 'post',
                    data: form_data,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response){
                        //notifyResponse("#rb_1 .notify",response.message,response.type);
                        if(response.type == "success"){
                            if(response.btn_action == "save_next"){
                                gotoRBStep(response.action_step);
                            }
                            if(response.btn_action == "save_preview"){
                                popupRBPreview();
                            }
                        }else{
                            var Html = '<div class="alert swl-alert-danger">'+response.message+'</div>'; 
                            notifyResponseTimerAlert(Html,"error","Error");
                        }
                        hide_inner_loader(".timeline_stp_desc","#all_tab_data");
                    },
                });

                /****/
            });
        }

        function reNewPSP()
        {
            $("#rb_existing_psp").hide();
            $("#rb_existing_psp input").attr("disabled",true);

            $("#rb_upload_psp").show();
            $("#rb_upload_psp input").removeAttr("disabled");
        }

        function gotoRBStep(currentStepNum,direction)
        {
            var gotoStep = 1;
            if(direction == "prev"){
                gotoStep = Number(currentStepNum) - Number(1);
            }
            else{
                gotoStep = Number(currentStepNum) + Number(1);
            }
            loadRBForm(gotoStep);
        }

        function toggleRBSection(ele,section_id,form_id)
        {
            var section_selector = "#"+section_id+" .rb_section_content";
            var form_selector = "#"+form_id+" input[name='remove_existing_id']";
            var remove_existing_id = $(form_selector).val();
            var existing_id = $(section_selector).find(".rb_existing_id").val();

            if(remove_existing_id != "" && remove_existing_id != "undefined" && remove_existing_id != null)
            {
                remove_existing_id = remove_existing_id.split(',');
            }
            else
            {
                remove_existing_id = [];
            }

            if($(ele).is(":checked") == true){
                $(section_selector).show();
                $(section_selector).find("select, textarea, input").removeAttr('disabled');

                if(existing_id != "" && existing_id != "undefined" && existing_id != null){
                    remove_existing_id = removeArrayElement(remove_existing_id, existing_id);
                    $(form_selector).val(remove_existing_id);
                }
            }
            else{
                $(section_selector).hide();
                $(section_selector).find("select, textarea, input").attr('disabled',true);

                if(existing_id != "" && existing_id != "undefined" && existing_id != null){
                    remove_existing_id.push(existing_id);
                    $(form_selector).val(remove_existing_id);
                }
            }
        }

        function initRBSection()
        {
            $(".rb_section").each(function(i,e){
                if($(this).find(".rb_section_checkbox").length > 0)
                {
                    var ele_id = $(this).attr('id');
                    var section_selector = "#"+ele_id+" .rb_section_content";

                    if($(this).find(".rb_section_checkbox").is(":checked") == true)
                    {
                        $(section_selector).show();
                        $(section_selector).find("select, textarea, input").removeAttr('disabled');
                    }
                    else
                    {
                        $(section_selector).hide();
                        $(section_selector).find("select, textarea, input").attr('disabled',true);
                    }
                }
            });
        }

        function popupRBPreview()
        {
            show_popup('modal-lg');
            get_common_ajax('{{ route("previewresume") }}',{
                action: "resume_preview",
            },"modal-lg");
        }

        function resetResume()
        {
            //show_inner_loader(".timeline_stp_desc","#all_tab_data");
            $.ajax({
                url: "{{ route('resetresume') }}",
                type: "post",
                dataType: "json",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: { action : "reset_resume" },
                success:function(response){
                    if(response.type == "success"){
                        resumeBuilder('hide');
                    }
                }
            });
        }
    </script>
@endif