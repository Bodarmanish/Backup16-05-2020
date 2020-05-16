<div class="general_right_sidebar">
    <a class="btn btn-info btn-block p-10 cpointer text-center btn-xl" href="{{ url("addtopic") }}">POST NEW TOPIC</a>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
             <div class="box-title"> <h3>Categories</h3></div>
             <div class="button-box btn-group-font custom-btn-group">
                  <div class="list-style-none subcat_block_list">
                        @if(count($data['category_list'])>0)  
                              @foreach($data['category_list'] as $cat) 
                                <div class="row btn btn-default btn-block btn-outline cdefault">
                                    <small class="pull-left"><a href="{{url('category/'.$cat->slug)}}" class="text-info">{{ $cat->title }}</a></small>
                                    <span class="label label-info pull-right">{{ $cat->topic_total }}</span>
                                </div> 
                              @endforeach 
                        @endif 
                  </div>
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