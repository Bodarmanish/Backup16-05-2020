<div class="row m-t-20"> 
    <div class="col-xs-12">
        
        @if (count($errors) > 0)
        <div class="alert alert-danger m-b-10">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{!! $error !!}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success m-b-10">
                {!! session('success') !!}
            </div>
        @endif
        
        @if (session('status'))
            <div class="alert alert-success m-b-10">
                {!! session('status') !!}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger m-b-10">
                 {!! session('error') !!}
            </div>
        @endif
    </div> 
</div>  