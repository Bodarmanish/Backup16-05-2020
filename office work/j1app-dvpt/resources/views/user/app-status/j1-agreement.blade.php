@if($action == "j1_agreement_pdf_content")
    <span>{{ $data }}</span>
@elseif($action == "j1_pdf_template")
    <span>{!! $html_content !!}</span>
    <span>{{ $contact }}</span>
    <span>{{ $office_address }}</span>
@else
    <div class="row">
        <div class="col-sm-12">
            <h3>J1 Agreement</h3>
            <p>You will need to sign the J1 Agreement in order to continue our process. </p>
            @if($is_step_success == 1)
                <div class="alert alert-success p-25">
                    Your J1 Agreement signed successfully.
                </div>
                @if(!empty($next_step_key))
                    <button type="button" class="btn btn-info" onclick="loadStepContent('{{ $next_step_key }}')">Next Step</button>
                @endif
            @else
                @if($is_step_locked != 1)
                <button type="button" class="btn btn-sm btn-info m-b-10" onclick="show_popup('j1-agreement-modal');">Sign J1 Agreement</button> 
                @endif
                <div class="j1_agreement_success hide">
                    <div class="alert alert-warning p-25"> 
                        <p>You will be redirected now to the next step: Registration Fee.</p>
                        <p>Hold tight...</p> 
                    </div>
                </div>
            @endif
            <div id="notify_1_6"></div>
        </div> 
    </div>
    <div id="j1-agreement-modal" class="modal modal-effect fade" style="display: none;" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-white">Sign J1 Agreement</h4>
                </div>
                <div class="modal-body p-25" id="agreement_body" style="height: 500px !important;">
                    <p><strong>Introduction</strong><br/> 
                    These Website Standard Terms and Conditions written on this webpage shall manage your use of this website. These Terms will be applied fully and affect to your use of this Website. By using this Website, you agreed to accept all terms and conditions written in here. You must not use this Website if you disagree with any of these Website Standard Terms and Conditions.</p>
                    <p>Minors or people below 18 years old are not allowed to use this Website.</p>
                    <p><strong>Intellectual Property Rights</strong><br/>               
                    Other than the content you own, under these Terms, J1 and/or its licensors own all the intellectual property rights and materials contained in this Website.</p>
                    <p>You are granted limited license only for purposes of viewing the material contained on this Website.</p>
                    <p><strong>Restrictions</strong><br/>
                    You are specifically restricted from all of the following</p>
                    <p>publishing any Website material in any other media;<br/>
                    selling, sublicensing and/or otherwise commercializing any Website material;<br/>
                    publicly performing and/or showing any Website material;<br/>
                    using this Website in any way that is or may be damaging to this Website;<br/>
                    using this Website in any way that impacts user access to this Website;<br/>
                    using this Website contrary to applicable laws and regulations, or in any way may cause harm to the Website, or to any person or business entity;<br/>
                    engaging in any data mining, data harvesting, data extracting or any other similar activity in relation to this Website;<br/>          using this Website to engage in any advertising or marketing.<br/>
                    Certain areas of this Website are restricted from being access by you and J1 may further restrict access by you to any areas of this Website, at any time, in absolute discretion. Any user ID and password you may have for this Website are confidential and you must maintain confidentiality as well.</p>
                    <p><strong>Your Content</strong><br/>
                    In these Website Standard Terms and Conditions, "Your Content" shall mean any audio, video text, images or other material you choose to display on this Website. By displaying Your Content, you grant J1 a non-exclusive, worldwide irrevocable, sub licensable license to use, reproduce, adapt, publish, translate and distribute it in any and all media.</p>
                    <p>Your Content must be your own and must not be invading any third-party's rights. J1 reserves the right to remove any of Your Content from this Website at any time without notice.</p>
                    <p><strong>No warranties</strong><br>
                    This Website is provided "as is," with all faults, and J1 express no representations or warranties, of any kind related to this Website or the materials contained on this Website. Also, nothing contained on this Website shall be interpreted as advising you.</p> 
                    <p><strong>Limitation of liability</strong><br> 
                    In no event shall J1, nor any of its officers, directors and employees, shall be held liable for anything arising out of or in any way connected with your use of this Website whether such liability is under contract.  J1, including its officers, directors and employees shall not be held liable for any indirect, consequential or special liability arising out of or in any way related to your use of this Website.</p> 
                    <p><strong>Indemnification</strong><br> 
                    You hereby indemnify to the fullest extent J1 from and against any and/or all liabilities, costs, demands, causes of action, damages and expenses arising in any way related to your breach of any of the provisions of these Terms.</p>
                    <p><strong>Severability</strong><br>  
                    If any provision of these Terms is found to be invalid under any applicable law, such provisions shall be deleted without affecting the remaining provisions herein.</p> 
                    <p><strong>Variation of Terms </strong><br> 
                    J1 is permitted to revise these Terms at any time as it sees fit, and by using this Website you are expected to review these Terms on a regular basis.</p> 
                    <p><strong>Assignment</strong> <br>
                    The J1 is allowed to assign, transfer, and subcontract its rights and/or obligations under these Terms without any notification. However, you are not allowed to assign, transfer, or subcontract any of your rights and/or obligations under these Terms. </p> 
                    <p><strong>Entire Agreement</strong><br> 
                    These Terms constitute the entire agreement between J1 and you in relation to your use of this Website, and supersede all prior agreements and understandings.</p>
                    <p><strong>Governing Law & Jurisdiction</strong><br>  
                    These Terms will be governed by and interpreted in accordance with the laws of the State of CA, and you submit to the non-exclusive jurisdiction of the state and federal courts located in CA for the resolution of any disputes.</p>
                    <p>These terms and conditions have been generated at termsandcondiitionssample.com.</p>  
                    <br>
                    <div class="modal-footer text-left j1-agreement-footer"> 
                        <input type="button" name="j1_agreement_agree" id="j1_agreement_agree" class="btn btn-info btn-sm text-left" value="I Agree" onclick="return j1Agree();" /> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var url = "{{ route('j1agree') }}";
        
        $(document).ready(function(){
            
        });

        function j1Agree(){
            
            show_inner_loader('.modal-content');
            $("#j1_agreement_agree").attr("disabled",true);
            
            $.ajax({
                url: url,
                type: 'post', 
                dataType: 'json', 
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: { terms_agreed: 1 },
                success: function(response){
                    hide_popup('j1-agreement-modal');
                    if(response.type == "success")
                    {
                        $("#j1_agreement_agree").attr("disabled",false);
                        $('.j1_agreement_success').removeClass('hide');
                        setTimeout(function(){
                            navigateStages('1');
                        }, 3000);
                    }
                    else 
                    {
                        notifyResponse("#notify_1_6",response.message,response.type);
                    }
                    hide_inner_loader('.modal-content');
                },
            });  
        }
    </script>
    
@endif