<div class="card form-group mtop40" id="V_all_tab">
    <div class="card-body">
        <h3 class="card-title d-inline">Application Status – <small>Agency-contract , Upload-document , Visa Stage</small></h3>
        <a href="javascript:void(0);" class="text-blue pull-right page_top">Back to Top <img src="images/page_up.png" width="15" /></a>
        <div class="clearfix p10"></div>
        <div class="card-text">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        @foreach ($asa as $key => $value)
                                <a class='nav-item nav-link {{$loop->first ? 'active' : ''}}' id='V_{{$key}}_user_tab' data-toggle='tab' href='#V_{{$key}}' role='tab' aria-controls='nav-{{$key}}' aria-selected='true' title='{{$value}}'>{{$value}}</a>
                       @endforeach
                </div>
            </nav>

            <div class="tab-content" id="nav-tabContent">
                
                <div class="tab-pane fade show active" id="V_cra" role="tabpanel" aria-labelledby="V_cra_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">Contract Request Action</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.cra')}}" method="post" target="_blank" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >End Point:</label>
                                    <div class="col-sm-7"> 
                                        <label class="form-control" >/cra</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Contract id:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="contract" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >agency id:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="agency" class="form-control" />
                                    </div>
                                </div>
                                <input type="hidden" name="btn_action" class="form-control" value="accept" />
                                <input type="hidden" name="btn_action" class="form-control" value="2_contract_placement" />
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Authorization Token:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7"> 
                                        <input type="text" name="usertoken" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="button" value="Submit" class="btn btn-primary">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="tab-pane fade" id="V_cr" role="tabpanel" aria-labelledby="V_cr_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">Agency Contract Request</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.cr')}}" method="post" target="_blank" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >End Point:</label>
                                    <div class="col-sm-7"> 
                                        <label class="form-control" >/cr</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >agency id:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" name="agency" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Authorization Token:<span class="text-danger"> *</span></label>
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
                
                <div class="tab-pane fade" id="V_udi" role="tabpanel" aria-labelledby="V_udi_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">Upload-document-instruction</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.udi')}}" method="post" target="_blank" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-10">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >End Point:</label>
                                    <div class="col-sm-7"> 
                                        <label class="form-control" >/udi</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Document Request id:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" name="doc_req_id" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Authorization Token:<span class="text-danger"> *</span></label>
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
                
                <div class="tab-pane fade" id="V_dh" role="tabpanel" aria-labelledby="V_dh_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">Document History</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.dh')}}" method="post" target="_blank" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-10">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >End Point:</label>
                                    <div class="col-sm-7"> 
                                        <label class="form-control" >/dh</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Document Type id:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" name="doc_type" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Authorization Token:<span class="text-danger"> *</span></label>
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
                
                <div class="tab-pane fade" id="V_uploadDocument" role="tabpanel" aria-labelledby="V_uploadDocument_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">Upload Document</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.uploaddocument')}}" method="post" target="_blank" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-10">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >End Point:</label>
                                    <div class="col-sm-6"> 
                                        <label class="form-control" >/uploaddocument</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Document Type id:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" name="document_type" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Upload Document File:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="file" name="document_file" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Authorization Token:<span class="text-danger"> *</span></label>
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
                
                <div class="tab-pane fade" id="V_docUploaded" role="tabpanel" aria-labelledby="V_docUploaded_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">Document Uploaded</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.doc.uploaded')}}" method="post" target="_blank" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-10">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >End Point:</label>
                                    <div class="col-sm-6"> 
                                        <label class="form-control" >/docuploaded</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Authorization Token:<span class="text-danger"> *</span></label>
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
                
                <div class="tab-pane fade" id="V_sponsorUpdated" role="tabpanel" aria-labelledby="V_sponsorUpdated_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">Sponsor Updated</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.sponsorupdated')}}" method="post" target="_blank" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-10">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >End Point:</label>
                                    <div class="col-sm-6"> 
                                        <label class="form-control" >/sponsorupdated</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Authorization Token:<span class="text-danger"> *</span></label>
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
                
                <div class="tab-pane fade" id="V_acceptInvitation" role="tabpanel" aria-labelledby="V_acceptInvitation_user_tab">
                    <div class="form-group row">
                        <h3 class="card-title d-inline col-md-12 mtop40">Accept-invitation</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.accept.invitation')}}" method="post" target="_blank" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-10">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >End Point:</label>
                                    <div class="col-sm-6"> 
                                        <label class="form-control" >/accept-invitation</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Contract id:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" name="contract_id" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >First Name:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" name="first_name" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Last Name:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" name="last_name" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Email Address:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="email" name="email_address" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Password:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="password" name="password" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Password Confirmation:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="password" name="password_confirmation" class="form-control" />
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
                
                <div class="tab-pane fade" id="V_visaStage" role="tabpanel" aria-labelledby="V_visaStage_user_tab">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <h3 class="card-title d-inline col-md-12 mtop40">Embassy Interview</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.visa.stage')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >End Point:</label>
                                            <div class="col-sm-7"> 
                                                <label class="form-control" >/visa-stage</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >action:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" readonly name="action" value="embassy_interview" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >interview Date:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7">
                                                <input type="datetime-local" name="embassy_interview_date" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Embassy Timezone:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7">
                                                <select name="embassy_timezone" class="form-control" required="">
                                                    <option selected disabled >-- Select Timezone --</option>
                                                    @foreach($timezones as $zone)
                                                        <option value="{{ $zone->zone_id }}">{{ $zone->zone_label }}</option>
                                                    @endforeach
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Authorization Token:<span class="text-danger"> *</span></label>
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
                                <h3 class="card-title d-inline col-md-12 mtop40">Visa Outcome</h3>
                            </div>
                            <form class="form-horizontal" action="{{route('api.visa.stage')}}" method="post" target="_blank" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >End Point:</label>
                                            <div class="col-sm-7"> 
                                                <label class="form-control" >/visa-stage</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >action:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7"> 
                                                <input type="text" readonly name="action" value="visa_outcome" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Visa Status:<span class="text-danger"> *</span></label>
                                            <div class="col-sm-7">
                                                <input type="text" name="visa_status" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4" >Authorization Token:<span class="text-danger"> *</span></label>
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
                        <h3 class="card-title d-inline col-md-12 mtop40">Flight info</h3>
                    </div>
                    <form class="form-horizontal" action="{{route('api.visa.stage')}}" method="post" target="_blank" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >End Point:</label>
                                    <div class="col-sm-5"> 
                                        <label class="form-control" >/visa-stage</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >action:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-5"> 
                                        <input type="text" readonly name="action" value="flight_info" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Arrival Airport:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-5">
                                        <select class="form-control" name="arrival_airport" required="">
                                        <option value="">-- Select Airport --</option>
                                        @foreach($airports as $airport)
                                            <option value="{{ $airport->ap_abbr }}" >{{ $airport->airport_label }}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Airline:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-5">
                                        <input type="text" name="airline" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Flight:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-5">
                                        <input type="text" name="flight" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Departure Timezone:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-5">
                                        <select name="departure_timezone" class="form-control" required="">
                                            <option selected disabled >-- Select Timezone --</option>
                                            @foreach($timezones as $zone)
                                                <option value="{{ $zone->zone_id }}">{{ $zone->zone_label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Departure Date:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-5">
                                        <input type="date" name="departure_date" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Flight:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-5">
                                        <select name="arrival_timezone" class="form-control" required="">
                                            <option selected disabled >-- Select Timezone --</option>
                                            @foreach($timezones as $zone)
                                                <option value="{{ $zone->zone_id }}">{{ $zone->zone_label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Arrival Date:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-5">
                                        <input type="date" name="arrival_date" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >additional_info:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-5">
                                        <input type="text" name="additional_info" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3" >Authorization Token:<span class="text-danger"> *</span></label>
                                    <div class="col-sm-5"> 
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