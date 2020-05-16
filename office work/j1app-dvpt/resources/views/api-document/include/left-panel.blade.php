<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
    <div class="sidebar sticky-top">
        <div class="list-group mb-3">
             <span class="list-group-item"><strong>User</strong></span>
                @foreach($user as $key => $value)
                        <a class='list-group-item' href='#U_{{$key}}'>{{$value}}</a>
                @endforeach
            <span class="list-group-item"><strong>Application Status – Additional Information , Upload Resume , j1 Agreement</strong></span>
                @foreach($as as $key => $value)
                        <a class='list-group-item' href='#A_{{$key}}'>{{$value}}</a>
                @endforeach
            <span class="list-group-item"><strong>Application Status – Agency-contract , Upload-document , Visa Stage</strong></span>
                @foreach($asa as $key => $value)
                        <a class='list-group-item' href='#V_{{$key}}'>{{$value}}</a>
                @endforeach
        </div>
    </div>
</div>

        