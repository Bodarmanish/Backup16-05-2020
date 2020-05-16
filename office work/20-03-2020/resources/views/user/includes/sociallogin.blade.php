@if(Request::route()->getName()=="register")
<div class="button-box">
    <button type="button" class="btn btn-facebook btn-rounded btn-block social_btn" onclick="window.location.href='{{ route("social.redirect",['provider'=>"facebook",'type'=>"register"]) }}'; $('.social_btn').prop('disabled', true); return false;">
        <span class="social-btn-logo"><i class="fa fa-facebook"></i></span>Sign up with Facebook
    </button>
</div>
<div class="button-box"> 
    <button type="button" class="btn btn-twitter btn-rounded btn-block social_btn" onclick="window.location.href='{{ route("social.redirect",['provider'=>"twitter",'type'=>"register"]) }}'; $('.social_btn').prop('disabled', true); return false;">
        <span class="social-btn-logo"><i class="fa fa-twitter"></i></span>Sign up with Twitter
    </button> 
</div>
<div class="button-box"> 
    <button type="button" class="btn btn-googleplus btn-rounded btn-block social_btn" onclick="window.location.href='{{ route("social.redirect",['provider'=>"google",'type'=>"register"]) }}'; $('.social_btn').prop('disabled', true); return false;">
        <span class="social-btn-logo"><i class="fa fa-google-plus"></i></span>Sign up with Google
    </button>
</div>
@else
<div class="button-box">
    <button type="button" class="btn btn-facebook btn-rounded btn-block social_btn" onclick="window.location.href='{{ route("social.redirect",['provider'=>"facebook",'type'=>"login"]) }}'; $('.social_btn').prop('disabled', true); return false;">
        <span class="social-btn-logo"><i class="fa fa-facebook"></i></span>Sign in with Facebook
    </button>
</div>
<div class="button-box">   
    <button type="button" class="btn btn-twitter btn-rounded btn-block social_btn" onclick="window.location.href='{{ route("social.redirect",['provider'=>"twitter",'type'=>"login"]) }}'; $('.social_btn').prop('disabled', true); return false;">
        <span class="social-btn-logo"><i class="fa fa-twitter"></i></span>Sign in with Twitter
    </button> 
</div>
<div class="button-box"> 
    <button type="button" class="btn btn-googleplus btn-rounded btn-block social_btn" onclick="window.location.href='{{ route("social.redirect",['provider'=>"google",'type'=>"login"]) }}'; $('.social_btn').prop('disabled', true); return false;">
        <span class="social-btn-logo"><i class="fa fa-google-plus"></i></span>Sign in with Google
    </button>
</div>
@endif
