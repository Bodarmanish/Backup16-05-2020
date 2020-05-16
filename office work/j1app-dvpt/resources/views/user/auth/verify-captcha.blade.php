<div class="modal-content"> 
    <div class="modal-header bg-info">
        <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h2 class="modal-title text-white">reCAPTCHA</h2>
    </div>
    <div class="modal-body">
        <div class="col-md-12 text-center margin2-auto">
            {!! NoCaptcha::renderJs() !!}
            {!! NoCaptcha::display(['data-theme' => 'light','data-callback' => 'recaptchaCallback']) !!}
        </div>
    </div>
</div>
