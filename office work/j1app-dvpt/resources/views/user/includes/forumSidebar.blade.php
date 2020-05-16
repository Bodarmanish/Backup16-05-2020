<div class="general_right_sidebar">
    <a class="btn btn-info btn-block p-10 cpointer text-center btn-xl uppercase" href="{{ url('addtopic') }}">POST NEW TOPIC</a> 
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
            <div class="box-title"> <h3>Trending</h3></div>
            <div class="button-box btn-group-font custom-btn-group">
                @if(count($data['tag_list'])>0) 
                    <ul class="list-inline">
                        @foreach($data['tag_list'] as $tag)    
                        <li><a href="{{ url("tag/".$tag->slug) }}" class="btn btn-default btn-outline">{{ $tag->title }} <span class="label label-info">{{ $tag->topic_total }}</span></a></li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div> 
<!--    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
            <div class="box-title m-t-15">
                <ul class="list-inline">
                    <li><h3 class="no-margin">Who's online</h3></li>
                    <li><span class="label label-default bg-inverse">11</span></li>
                </ul>
            </div>
            <div class="button-box btn-group-font">
                <ul class="list-inline">
                    <li><img src="{{ asset($image_path.'users/varun.jpg') }}" alt="user-img" class="img-responsive thumbnail m-b-10" width="36"></li>
                    <li><img src="{{ asset($image_path.'users/varun.jpg') }}" alt="user-img" class="img-responsive thumbnail m-b-10" width="36"></li>
                    <li><img src="{{ asset($image_path.'users/varun.jpg') }}" alt="user-img" class="img-responsive thumbnail m-b-10" width="36"></li>
                    <li><img src="{{ asset($image_path.'users/varun.jpg') }}" alt="user-img" class="img-responsive thumbnail m-b-10" width="36"></li>
                    <li><img src="{{ asset($image_path.'users/varun.jpg') }}" alt="user-img" class="img-responsive thumbnail m-b-10" width="36"></li>
                    <li><img src="{{ asset($image_path.'users/varun.jpg') }}" alt="user-img" class="img-responsive thumbnail m-b-10" width="36"></li>
                    <li><img src="{{ asset($image_path.'users/varun.jpg') }}" alt="user-img" class="img-responsive thumbnail m-b-10" width="36"></li>
                    <li><img src="{{ asset($image_path.'users/varun.jpg') }}" alt="user-img" class="img-responsive thumbnail m-b-10" width="36"></li>
                    <li><img src="{{ asset($image_path.'users/varun.jpg') }}" alt="user-img" class="img-responsive thumbnail m-b-10" width="36"></li>
                    <li><img src="{{ asset($image_path.'users/varun.jpg') }}" alt="user-img" class="img-responsive thumbnail m-b-10" width="36"></li>
                    <li><img src="{{ asset($image_path.'users/varun.jpg') }}" alt="user-img" class="img-responsive thumbnail m-b-10" width="36"></li>
                </ul>
             </div>
         </div>
     </div>-->
</div>