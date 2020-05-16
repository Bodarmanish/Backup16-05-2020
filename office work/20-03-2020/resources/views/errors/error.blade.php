@extends('admin.layouts.applogin')

@section('content')
<div class="error-box">
    <div class="error-body text-center">
        @if(!empty($error_code))
        <h1 class="text-warning">{{ $error_code }}</h1>
        @endif
        @if(!empty($error_message))
        <h3 class="text-uppercase">{{ $error_message }}</h3>
        @endif
        @if(!empty($redirect_url))
        <a href="{{ $redirect_url }}" class="btn btn-info btn-rounded m-b-40">Back to home</a>
        @endif
    </div>
</div>
@endsection

