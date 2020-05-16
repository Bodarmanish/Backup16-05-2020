@extends('user.layouts.app')

@section('content')
@php 
    $company_name = config("app.name");
@endphp
<!-- Wrapper -->
<div class="white-box">
    <h2 class="text-info text-uppercase main-title">Terms and Condition</h2> 
    <div class="row m-b-15">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
            <p>Thank you for choosing <span class="text-info">{{$company_name}}.</span> By using our services and websites or accessing any content or material through the Service you are agreeing to these terms.</p> 
            <ul>
                <li> These terms and conditions govern your use of this website, please read the following <b>TERMS AND CONDITIONS</b> of use carefully before using this website. If you do not agree to these terms and conditions, please discontinue use of our Service.</li>
            </ul>
        </div> 
    </div>
    <div class="row m-b-15">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4 class="text-info text-uppercase">COPYRIGHTS</h4>
            <ul>
                <li> Visitors are not allowed to distribute, exchange, modify, sell, or transmit any information published on this site. No material from this site may be copied, republished, uploaded, and posted in any form without prior written permission from {{$company_name}}.</li>
                <li> You may not use the Site or Content except as permitted in these Terms. You may use the Site and Content for your personal, non-commercial use only.</li>
                <li> Unauthorized use of the materials appearing on this site may violate copyright, and other applicable laws with the corresponding legal consequences.</li>
            </ul>
            <h5><strong>You must not:</strong></h5>
            <ul>
                <li> Sell, rent or sub-license material from the website.</li>
                <li> Reproduce, duplicate, copy or otherwise exploit material on this website for a commercial purpose.</li>
            </ul> 
        </div>         
    </div>

    <div class="row m-b-15">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4 class="text-info text-uppercase">LIMITATIONS OF LIABILITY</h4>
            <ul>
                <li> <span class="text-info">{{$company_name}}</span> expressly disclaims all kind of warranties, whether express, implied, or statutory.</li>
                <li> You acknowledge and agree that, <span class="text-info">{{$company_name}}</span> shall not be liable for any direct, indirect, incidental, special, or consequential damages that result from the use of, or the inability to use this site or content, even if <span class="text-info">{{$company_name}}</span> has been advice of the possibility of such damages.</li>
                <li>All information provided may be modified or terminated by <span class="text-info">{{$company_name}}</span> without notice. The conditions regarding Copyrights, Indemnification shall survive any termination of these terms and conditions.</li>
            </ul> 
        </div>         
    </div>

    <div class="row m-b-15">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4 class="text-info text-uppercase">INDEMNIFICATION</h4>
            <ul>
                <li> You agree to indemnify, and hold <span class="text-info">{{$company_name}}</span> harmless from and against all losses, liabilities, expenses, damages and costs, resulting from any violation of these Terms, any content you post to or make available on the Site, or any activity you or any other person accessing the Site using your account.</li>
            </ul> 
        </div>         
    </div>

    <div class="row m-b-15">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4 class="text-info text-uppercase">PRIVACY POLICY</h4>
            <ul>
                <li> We may use and share your information according to our Privacy Policy.</li>
            </ul> 
        </div>         
    </div>
</div>
@endsection
