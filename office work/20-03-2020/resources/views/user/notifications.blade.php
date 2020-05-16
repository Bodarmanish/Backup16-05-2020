@extends('user.layouts.app')

@section('content')  
@php 
    $notification_data = @$data['notification_data'];
@endphp
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="{{ route('edit.profile') }}" class="text-info">Home</a></li>
            <li class="active capitalize">Notifications</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <div class="row m-b-20">
            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12 m-b-10">
                <h2 class="page-title capitalize no-margin"><b>Your Notifications</b></h2>
                <div class="spinner-border"></div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                <a class="btn btn-info p-10 cpointer text-center m-b-10 pull-right text-white" href="{{ route('edit.profile','notification') }}">Notification Settings</a>
            </div>
        </div>
        <div class="white-box font-13 notification-box">
            @if(!empty($notification_data))
                <div class="infinite-scroll">
                    @foreach($notification_data as $notification)
                         <div class="row b-b m-b-10">
                            <!-- <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
                            <img src="{{asset($image_path.'mini_odc_header_logo.png')}}" alt="logo-img" class="thumbnail thumb-sm b-none m-r-5 m-b-10">
                            </div> --> 
                            <div class="col-md-9 col-sm-12">
                                <p>{{$notification->notification_log_text}}</p>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <span class="text-muted txt-oflo font-12 txt-oflo">{{ dateformat($notification->created_at, "M d, Y h:i a") }}</span>
                            </div>
                        </div>
                    @endforeach
                    {!! $notification_data->links() !!}
                </div>
            @else
                <div class="row b-b m-b-10"> 
                    <div class="col-sm-12">
                        <p>You have not any new notification</p>
                    </div> 
                </div>
            @endif
        </div>
    </div> 
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $('ul.pagination').hide();
    $(function() {
        $('.infinite-scroll').jscroll({
            autoTrigger: true,
            loadingHtml: '<img class="center-block" src="{{url('UserInterface/assets/images/loader1.gif')}}" alt="Loading..." />',
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.infinite-scroll',
            callback: function() {
                $('ul.pagination').remove();
            }
        });
    });
</script>
@stop