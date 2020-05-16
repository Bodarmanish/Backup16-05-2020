<div class="card form-group mtop40" id="A_all_tab">
    <div class="card-body">
        <h3 class="card-title d-inline">Application Status – <small>Additional Information , Upload Resume , j1 Agreement</small></h3>
        <a href="javascript:void(0);" class="text-blue pull-right page_top">Back to Top <img src="images/page_up.png" width="15" /></a>
        <div class="clearfix p10"></div>
        <div class="card-text">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    @foreach($as as $key=>$value)
                        <a class='nav-item nav-link {{$loop->first ? 'active' : ''}}' id='A_{{$key}}_user_tab' data-toggle='tab' href='#A_{{$key}}' role='tab' aria-controls='nav-{{$key}}' aria-selected='true' title="{{$value}}">{{$value}}</a>
                    @endforeach
                </div>
            </nav>

            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="A_asProgress" role="tabpanel" aria-labelledby="A_asProgress_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">User App Progress</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.asprogress')}}" method="post" target="_blank" enctype="multipart/form-data">
                        <div class="row">                            
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">End Point:</label>
                                    <div class="col-sm-7">
                                        <label class="form-control" >/asprogress</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="usertoken" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="button" value="Submit" class="btn btn-primary userapi">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="tab-pane fade" id="A_applicationStatus" role="tabpanel" aria-labelledby="A_applicationStatus_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">User Application Status</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.application-status')}}" method="get" target="_blank" >
                       <div class="row">  
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">End Point:</label>
                                    <div class="col-sm-7">
                                        <label class="form-control" >/application-status</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="usertoken" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="button" value="Submit" class="btn btn-primary getuserapi">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="tab-pane fade" id="A_navigateStage" role="tabpanel" aria-labelledby="A_navigateStage_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">Navigate Stage</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.navigatestage')}}" method="post" target="_blank" enctype="multipart/form-data">
                       <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">End Point:</label>
                                    <div class="col-sm-7">
                                        <label class="form-control" >/navigatestage</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >action:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" readonly name="action" value="navigate_stage" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="servertoken">Active Stage:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="active_stage" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="servertoken">Step key:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="request_step_key" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="usertoken" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="button" value="Submit" class="btn btn-primary userapi">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="tab-pane fade" id="A_navigateStep" role="tabpanel" aria-labelledby="A_navigateStep_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">User Navigate Step</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.navigatestage')}}" method="post" target="_blank" enctype="multipart/form-data">
                       <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">End Point:</label>
                                    <div class="col-sm-7">
                                        <label class="form-control" >/navigatestage</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >action:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" readonly name="action" value="navigate_step" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="servertoken">Active Step key:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="active_step_key" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="usertoken" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="button" value="Submit" class="btn btn-primary userapi">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="tab-pane fade" id="A_eligibilityQuest" role="tabpanel" aria-labelledby="A_eligibilityQuest_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">Eligibility Quest</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.eligibilityquest')}}" method="post" target="_blank" enctype="multipart/form-data">
                       <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">End Point:</label>
                                    <div class="col-sm-7">
                                        <label class="form-control" >/eligibilityquest</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="servertoken">Eligibility Answer:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="eligibility_answer" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="usertoken" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="button" value="Submit" class="btn btn-primary userapi">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="tab-pane fade" id="A_uploadResume" role="tabpanel" aria-labelledby="A_uploadResume_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">Upload Resume</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.uploadresume')}}" method="post" target="_blank" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">End Point:</label>
                                    <div class="col-sm-7">
                                        <label class="form-control" >/uploadresume</label>                                    
                                    </div>                                
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Resume:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="file" name="resume" id="resume" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="usertoken" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="button" value="Submit" class="btn btn-primary userapi">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="tab-pane fade" id="A_resumebuilder" role="tabpanel" aria-labelledby="A_resumebuilder_user_tab">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <h3 class="card-title d-inline col-md-12 mtop40">Update Resume Candidate Information</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.resumebuilder')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4">End Point:</label>
                                            <div class="col-sm-7">
                                                <label class="form-control" >/resumebuilder</label>
                                            </div>
                                        </div>
                                        <input type="hidden" name="rb_step" value="1" class="form-control" />
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >First Name:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="first_name" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Last Name:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="last_name" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Email:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="email" name="email" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Address:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="address" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Country:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="country" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Phone No:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="phone_no" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Mobile No:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="mobile_no" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Skype id:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="skype" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Objective:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="objective" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Summary:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="summary" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Passport Photo:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="file" name="passport_photo" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="usertoken" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group"> 
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <input type="button" value="Submit" class="btn btn-primary userapi">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <h3 class="card-title d-inline col-md-12 mtop40">Update Resume SKILLS & ABILITIES</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.resumebuilder')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4">End Point:</label>
                                            <div class="col-sm-7">
                                                <label class="form-control" >/resumebuilder</label>
                                            </div>
                                        </div>
                                        <input type="hidden" name="rb_step" value="2" class="form-control" />
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Computer skills:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="skill_computer_skills" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Computer Programs:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="skill_computer_programs" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Industry specific programs:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="skill_industry_programs" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Languages Spoken:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="skill_language_spoken" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Other skills:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="skill_other_skills" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="usertoken" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <input type="hidden" readonly name="act" value="" class="form-control" />
                                        <div class="form-group"> 
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <input type="button" value="Submit" class="btn btn-primary userapi">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <h3 class="card-title d-inline col-md-12 mtop40">Update Resume Education</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.resumebuilder')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4">End Point:</label>
                                            <div class="col-sm-7">
                                                <label class="form-control" >/resumebuilder</label> 
                                            </div>
                                        </div>
                                        <input type="hidden" name="rb_step" value="3" class="form-control" />
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >School Name/Location:<span class="text-danger">  *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="school" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Degree Name:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="degree" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Start Date:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="start_date" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >End Date:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="end_date" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Concentration or Minor:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="minor" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Brief description of degree or courses taken:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="description" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="usertoken" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group"> 
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <input type="button" value="Submit" class="btn btn-primary userapi">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <h3 class="card-title d-inline col-md-12 mtop40">Update Resume Empolyment History</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.resumebuilder')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4">End Point:</label>
                                            <div class="col-sm-7">
                                                <label class="form-control" >/resumebuilder</label>
                                            </div>
                                        </div>
                                        <input type="hidden" name="rb_step" value="4" class="form-control" />
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Job Title:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="title" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Host Company Name:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="employment[0][employer_name]" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Start Date:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="start_date" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >End Date:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="end_date" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Location:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="location" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Duties:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="duties" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="usertoken" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group"> 
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <input type="button" value="Submit" class="btn btn-primary userapi">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <h3 class="card-title d-inline col-md-12 mtop40">Credentials & Certifications</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.resumebuilder')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4">End Point:</label>
                                            <div class="col-sm-7">
                                                <label class="form-control" >/resumebuilder</label>
                                            </div>
                                        </div>
                                        <input type="hidden" name="rb_step" value="5" class="form-control" />
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Certificate Title:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="title" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Location:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="location" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Date:</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="date_of_certificate" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Brief Description:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="description" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="usertoken" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group"> 
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <input type="button" value="Submit" class="btn btn-primary userapi">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <h3 class="card-title d-inline col-md-12 mtop40">Awards & Recognitions</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.resumebuilder')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4">End Point:</label>
                                            <div class="col-sm-7">
                                                <label class="form-control" >/resumebuilder</label>
                                            </div>
                                        </div>
                                        <input type="hidden" name="rb_step" value="6" class="form-control" />
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Certificate Title:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="title" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Location:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="location" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Date:</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="date_of_certificate" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Brief Description:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="description" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="usertoken" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group"> 
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <input type="button" value="Submit" class="btn btn-primary userapi">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="A_loadRBForm" role="tabpanel" aria-labelledby="A_loadRBForm_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">Load RBForm</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.loadrbform')}}" method="post" target="_blank" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">End Point:</label>
                                    <div class="col-sm-7">
                                        <label class="form-control" >/loadrbform</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Form Number:</label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="form_number" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="usertoken" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="button" value="Submit" class="btn btn-primary userapi">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="tab-pane fade" id="A_resetResume" role="tabpanel" aria-labelledby="A_resetResume_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">Reset Resume</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.resetresume')}}" method="post" target="_blank" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">End Point:</label>
                                    <div class="col-sm-7">
                                        <label class="form-control" >/resetresume</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="usertoken" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="button" value="Submit" class="btn btn-primary userapi">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="tab-pane fade" id="A_previewResume" role="tabpanel" aria-labelledby="A_previewResume_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">Preview Resume</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.previewresume')}}" method="post" target="_blank" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">End Point:</label>
                                    <div class="col-sm-7">
                                        <label class="form-control" >/previewresume</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Action:</label>
                                    <div class="col-sm-7"> 
                                        <input type="text" readonly name="action" value="resume_preview" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="usertoken" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="button" value="Submit" class="btn btn-primary userapi">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="tab-pane fade" id="A_buildResumePDF" role="tabpanel" aria-labelledby="A_buildResumePDF_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">Build Resume PDF</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.buildresumepdf')}}" method="post" target="_blank" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">End Point:</label>
                                    <div class="col-sm-7">
                                        <label class="form-control" >/buildresumepdf</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="usertoken" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="button" value="Submit" class="btn btn-primary userapi">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="tab-pane fade" id="A_updateSkype" role="tabpanel" aria-labelledby="A_updateSkype_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">Update Skype</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.updateskype')}}" method="post" target="_blank" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">End Point:</label>
                                    <div class="col-sm-7">
                                        <label class="form-control" >/updateskype</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Skype id:</label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="skype_id" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="usertoken" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="button" value="Submit" class="btn btn-primary userapi">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="tab-pane fade" id="A_requestFinance" role="tabpanel" aria-labelledby="A_requestFinance_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">Request Finance</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.requestfinance')}}" method="post" target="_blank" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">End Point:</label>
                                    <div class="col-sm-7">
                                        <label class="form-control" >/requestfinance</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="usertoken" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="button" value="Submit" class="btn btn-primary userapi">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="tab-pane fade" id="A_additionalInformation" role="tabpanel" aria-labelledby="A_additionalInformation_user_tab">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <h3 class="card-title d-inline col-md-12 mtop40">Update Basic Details</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.addinfo')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <input type="hidden" name="addinfo_form_step" value="1" class="form-control" />
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4">End Point:</label>
                                            <div class="col-sm-7">
                                                <label class="form-control" >/addinfo</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Fast Name:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="fname" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Middle Name:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="mname" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Last Name:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="lname" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Phone Number:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="phone_number" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="usertoken" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group"> 
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <input type="button" value="Submit" class="btn btn-primary userapi">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <h3 class="card-title d-inline col-md-12 mtop40">Update Address</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.addinfo')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <input type="hidden" name="addinfo_form_step" value="2" class="form-control" />
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4">End Point:</label>
                                            <div class="col-sm-7">
                                                <label class="form-control" >/addinfo</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Street Address:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="address_street" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >City:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="address_city" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Zip/Postal Code:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="address_zip" class="form-control" />
                                            </div>
                                        </div>
                                       <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >State:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="state" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Country:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="address_country" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="usertoken" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group"> 
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <input type="button" value="Submit" class="btn btn-primary userapi">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <h3 class="card-title d-inline col-md-12 mtop40">Passport Information</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.addinfo')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <input type="hidden" name="addinfo_form_step" value="3" class="form-control" />
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4">End Point:</label>
                                            <div class="col-sm-7">
                                                <label class="form-control" >/addinfo</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Passport Number:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="passport_number" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Passport Issuing Date:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="date" name="passport_issued" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Passport Expiration Date:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="date" name="passport_expires" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Date of Birth:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="date" name="birth_date" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >City of Birth:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="birth_city" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Country of Birth:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="birth_country" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Citizen of Country:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="country_citizen" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Legal Permanent Resident:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="country_resident" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Passport Issuing Country:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="country_issuer" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="usertoken" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group"> 
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <input type="button" value="Submit" class="btn btn-primary userapi">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <h3 class="card-title d-inline col-md-12 mtop40">Previous J1 Program</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.addinfo')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <input type="hidden" name="addinfo_form_step" value="4" class="form-control" />
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4">End Point:</label>
                                            <div class="col-sm-7">
                                                <label class="form-control" >/addinfo</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Have you participated in a J-1 Programs in the past ? :<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7">
                                                <label > Yes</label>
                                                <input type="radio" name="previously_participated" value="1" required>
                                                <label > No</label>
                                                <input type="radio" name="previously_participated" value="2" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Recent Program Name:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="j1_recent_program_name" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Start Date:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="date" name="j1_recent_program_start" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >End Date:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="date" name="j1_recent_program_end" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="usertoken" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group"> 
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <input type="button" value="Submit" class="btn btn-primary userapi">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <h3 class="card-title d-inline col-md-12 mtop40">Spouse</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.addinfo')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <input type="hidden" name="addinfo_form_step" value="5" class="form-control" />
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4">End Point:</label>
                                            <div class="col-sm-7">
                                                <label class="form-control" >/addinfo</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Marital Status:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <label > Single</label>
                                                <input type="radio" name="marital_status" value="1" required>
                                                <label > Married</label>
                                                <input type="radio" name="marital_status" value="2" required>
                                                <label > Divorced</label>
                                                <input type="radio" name="marital_status" value="3" required>
                                                <label > Widowed</label>
                                                <input type="radio" name="marital_status" value="4" required><br>
                                                <span>if you are <strong>Married</strong> then all fields are required.</span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Will your spouse need J-2 Visa to enter U.S. ? :<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="spouse_dep_needs_j2" class="form-control" maxlength="1" oninput="this.value=this.value.replace(/[^1-2]/g,'');" />
                                                <strong>1=No, 2=Yes</strong>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Spouse Family Name:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="spouse_dep_last_name" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Spouse Given Name:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="spouse_dep_first_name" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Spouse Middle Name:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="spouse_dep_middle_name" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Spouse Gender:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <label > Male</label>
                                                <input type="radio" name="spouse_dep_gender" value="1" required>
                                                <label > Female</label>
                                                <input type="radio" name="spouse_dep_gender" value="2" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Spouse Birth date:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="date" name="spouse_dep_birth_date" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Spouse City of Birth:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="spouse_dep_birth_city" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Spouse Country of Birth:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="spouse_dep_birth_country" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Do you have any other dependents ? :<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <label > Yes</label>
                                                <input type="radio" name="other_dependants" value="1" required>
                                                <label > No</label>
                                                <input type="radio" name="other_dependants" value="2" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Spouse enters U.S. at same time with you ? :<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <label > Yes</label>
                                                <input type="radio" name="spouse_dep_us_entry_together" value="1" required>
                                                <label > No</label>
                                                <input type="radio" name="spouse_dep_us_entry_together" value="2" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="usertoken" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group"> 
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <input type="button" value="Submit" class="btn btn-primary userapi">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <h3 class="card-title d-inline col-md-12 mtop40">Education</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.addinfo')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <input type="hidden" name="addinfo_form_step" value="6" class="form-control" />
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4">End Point:</label>
                                            <div class="col-sm-7">
                                                <label class="form-control" >/addinfo</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Are you currently a full time student ? :<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <label > Yes</label>
                                                <input type="radio" name="currently_student" value="1" required>
                                                <label > No</label>
                                                <input type="radio" name="currently_student" value="2" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Educational institution last or presently attending:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="institution" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Educational institution type:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <select name="institution_type" id="institution_type" class="form-control disable-control" required="">
                                                    <option selected disabled>-- Select Option --</option>
                                                    <option value="1">High/Secondary School</option>
                                                    <option value="2">University/College</option>
                                                    <option value="3">Vocational Training</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Field studied / presently studying:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="field_studied" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Study level:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <select name="study_level" class="form-control" required="">
                                                    <option selected disabled >-- Select Option --</option>
                                                    <option value="1">Bachelors Degree</option>
                                                    <option value="2">Masters Degree</option>
                                                    <option value="3">Doctorate</option>
                                                    <option value="4">Baccalaureate</option>
                                                    <option value="5">Diploma</option>
                                                    <option value="6">Associates Degree</option>
                                                    <option value="7">Postgraduate</option>
                                                    <option value="8">Certificate</option>
                                                    <option value="9">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Date started studying:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="date" name="program_start" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Date Graduated (estimated or actual):<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="date" name="program_end" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Advance Course worked Completed (if applicable):</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="advance_completed" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Year of experience in the field:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="experience_year" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="usertoken" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group"> 
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <input type="button" value="Submit" class="btn btn-primary userapi">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <h3 class="card-title d-inline col-md-12 mtop40">Work Experience</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.addinfo')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <input type="hidden" name="addinfo_form_step" value="7" class="form-control" />
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4">End Point:</label>
                                            <div class="col-sm-7">
                                                <label class="form-control" >/addinfo</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Are you Currently Employed ? :<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <label > Yes</label>
                                                <input type="radio" name="currently_employed" value="1" required>
                                                <label > No</label>
                                                <input type="radio" name="currently_employed" value="2" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Name of company:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="employer_name" class="form-control" />
                                            </div>  
                                        </div> 
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Company address:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="employer_address" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Total Number of employees:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="total_employees" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Your Training Position:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="position" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Full Name of supervisor/owner:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="sup_name" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Phone Number (with Country and Area code):<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="employer_phone" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Fax Number (with Country and Area code):</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="employer_fax" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Computer program skills (coma separated):</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="computer_programs" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Employment start date:</label>
                                            <div class="col-sm-7"> 
                                                <input type="date" name="emp_start_date" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="usertoken" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group"> 
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <input type="button" value="Submit" class="btn btn-primary userapi">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <h3 class="card-title d-inline col-md-12 mtop40">Emergency Contacts</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.addinfo')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <input type="hidden" name="addinfo_form_step" value="8" class="form-control" />
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4">End Point:</label>
                                            <div class="col-sm-7">
                                                <label class="form-control" >/addinfo</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Emergency Contact First Name:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="contact_name_first" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Emergency Contact Last Name:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="contact_name_last" class="form-control" />
                                            </div>  
                                        </div> 
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Phone Number (with Country and Area Code):<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="contact_phone" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Alternate Phone Number (with Country and Area Code):</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="contact_phone_alternative" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Relationship:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <select name="contact_relationship" id="contact_relationship" class="form-control" required="">
                                                    <option selected disabled>-- Select Option --</option>
                                                    <option value="1">Student</option>
                                                    <option value="2">Parent</option>
                                                    <option value="3">Spouse or Partner</option>
                                                    <option value="4">Child</option>
                                                    <option value="5">Sibling</option>
                                                    <option value="6">Other</option>
                                                    <option value="7">Teacher</option>
                                                    <option value="8">Friend</option>
                                                    <option value="9">School Official</option>
                                                    <option value="10">Reference</option>
                                                    <option value="11">Spouse</option>
                                                    <option value="12">Host</option>
                                                    <option value="13">Step Parent</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Contact Location Country:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7">
                                                <input type="text" name="contact_country" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Contact is English Speaking:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <label > Yes</label>
                                                <input type="radio" name="contact_english_speaking" value="1">
                                                <label > No</label>
                                                <input type="radio" name="contact_english_speaking" value="2">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Language spoken:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="contact_language" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Email Address:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="email" name="contact_email" class="form-control" required />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="usertoken" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group"> 
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <input type="button" value="Submit" class="btn btn-primary userapi">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">Criminal Background</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.addinfo')}}" method="post" target="_blank" enctype="multipart/form-data">
                        <div class="row">
                            <input type="hidden" name="addinfo_form_step" value="9" class="form-control" />
                            <div class="col-sm-8">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-4">End Point:</label>
                                    <div class="col-sm-6">
                                        <label class="form-control" >/addinfo</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-4" >Have you ever been convicted of a crime ? :<span class="text-danger"> *</span></label>
                                    <div class="col-sm-6"> 
                                        <label > Yes</label>
                                        <input type="radio" name="criminal_record" id="contact_english_speaking_yes" value="1" required>
                                        <label > No</label>
                                        <input type="radio" name="criminal_record" id="contact_english_speaking_no" value="2">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-4" >If you selected yes Please explain :<span class="text-danger"> *</span></label>
                                    <div class="col-sm-6"> 
                                        <input type="text" name="criminal_explanation" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-4" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-6"> 
                                        <input type="text" name="usertoken" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="button" value="Submit" class="btn btn-primary userapi">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="tab-pane fade" id="A_j1Agreement" role="tabpanel" aria-labelledby="A_j1Agreement_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">j1 Agreement</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.j1agree')}}" method="post" target="_blank" enctype="multipart/form-data">
                        <div class="row">
                            <input type="hidden" name="addinfo_form_step" value="9" class="form-control" />
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-4">End Point:</label>
                                    <div class="col-sm-6">
                                        <label class="form-control" >/j1agree</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-4" >Terms Agreed:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-6"> 
                                        <input type="checkbox" name="terms_agreed" value="1" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-4" for="servertoken">Authorization Token:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-6"> 
                                        <input type="text" name="usertoken" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="button" value="Submit" class="btn btn-primary userapi">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
</div>
