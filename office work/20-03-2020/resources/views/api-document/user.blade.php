<div class="card form-group mtop40" id="U_all_tab">
    <div class="card-body">
        <h3 class="card-title d-inline">User</h3>
        <a href="javascript:void(0);" class="text-blue pull-right page_top">Back to Top <img src="images/page_up.png" width="15" /></a>
        <div class="clearfix p10"></div>
        <div class="card-text">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    @foreach($user as $key=>$value)
                        <a class='nav-item nav-link {{$loop->first ? 'active' : ''}}' id='U_{{$key}}_user_tab' data-toggle='tab' href='#U_{{$key}}' role='tab' aria-controls='nav-{{$key}}' aria-selected='true' title="{{$value}}">{{$value}}</a>
                    @endforeach
                </div>
            </nav>

            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="U_userLogin" role="tabpanel" aria-labelledby="U_userLogin_user_tab">
                    <div class="form-group row mtop40">
                        <div class="col-md-6 col-sm-12">
                            <h3 class="card-title">User Login</h3>
                            <form class="form-horizontal" action="{{route('api.login')}}" method="post" target="_blank">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" for="j1_email_address">End Point:</label>
                                            <div class="col-sm-7"> 
                                                <label class="form-control">/login</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" for="j1_email_address">j1_email_address:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="email" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" for="password">password:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="password" value="" class="form-control" />
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
                <div class="tab-pane fade " id="U_userRegister" role="tabpanel" aria-labelledby="U_userRegister_user_tab">
                    <div class="form-group row mtop40">
                        <div class="col-md-6 col-sm-12">
                            <h3 class="card-title">User Registration</h3>
                            <form class="form-horizontal" action="{{route('api.register')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" for="j1_email_address">End Point:</label>
                                            <div class="col-sm-7"> 
                                                <label class="form-control" >/register</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" for="first_name">first_name:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="first_name" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" for="last_name">last_name:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="last_name" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" for="j1_email_address">email_address:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="email_address" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" for="password">password:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="password" value="" class="form-control" />
                                            </div>
                                        </div> 
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" for="confirm_password">confirm_password:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="password_confirmation" value="" class="form-control" />
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
                <div class="tab-pane fade" id="U_getProfileInfo" role="tabpanel" aria-labelledby="U_getProfileInfo_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">User Profile Info</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.profile')}}" method="get" target="_blank">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="j1_email_address">End Point:</label>
                                    <div class="col-sm-7"> 
                                        <label class="form-control" >/profile</label>
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
                
                <div class="tab-pane fade" id="U_userForgotPwd" role="tabpanel" aria-labelledby="U_userForgotPwd_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">User Forgot Password</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.password.email')}}" method="post" target="_blank" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">End Point:</label>
                                    <div class="col-sm-7"> 
                                        <label class="form-control" >/password/email</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="email_address">email_address:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="email" name="email" value="" class="form-control" />
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
                
                <div class="tab-pane fade" id="U_userResetPwd" role="tabpanel" aria-labelledby="U_userResetPwd_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">Reset Password</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.password.reset')}}" method="post" target="_blank" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">End Point:</label>
                                    <div class="col-sm-7"> 
                                        <label class="form-control" >/password/reset</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="email_address">token:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="token" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="email_address">email_address:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="email" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="email_address">password:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="password" name="password" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="email_address">Password Confirmation:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="password" name="password_confirmation" value="" class="form-control" />
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
                
                <div class="tab-pane fade" id="U_updateProfile" role="tabpanel" aria-labelledby="U_updateProfile_user_tab">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <h3 class="card-title d-inline col-md-12 mtop40">Update User Profile</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.update-profile')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" for="j1_email_address">End Point:</label>
                                            <div class="col-sm-7"> 
                                                <label class="form-control" >/update-profile</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" for="first_name">action:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" readonly name="action" value="editProfile " class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" for="first_name">first_name:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="first_name" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" for="last_name">last_name:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="last_name" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" for="j1_email_address">phone_number:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="phone_number" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" for="password">secondary_email:</label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="secondary_email" value="" class="form-control" />
                                            </div>
                                        </div> 
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" for="confirm_password">timezone:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="timezone" value="" class="form-control" />
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
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <h3 class="card-title d-inline col-md-12 mtop40">User Change Password</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.update-profile')}}" method="post" target="_blank">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3">End Point:</label>
                                            <div class="col-sm-7"> 
                                                <label class="form-control" >/update-profile</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" for="first_name">action:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" readonly name="action" value="changePassword" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" for="old_pwd">current_password:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="current_password" class="form-control" value="" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" for="new_pwd">new_password:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="new_password" class="form-control" value="" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" for="confirm_pwd">confirm_password:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="new_confirm_password" class="form-control" value="" s/>
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
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <h3 class="card-title d-inline col-md-12 mtop40">Edit Profile Address</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.update-profile')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3">End Point:</label>
                                            <div class="col-sm-7"> 
                                                <label class="form-control" >/update-profile</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" >action:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" readonly name="action" value="editProfileAddress" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" >street:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="street" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" >city:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="city" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" >zip_code:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="zip_code" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" >country:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="country" value="" class="form-control" />
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
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <h3 class="card-title d-inline col-md-12 mtop40">Edit Social Detail</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.update-profile')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3">End Point:</label>
                                            <div class="col-sm-7"> 
                                                <label class="form-control" >/update-profile</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" >action:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" readonly name="action" value="editSocialDetail" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" >facebook_url:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="facebook_url" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" >twitter_url:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="twitter_url" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" >skype_id:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="skype_id" value="" class="form-control" />
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
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <h3 class="card-title d-inline col-md-12 mtop40">Remove SocialID</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.update-profile')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3">End Point:</label>
                                            <div class="col-sm-7"> 
                                                <label class="form-control" >/update-profile</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" >action:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" readonly name="action" value="deleteSocialID" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" >social_id:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="social_id" value="" class="form-control" />
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
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <h3 class="card-title d-inline col-md-12 mtop40">Store User Notification Status</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.update-profile')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3">End Point:</label>
                                            <div class="col-sm-7"> 
                                                <label class="form-control" >/update-profile</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" >action:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" readonly name="action" value="notification_type_id" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" >value:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="value" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" >status:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="status" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3" >notification type id:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" name="notification_type_id" value="" class="form-control" />
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
                    </div>
                </div>
                
                <div class="tab-pane fade" id="U_userLogout" role="tabpanel" aria-labelledby="U_userLogout_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">User Logout</h3>
                    </div>
                    <form class="form-horizontal" id="logout" action="{{route('api.logout')}}" method="get" target="_blank">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">End Point:</label>
                                    <div class="col-sm-7"> 
                                        <label class="form-control" >/logout</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" for="apiuser">Authorization Token:<span class="text-danger"> *</span></label>
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
               
            </div>
        </div>
    </div>
</div>