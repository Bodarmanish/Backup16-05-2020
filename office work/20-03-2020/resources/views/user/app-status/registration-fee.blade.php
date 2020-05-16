@php
$reg_fee_status = @$step_verified_data['reg_fee_status'];
@endphp
<div class="row">
    <div class="col-sm-12">
        <h3>Registration Fee </h3>
        <p>$50 USD/EUR non-refundable registration fee. This is a one time, non-refundable fee for the processing of your application.</p>
        @if($is_step_success == 1)
            <div class="paynow_success">
                @if($reg_fee_status == 1)
                    <div class="alert alert-success p-25"> 
                        <p>Thank you, the payment has been confirmed.</p>
                        <p>Go to next step to update your Additional Information.</p> 
                    </div>
                    @if(!empty($next_step_key))
                        <button type="button" class="btn btn-info" onclick="loadStepContent('{{ $next_step_key }}')">Next Step</button>
                    @endif
                @elseif($reg_fee_status == 2)
                    <div class="alert alert-success p-25"> 
                        <p>Your registration fee is postponed.</p>
                        <p>Please click on "Next Step" and go to next step.</p> 
                    </div>
                    @if(!empty($next_step_key))
                        <button type="button" class="btn btn-info" onclick="loadStepContent('{{ $next_step_key }}')">Next Step</button>
                    @endif
                @endif
            </div>
        @else
            @if($is_step_locked != 1)
                <div class="text-center">
                    <form action="{{ config('common.paypal_settings.paypal_action_url') }}" method="post" target="_top">
                        @if(config('common.paypal_settings.enable_sandbox') == false)
                        <input type="hidden" value="images/itn-paypal-logo.jpg" name="cpp_logo_image"/>
                        <input type="hidden" value="#1d426e" name="cpp_cart_border_color"/>
                        @endif
                        <input type="hidden" name="custom" value="{{ $user->user_id_hash }}"/>
                        <input type="hidden" name="cmd" value="_s-xclick">
                        <input type="hidden" name="hosted_button_id" value="{{ config('common.paypal_settings.hosted_button_id') }}">
                        <input type="image" src="https://www.sandbox.paypal.com/en_GB/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!">
                        <img alt="" border="0" src="https://www.sandbox.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
                    </form>
                </div>
            @endif
        @endif
        <div class="clearfix"></div>
        <hr class="half_dotted_line m-b-20" />
        <img src="{{ asset($image_path.'payment.png') }}" class="img-responsive payment" />
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#pay_now").click(function(){
            show_popup('modal-lg');
            get_common_ajax('reg_payment_modal','modal-lg');
        });
    });
    
</script>