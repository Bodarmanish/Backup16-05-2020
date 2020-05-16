@extends('admin.layouts.app')
@php
    $settings_section = config("common.setting_section");
    $ps_data = @$ps_data;
@endphp
@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">System settings</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="active">System Settings</li>
            </ol>
        </div>
    </div>
    <div class="well">
        <div class="row">
            <div class="form-group col-md-4 col-xs-12">
                <label>Select Settings Section</label>
                <select name="settings_section" id="settings_section" class="form-control">
                    <option value="none">-- Select System Settings Section --</option>
                    @if (!empty($settings_section))
                        @foreach($settings_section as $key => $value)
                            <option value="{{$key}}" {{ is_selected(old('action'),$key) }}>{{$value}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>
    <div class="row settings_section" id="common_setting">
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Update Common Settings</h3>
                    </div>
                </div>
                @if(old('action') == 'common_setting')
                    @include('admin.includes.status')
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="row m-t-20">
                            <div class="col-xs-12">
                            </div>
                        </div>
                        <form method="post" action="{{ route('system.settings.edit') }}" class="form-horizontal form-validator" novalidate="true">
                            {{ csrf_field() }}
                            <input type="hidden" id="action" name="action" value="common_setting">
                            <div class="form-group">
                                <label class="col-md-12">Application Name <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="app_name" id="app_name" placeholder="Application Name" class="form-control" required="" value="{{config('app.name')}}">
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">App URL <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="app_url" id="app_url" placeholder="App URL" class="form-control" value="{{config('app.url')}}" required>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Contact Email Address <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="contact_email" id="contact_email" placeholder="Contact Email Address" class="form-control" value="{{config('common.contact_email')}}" required>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Upload File Size (MB) <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="upload_file_size" id="upload_file_size" placeholder="Upload File Size" class="form-control" value="{{config('common.upload_file_size')}}" required>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Upload Image Size (MB) <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="upload_img_size" id="upload_img_size" placeholder="Upload Image Size" class="form-control" value="{{config('common.upload_img_size')}}" required>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info disabled">Save Common Settings</button>
                                    <button type="reset" class="btn btn-danger">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row settings_section" id="password_setting">
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Update Password Settings</h3>
                    </div>
                </div>
                @if(old('action') == 'password_setting')
                    @include('admin.includes.status')
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="row m-t-20">
                            <div class="col-xs-12">
                            </div>
                        </div>
                        <form method="post" action="{{ route('system.settings.edit') }}" class="form-horizontal form-validator" novalidate="true">
                            {{ csrf_field() }}
                             <input type="hidden" id="action" name="action" value="password_setting">
                            <div class="form-group">
                                <label class="col-md-12">Minimum Password Length <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="min_limit" id="min_limit" placeholder="Minimum Password Length" class="form-control" required="" value="{{$ps_data->min_limit}}">
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Password Pattern <span class="text-danger">*</span></label>
                                <div class="col-md-12  m-l-20">
                                    <label><input type="checkbox" name="password_pattern[]" value="one_alphabet" {{ in_array('one_alphabet',$ps_data->password_pattern)? "checked" : "" }}> One alphabet</label>
                                </div>
                                <div class="col-md-12 m-l-20">
                                    <label><input type="checkbox" name="password_pattern[]" value="one_digit" {{ in_array('one_digit',$ps_data->password_pattern)? "checked" : "" }}> One digit</label>
                                </div>
                                <div class="col-md-12 m-l-20">
                                    <label><input type="checkbox" name="password_pattern[]" value="one_special" {{ in_array('one_special',$ps_data->password_pattern)? "checked" : "" }}> One Special Character From (!@#$%*)</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info disabled">Save Password Settings</button>
                                    <button type="reset" class="btn btn-danger">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row settings_section" id="social_setting">
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Update Social Settings</h3>
                    </div>
                </div>
                @if(old('action') == 'social_setting')
                    @include('admin.includes.status')
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="row m-t-20">
                            <div class="col-xs-12">
                            </div>
                        </div>
                        <form method="post" action="{{ route('system.settings.edit') }}" class="form-horizontal form-validator" novalidate="true">
                            {{ csrf_field() }}
                            <input type="hidden" id="action" name="action" value="social_setting">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="col-md-12">Google Client Id<span class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <input type="text" name="google_client_id" id="google_client_id" placeholder="Google Client Id" class="form-control" value="{{config('services.google.client_id')}}" required>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="form-group  col-md-6">
                                    <label class="col-md-12">Google Client Secret <span class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <input type="text" name="google_client_secret" id="google_client_secret" placeholder="Google Client Secret" class="form-control" value="{{config('services.google.client_secret')}}" required>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="col-md-12">Facebook Client Id <span class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <input type="text" name="facebook_client_id" id="facebook_client_id" placeholder="Facebook Client Id" class="form-control" value="{{config('services.facebook.client_id')}}" required>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-md-12">Facebook Client Secret <span class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <input type="text" name="facebook_client_secret" id="facebook_client_secret" placeholder="Facebook Client Secret" class="form-control" value="{{config('services.facebook.client_secret')}}" required>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="col-md-12">Twitter Client Id <span class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <input type="text" name="twitter_client_id" id="twitter_client_id" placeholder="Twitter Client Id" class="form-control" value="{{config('services.twitter.client_id')}}" required>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-md-12">Twitter Client Secret <span class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <input type="text" name="twitter_client_secret" id="twitter_client_secret" placeholder="Twitter Client Secret" class="form-control" value="{{config('services.twitter.client_secret')}}" required>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="col-md-12">Google reCAPTCHA Site Key <span class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <input type="text" name="google_captcha_site_key" id="google_captcha_site_key" placeholder="Google reCAPTCHA Site Key" class="form-control" value="{{config('captcha.sitekey')}}" required>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-md-12">Google reCAPTCHA Site Secret: <span class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <input type="text" name="google_captcha_site_secret" id="google_captcha_site_secret" placeholder="Google reCAPTCHA Site Secret:" class="form-control" value="{{config('captcha.secret')}}" required>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info disabled">Save Social Settings</button>
                                    <button type="reset" class="btn btn-danger">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row settings_section" id="clear_cache">
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Clear Cache</h3>
                    </div>
                </div>
                @if(old('action') == 'clear_cache')
                    @include('admin.includes.status')
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="row m-t-20">
                            <div class="col-xs-12">
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <button class="btn btn-info m-r-15" onclick="clearCache('{{ route('configcacheclear') }}');">Create Configuration Cache<br><small>(config:cache)</small></button>
                                    <button class="btn btn-info m-r-15" onclick="clearCache('{{ route('configclear') }}');">Remove Configuration Cache<br><small>(config:clear)</small></button>
                                    <button class="btn btn-info m-r-15" onclick="clearCache('{{ route('cacheclear') }}');">Clear Application Cache<br><small>(cache:clear)</small></button>
                                    <button class="btn btn-info" onclick="clearCache('{{ route('viewclear') }}');">Clear Compiled Views<br><small>(view:clear)</small></button>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
$(document).ready(function (url) {
    $('.settings_section').hide();
    id = $("#settings_section").val();
    $("#"+id).show();

    $(document).on('change', '#settings_section', function () {
        $('.settings_section').hide();
        id = $(this).val();
        $("#"+id).show();
    });
});
function clearCache(url){ 
    showLoader("#full-overlay");
    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response){
            if(response.type == "success")
            {
                var Html = '<div class="alert swl-alert-success"><p>'+response.message+'</p></div>'; 
                notifyResponseTimerAlert(Html,"success","Success");
            }
            else
            {
                var Html = '<div class="alert swl-alert-danger"><p>Something went wrong.</p></div>'; 
                notifyResponseTimerAlert(Html,"error","Error");
            }
            hideLoader("#full-overlay");
        },
    });
}
</script>
@endsection