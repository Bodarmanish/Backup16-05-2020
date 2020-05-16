@if($action == "eligibility_test")
    <label class="col-sm-12">{!! $question !!}</label>
    <div class="col-sm-12">
        @foreach($options as $key => $option)
            <div class="radio radio-info">
                <input type="radio" name="eligibility_answer" id="radio{{ $key }}" value="{{ $option['answer'] }}">
                <label for="radio{{ $key }}">{!! $option['label'] !!}</label>
            </div>
        @endforeach
    </div>
    <div class="col-sm-12">
        <button type="button" class="btn btn-info m-t-10" onclick="submitQuestAnswer();">Next</button>
    </div>
    <div class="col-sm-12">
        {!! $desc !!}
    </div>
@else
    @php
        $step_status = @$step_verified_data['step_status'];
        $portfolio = @$portfolio;
        $userGeneral = @$userGeneral;
    @endphp
    <div class="row">
        <div class="col-sm-12">
            <h3>Determine your eligibility</h3>
        </div>
        @if($step_status == 1)
            <div class="col-sm-12">
                <form name="eligibility_form" id="eligibility_form" action="">
                    <div class="quest"></div>
                </form>
            </div>
        @elseif($step_status == 2)
            <div class="col-sm-12">
                @if(!empty($userGeneral->eligibility_test_output))
                <label class="col-sm-12">You have successfully passed the eligibility test. 
                    @if($current_step <= 8)
                    <a class="cpointer" onclick="retakeEligibilityTest();">Retake?</a>
                    @endif
                </label>
                @else
                <div class="col-sm-12">
                    <p>Eligibility test not available</p>
                </div>
                @endif

                @if(!empty($next_step_key))
                    <button type="button" class="btn btn-info" onclick="loadStepContent('{{ $next_step_key }}')">Next Step</button>
                @endif
            </div>
        @else
            <div class="col-sm-12">
                <p>Eligibility test not available</p>
            </div>
        @endif
    </div>
    <script type="text/javascript">

    $(document).ready(function(){

        eligibilityQuest();

    });

    function eligibilityQuest(answer) {

        show_inner_loader(".timeline_stp_desc","#all_tab_data");

        var questAnswer = "";

        if(answer != "" && answer != "undefined" && answer != null)
        {
            questAnswer = answer;
        }

        var url = "{{ route('eligibilityquest') }}";

        var data = { eligibility_answer: questAnswer }

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                console.log(response);
                if(response.type == "success")
                {
                    var data = response.data;
                    if(typeof data === "object" &&  Object.keys(data).length != 0)
                    {
                        if(data.html_data != "" && data.html_data != "undefined" && data.html_data != null) {
                            $("#eligibility_form .quest").html(data.html_data);
                            $('#eligibility_form :button').prop('disabled', true);
                            $('#eligibility_form :radio').click(function() {
                                $('#eligibility_form :button').prop('disabled', false);
                            });
                        }
                        else if(data.result == "success") {
                            eligibilityComplete(data.desc,function(){ 
                                $('#stp_1 .pie_progress').asPieProgress('go', '100%');
                                $('#stp_1 .pie_progress .pie_progress__content').html('<i class="fa fa-check text-success"></i>');
                                navigateStages(1);
                            });
                        }
                        else if(data.result == "error") {
                            eligibilityNoComplete(data.desc,function()
                            {
                                eligibilityQuest('0');
                            });
                        }
                    }
                }
                hide_inner_loader('.timeline_stp_desc',"#all_tab_data");
            },

        });
        return;
    }

    function submitQuestAnswer(){
        var questAnswer;
        questAnswer = $('#eligibility_form input[name=eligibility_answer]:checked').val();
        eligibilityQuest(questAnswer);
    }

    function retakeEligibilityTest(){
        swal({
                title: "Alert",
                text: "Your previous eligibility result will be erased,<br> Are you sure you want to retake the test?",
                type: "warning",   
                showCancelButton: true,
                confirmButtonColor: "#1faae6",
                confirmButtonText: "Confirm",
                closeOnConfirm: true,
                html:true  
            },
            function(){
                eligibilityQuest("0");
                loadStepContent('1_eligibility_test');

        });
    }
    </script>
@endif