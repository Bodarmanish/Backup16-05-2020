@php
    $action = $data['action'];
@endphp
@if(!empty($action))
    @if($action == "getSubCatByParentCatId")   
        <label for="sub_category_id" class="control-label">Select Sub Category<span class="text-danger">*</span></label> 
        <select id="sub_category_id" name="sub_category_id" class="form-control" data-placeholder="Choose a Sub Category" tabindex="1" onchange="return selectForumTagList(this.value)">
            <option value="">-- Select Sub Category --</option>
            @if(isset($data['subcategory']) && !empty($data['subcategory']))   
                @foreach($data['subcategory'] as $subcat)
                <option value="{{ $subcat->id }}">{{ $subcat->title }}</option>
                @endforeach
            @endif  
        </select>                      
        <div class="help-block with-errors">
            @if($errors->has('subcategory')){{ $errors->first('subcategory') }}@endif
        </div>
        <div class="form-control-feedback"></div>
    @elseif($data['action'] == "ForumTagListBySubCatId")
      <label for="forum_tag_list" class="control-label">Choose Forum Tags (You can select multiple tag using ctrl+select)</label> 
      <select id="forum_tag_list" name="forum_tag_list[]" class="form-control" data-placeholder="Choose a Forum Tag List" tabindex="1" multiple>
          <option value="" disabled selected>Choose your tag</option>
          @if(isset($data['forum_tag_list']) && !empty($data['forum_tag_list']))   
              @foreach($data['forum_tag_list'] as $tag)
                <option value="{{ $tag->tagid }}">{{ $tag->tagtitle }}</option>
              @endforeach
          @endif   
      </select>
    @elseif($data['action'] == "fvttopicmenu")
        <li id="{{$data["fuft_id"]}}"> 
            <p  class="capitalize">{{ str_limit($data["topicdetail"]->title, 18, "...") }}  {{$data['status'] == 1 ?  ' added as favorite.' : ' remove from favorite.'}} <span><a class="text-info cpointer" href="{{ route("favoritetopic")}}">See all favorite</a></span></p>
        </li>
    @elseif($data['action'] == "reportTopic") 
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title text-white">Help us to understand. Why are you reporting this?</h5>
            </div>
            <div class="modal-body">
                <ul class="search-listing last_b_none text-info" id="sa-thanks">
                    <li>
                        <div class="cpointer" onclick="submitReport('{{$data['topicId']}}',this);">This is inappropriate for J1 Community</div>
                    </li>
                    <li>
                        <div class="cpointer" onclick="submitReport('{{$data['topicId']}}',this);">It's pornographic or extremely violent</div>
                    </li>
                    <li>
                        <div class="cpointer" onclick="submitReport('{{$data['topicId']}}',this);">It's spam, a scam, or a fake account</div>
                    </li>
                    <li>
                        <div class="cpointer" onclick="submitReport('{{$data['topicId']}}',this);">This account may have been hacked</div>
                    </li>
                    <li>
                        <div class="cpointer" onclick="submitReport('{{$data['topicId']}}',this);">The topic or language is offensive</div>
                    </li>
                </ul>
            </div>
        </div>
    @elseif($data['action'] == "resend_verification")
    <div class="modal-content">
        <div class="modal-header bg-info">
            <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h5 class="modal-title text-white">reCAPTCHA</h5>
        </div>
        <div class="modal-body">
            {!! NoCaptcha::renderJs() !!}
            {!! NoCaptcha::display(['data-theme' => 'light','data-callback' => 'recaptchaCallback']) !!} 
        </div>
    </div>
        
    @endif
@endif