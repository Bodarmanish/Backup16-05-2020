@extends('admin.layouts.app')
@php
$mode = "Add";
$action = route('faq.add');


if(!empty($id)){
    $mode = "Edit";
    $action = route('faq.edit',$id);
}
@endphp
@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Admin FAQ'S</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('faq.list') }}">FAQ</a></li>
                <li class="active">{{ $mode }} FAQ</li>
            </ol>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">{{ $mode }} FAQ!</h3>
                        <!--<p class="text-muted m-b-30">Add New Admin</p>-->
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="pull-right">
                            <a href="{{ route('faq.list') }}" class="btn btn-block btn-info"><i class="fa fa-list"></i> FAQ!</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @include('admin.includes.status')
                        <form method="post" action="{{ $action }}" class="form-horizontal form-validator" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $faqs->id }}" />
                            <div class="form-group {{ $errors->has('question') ? 'has-error' : '' }}">
                                <label class="col-md-12">Question <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="question" id="question" placeholder="FAQ Question" class="form-control" required value="{{ ($mode == "Edit") ? $faqs->question : old('question') }}">
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('question')){{ $errors->first('question') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('answer') ? 'has-error' : '' }}">
                                <label class="col-md-12">Answer <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <textarea name="answer" id="answer" placeholder="FAQ answer" class="form-control" required="required" rows="4">{!! ($mode == "Edit") ? $faqs->answer : old('answer') !!}</textarea>
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('answer')){{ $errors->first('answer') }}@endif
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Status</label>
                                <div class="col-md-12">
                                    <div class="radio-list">
                                        <label><input type="radio" name="status" value="1" checked {{ is_checked($faqs->status,'1') }}> Active </label>
                                        <label><input type="radio" name="status" value="0" {{ is_checked($faqs->status,'0') }}> De-Active </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Save FAQ</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    CKEDITOR.replace( 'answer' ); 
</script>
@endsection

