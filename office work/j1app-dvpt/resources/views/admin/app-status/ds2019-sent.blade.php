@php 
    $legal_data = @$step_verified_data['legal']; 
    $class = $is_step_success == 1 ? "hide" : "";
@endphp

<h3>DS2019 Sent</h3> 
<div class="row"> 
    <div class="col-sm-12">
        @if(empty($step_status)) 
            <p>This step is disable until user will reach to step.</p>  
        @else
            <div class="panel-group" id="ds2019" aria-multiselectable="true" role="tablist"> 
                @if($is_step_success==1 && !empty($legal_data))
                <div class="panel" id="div_ds2019">
                    <div class="panel-heading" id="ds2019sent_tab" role="tab"> 
                        <a class="panel-title" data-toggle="collapse" href="#ds2019sent" data-parent="#ds2019" aria-expanded="true" aria-controls="ds2019sent">DS2019 Legal Information</a>
                    </div>
                    <div class="panel-collapse collapse {{ (!empty($legal_data))?'in':'' }}" id="ds2019sent" aria-labelledby="exampleHeadingDefaultOne" role="tabpanel">
                    <div class="panel-body">
                        <p>DS 2019 Number: <a href="javascript:void(0);">{{$legal_data->ds_number}}</a></p>
                        <p>Tracking Number: <a href="javascript:void(0);">{{$legal_data->tracking_number}}</a></p>
                        <p>DS 2019 Start Date: <a href="javascript:void(0);">{{dateformat($legal_data->ds_start_date,DISPLAY_DATE)}}</a></p>
                        <p>DS 2019 End Date: <a href="javascript:void(0);">{{dateformat($legal_data->ds_end_date,DISPLAY_DATE)}}</a></p>
                        <p>DS2019 Shipment Date: <a href="javascript:void(0);"> {{dateformat($legal_data->ds_shipment_date,DISPLAY_DATE)}}</a></p>
                        <hr/>
                        <div class="clearfix"></div>
                        <div class="text-center response"></div> 
                        <div class="clearfix"></div>
                        <a  href="javascript:void(0);" class="btn btn-info m-r-15" data-toggle="tooltip" data-original-title="Edit" onclick="return  DS2019frm('showfrm');">Resend DS2019</a>
                    </div>
                </div> 
                </div>
                @endif
                <div class="panel {{$class}}" id="div_ds2019frm">
                    <div class="panel-heading" id="legal_info_tab" role="tab"> <a class="panel-title collapsed" data-toggle="collapse" href="#legal_info" data-parent="#ds2019" aria-expanded="false" aria-controls="legal_info">DS2019</a> </div>
                    <div class="panel-collapse collapse in" id="legal_info" aria-labelledby="exampleHeadingDefaultTwo" role="tabpanel">
                        <div class="panel-body"> 
                            <form id="frm_ds2019_sent" method="post" class="form-horizontal">
                                <input type="hidden" name="action" value="ds2019_sent" />
                                <input type="hidden" name="portfolio_id" value="{{$portfolio->id}}" />
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group {{ $errors->has('ds_number') ? 'has-error' : '' }}">
                                            <label class="col-md-12">DS 2019 #<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="text" name="ds_number" placeholder="DS2019 number" class="form-control" required>
                                                <div class="clearfix"></div>
                                                <div class="help-block with-errors">
                                                    @if ($errors->has('ds_number')){{ $errors->first('ds_number') }}@endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>    
                                    <div class="col-sm-6">
                                        <div class="form-group {{ $errors->has('tracking_number') ? 'has-error' : '' }}">
                                            <label class="col-md-12">Tracking Number<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="text" name="tracking_number" placeholder="Tracking number" class="form-control" required>
                                                <div class="clearfix"></div>
                                                <div class="help-block with-errors">
                                                    @if ($errors->has('tracking_number')){{ $errors->first('tracking_number') }}@endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group {{ $errors->has('ds_start_date') ? 'has-error' : '' }}">
                                            <label class="col-md-12">DS 2019 Start Date <span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="text" name="ds_start_date" placeholder="DS2019 start date" class="form-control datepicker" required autocomplete="off">
                                                <div class="clearfix"></div>
                                                <div class="help-block with-errors">
                                                    @if ($errors->has('ds_start_date')){{ $errors->first('ds_start_date') }}@endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group {{ $errors->has('ds_end_date') ? 'has-error' : '' }}">
                                            <label class="col-md-12">DS 2019 End Date <span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="text" name="ds_end_date" placeholder="DS2019 end date" class="form-control datepicker" required autocomplete="off">
                                                <div class="clearfix"></div>
                                                <div class="help-block with-errors">
                                                    @if ($errors->has('ds_end_date')){{ $errors->first('ds_end_date') }}@endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                   <div class="col-sm-6">
                                        <div class="form-group {{ $errors->has('ds_shipment_date') ? 'has-error' : '' }}">
                                            <label class="col-md-12">DS2019 Shipment Date <span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="text" name="ds_shipment_date" placeholder="DS2019 shipment date" class="form-control datepicker" required autocomplete="off">
                                                <div class="clearfix"></div>
                                                <div class="help-block with-errors">
                                                    @if ($errors->has('ds_shipment_date')){{ $errors->first('ds_shipment_date') }}@endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button type="reset" class="btn btn-danger" onclick="DS2019frm('ds2019data')">Cancel</button>
                                                <button type="submit" class="btn btn-info">Submit</button>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
                <script type="text/javascript">
                    
                    function DS2019frm(type)
                    {
                        showLoader("#full-overlay");
                        if(type == 'showfrm')
                        {
                            confirmAlert("On confirm DS2019 sent again.","warning","Are you sure?","Confirm",function(r,i){
                                if(i)
                                {
                                    $("#div_ds2019frm").removeClass( "hide" );
                                    $("#div_ds2019").addClass( "hide" );
                                    hideLoader("#full-overlay");
                                }
                                else
                                {
                                    hideLoader("#full-overlay");
                                }
                            });
                        }
                        else{
                            $(".with-errors").empty();
                            $("#frm_ds2019_sent").find('.has-error').removeClass("has-error");
                            
                            if("{{$is_step_success}}" == 1)
                            {
                                $("#div_ds2019frm").addClass( "hide" );
                                $("#div_ds2019").removeClass( "hide" );
                            }
                           
                            hideLoader("#full-overlay");
                        }
                    }
                    
                    $(document).ready(function(){
                        var user_id = $('meta[name="user_token"]').attr('content');
                        var form_selector = "#frm_ds2019_sent";
                        
                        ajaxFormValidator(form_selector,function(ele,event){
                            event.preventDefault();
                            showLoader("#full-overlay");
                            
                            var form_data = new FormData(ele);
                                form_data.append('user_id',user_id);
                                
                            var url = "{{ route('visa.stage') }}";
                            $.ajax({
                                type: 'post',
                                url: url, 
                                data: form_data,
                                dataType: 'json',
                                processData: false,
                                contentType: false,
                                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                success: function(response) {
                                    if(response.type=='success'){
                                        var Html = '<div class="alert swl-alert-success"><ul><li>'+ response.message+ '</li></ul></div>'; 
                                        notifyResponseTimerAlert(Html,"success","Success");
                                        setTimeout(function(){
                                            navigateStages('{{ $active_stage }}',"{{$active_step_key}}");
                                        }, 3000); 
                                    }
                                    else{
                                        hideLoader("#full-overlay");
                                        var Html = '<div class="alert swl-alert-danger"><ul>'; 
                                        $.each( response.message, function( key, value ) {
                                            Html += '<li>' + value+ '</li>';  
                                        });
                                        Html += '</ul></div>';  
                                        notifyResponseTimerAlert(Html,"error","Error");
                                    } 
                                }
                            });
                        }); 
                    });

                </script> 
            </div>
        @endif
    </div>
</div> 