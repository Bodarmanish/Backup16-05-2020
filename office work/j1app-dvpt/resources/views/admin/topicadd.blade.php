@extends('admin.layouts.app')
@php

if(!empty($slug)){
    $mode = "Edit";
    $action = route('topic.edit',$slug);
}
@endphp
@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Admin Topics</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('topic.list') }}">Topic</a></li>
                <li class="active">Edit Topic</li>
            </ol>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">{{ $mode }} Topic</h3>
                        <!--<p class="text-muted m-b-30">Add New Admin</p>-->
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="pull-right">
                            <a href="{{ route('topic.list') }}" class="btn btn-block btn-info"><i class="fa fa-list"></i> Topics</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @include('admin.includes.status')
                        <form method="post" action="{{ $action }}" class="form-horizontal form-validator">
                            {{ csrf_field() }}
                            <input type="hidden" name="{{ $topic->id }}" />
                            <div class="form-group">
                                <label class="col-md-12">Topic Name <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="title" id="title" placeholder="Topic Name" class="form-control " required value="{{$topic->title}}">
                                    <div class="clearfix"></div>
                                    <div class="help-block with-errors">
                                        @if ($errors->has('title')){{ $errors->first('title') }}@endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Description <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <textarea rows="5" name="description" id="description" class="form-control " placeholder="Topic Description">{{$topic->description}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Category <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <select class="form-control" name="sub_cat_id" id="sub_cat_id" required>
                                        <option value="">Select Forums Sub Category</option>
                                        @foreach($forum_categories as $item)
                                        <option value="{{ $item->id }}" {{ ( $topic->forum_category_id == $item->id || old('sub_cat_id') ==  $item->id) ? 'selected' : '' }}>{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Tags <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" name="tags" id="tags" placeholder="Topic Tags" class="form-control " value="" >    
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Status</label>
                                <div class="col-md-12">
                                    <div class="radio-list">
                                        <label><input type="radio" name="status" value="1" checked {{ is_checked($topic->status,'1') }}> Active </label>
                                        <label><input type="radio" name="status" value="0" {{ is_checked($topic->status,'0') }}> De-Active </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Save Topic</button>
                                    <button type="reset" class="btn btn-danger">Reset</button>
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
    src = "{{ route('searchtags') }}";
    var tag_data = <?php echo $tags_json; ?>;
    $('#tags').tokenfield({
        autocomplete: {
            source: function (request, response) {
                jQuery.get(src, {
                    query: request.term
                }, function (data) {
                    response(data);
                });
            },
            multiple: true,
            showAutocompleteOnFocus: true
            }
        }
    );
    if(tag_data.length > 0){
        $('#tags').tokenfield('setTokens', tag_data);
    }    
</script>
@endsection
