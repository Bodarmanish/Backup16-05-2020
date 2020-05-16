@extends('user.layouts.app')

@section('content')
@php  
    $company_name = config("app.name");
    $company_email = config("common.contact_email");
@endphp 
    <div class="white-box">
        <h2 class="text-info text-uppercase main-title">Privacy Notice</h2>
        <div class="row m-b-15">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <ul>
                    <li>Here at <span class="text-info">{{$company_name}}</span>, we take personal privacy very seriously. As a general rule <span class="text-info">{{$company_name}}</span> does not collect your personal information unless you chose to provide that information to us. When you choose to provide us with your personal information, you are giving <span class="text-info">{{$company_name}}</span> your permission to use that information for the stated purposes listed in this privacy policy. If you choose not to provide us with that information, it might limit the features and services that you can use on this website.</li>
                    <li><span class="text-info">{{$company_name}}</span> is committed to maintaining robust privacy protection for its users. Our Privacy Policy is designed to help you understand how we collect, use and safeguard the information you provide to us and to assist you in making informed decisions when using our service.</li>
                    <li>We have established this Privacy Policy so that you can understand the care with which we intend to treat your Personal Information. Personal Information means any information that may be used to identify an individual, including, but not limited to, a first and last name, an email address or other contact information, whether at work or at home.</li>
                    <li>In general, you can visit our Web pages without telling us who you are or revealing any Personal Information about yourself. We strive to comply with all applicable laws around the globe that are designed to protect your privacy.</li>
                </ul>
            </div> 
        </div>
        <div class="row m-b-15">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4 class="text-info text-uppercase">TYPES OF DATA WE COLLECT</h4>
                <h5><strong>Personal Information</strong></h5>
                <ul>
                    <li> Your personal information is used exclusively for engaging in a relationship between you, <span class="text-info">{{$company_name}}</span> and/or our designated affiliates and partners. It is never sold or made available to outside organizations or individuals for purposes other than those explicitly stated in this <span class="text-info">{{$company_name}}</span> Privacy Policy document.</li> 
                </ul>
                <h5><strong>Website Cookies</strong></h5>
                <ul>
                    <li> <b>Cookies.</b> A cookie is a small data file that can be placed on your hard drive when you visit certain websites. Cookies are used to collect and store information, such as the type of computer you are using and how often you log on to a site.</li>
                    <li> Our Site uses "session cookies," to improve your navigation across the Site and to remember you over the course of a single visit.</li>
                    <li> You can always delete or disable cookies through your browser. If you disable cookies, you may not be able to sign in or take advantage of certain features of the Site that require cookies.</li>
                </ul>
                
            </div>         
        </div>
        
        <div class="row m-b-15">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4 class="text-info text-uppercase">WHEN AND HOW WE SHARE PERSONAL DATA WITH OTHERS</h4>
                <ul>
                    <li> Except described in this Privacy notice, <span class="text-info">{{$company_name}}</span> will not share your personal data with third parties for their direct marketing purpose.</li>
                    <li> <strong>Service Providers:</strong> We have Service Providers that perform functions on our behalf in order to fulfil our customers' needs, including Housing, Medical Insurance, SIM cards, Tax Services… These Service Providers may have access to your personal data if necessary or useful to perform of their functions. If obtaining such access, these parties will be subject to confidentiality and security obligations with respect to such data.</li>
                </ul> 
            </div>         
        </div>
        
        <div class="row m-b-15">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4 class="text-info text-uppercase">HOW WE PROTECT YOUR DATA</h4>
                <p>{{$company_name}} is committed to keeping your personal data safe and secure</p>
                <ul>
                    <li> We understand that the legal framework for protecting your privacy is important to both of us, and we assure you we comply with applicable data protection laws and regulations.</li>
                    <li> Wherever your Personal Information may be held within our company or on its behalf, we intend to take reasonable and appropriate steps to protect the Personal Information that you share with us from unauthorized access or disclosure.</li>
                    <li> When you register for a program through <span class="text-info">{{$company_name}}</span>, your data is stored in Odyssey-owned online and offline database and file systems in the USA.</li>
                </ul> 
            </div>         
        </div>
        
        <div class="row m-b-15">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4 class="text-info text-uppercase">CONSENT</h4>
                <ul>
                    <li> By using this Web site, you consent to the terms of our Privacy Policy and to our processing of Personal Information for the purposes as well as those explained where we collect Personal Information on the Web.</li>
                </ul> 
            </div>         
        </div>
        
        <div class="row m-b-15">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4 class="text-info text-uppercase">NOTIFICATION OF CHANGES</h4>
                <ul>
                    <li> Any changes to this Privacy Notice will be posted on this Site. Any information that we collect from you will be used in accordance with the version of this Privacy Notice in effect at the time that the information was collected, unless we receive your consent to change our practices with respect to previously collected information.</li>
                    <li> For further information or comments on our Privacy Notice, please contact our data privacy officer at <a href="mailto:{{$company_email}}">{{$company_email}}</a> or contact <span class="text-info">{{$company_name}}</span> at the following address:<br/>
                        <strong>{{$company_name}}</strong>
                        <p class="font-normal mbottom20">
                            6300 Wilshire Blvd. Suite 610<br>
                            Los Angeles, CA 90048<br>
                        </p>
                        <p>UPDATED: 05/2018</p></li>
                </ul> 
            </div>         
        </div>
    </div> 
@endsection
