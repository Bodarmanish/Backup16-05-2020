@extends('admin.layouts.app')

@php
$document_data = @$document_data;
$document_section_type = config('common.document_section');

if(!empty($agency_type) && $agency_type == 3)
{
    unset($document_section_type[1]);
}
@endphp

@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Document Manager</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="active">Document Requirement</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <h3 class="box-title m-b-0">Document Requirement</h3>
                        <p class="text-muted m-b-30">List of Document Requirement</p>
                    </div>
                    @if(check_route_access('dr.add.form'))
                        <div class="col-md-6 col-xs-12">
                            <div class="pull-right">
                                <a href="{{ route('dr.add.form') }}" class="btn btn-block btn-info">Add Document Requirement</a>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="white-box well">
                    <div class="row">
                        <form id="document_filter" method="post" action="{{ route('dr.search') }}">
                            {{ csrf_field() }}
                            @if($agency_id == 0) 
                                <div class="form-group col-md-4 col-xs-12">
                                    <label>Agency Name</label>
                                    <select name="agency_id" id="agency_id" class="form-control">
                                        <option value="0">-- Select Agency Name --</option>
                                        @if (!empty($agency))
                                            @foreach($agency as $value)
                                                <option value="{{$value->id}}" {{is_selected(request()->get('agency_id'),$value->id)}}>{{$value->agency_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            @endif
                            @if($agency_type != 2)
                                <div class="form-group col-md-4 col-xs-12">
                                    <label>Select Document Section</label>
                                    <select name="document_section" id="document_section" class="form-control">
                                        <option value="">-- Select Document Section --</option>
                                            @foreach($document_section_type as $key => $value)
                                                <option value="{{$key}}" {{ is_selected(request()->get('document_section'),$key) }}>{{$value}}</option>
                                             @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="form-group col-md-4 col-xs-12">
                                <label>Document Name</label>
                                <input type="text" name="document_name" id="document_name" placeholder="Document Name" value="{{request()->get('document_name')}}" class="form-control">
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                      <!--<button type="button" class="btn btn-info" onclick="sendForm();">Search</button>-->
                                      <button type="submit" class="btn btn-info">Search</button>
                                      <a type="button" class="btn btn-danger" href="{{ route('dr.list') }}">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @include('admin.includes.status')
                <div class="table-responsive">
                    <table id="document_list" class="table table-bordered m-t-10">
                        <thead>
                            <tr>
                               @if($agency_id == 0)   
                                    <th>Agency Name</th>
                               @endif
                                <th>Document Name</th>
                                <th>Document Section</th>
                                <th>Requirement Type</th>
                                <th>Visibility</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                          @if (!empty($document_data))
                            @foreach($document_data as $document)
                                @php
                                    $requirement_type = ($document->requirement_type == 1) ? "Required" : "Optional";
                                    $document_section =  (!empty($document_section_type[$document->document_section])) ? $document_section_type[$document->document_section] : "";
                                    if($document->visibility==1){
                                        $visibility = "Admin";
                                    }elseif($document->visibility==2){
                                        $visibility = "User";
                                    }elseif($document->visibility==3){
                                        $visibility = "Both";
                                    }
                                @endphp
                                <tr>
                                    @if($agency_id == 0)  <td>{{ $document->agency_name == '' ? '-': $document->agency_name }}</td>  @endif
                                    <td>{{ $document->document_type }}</td>
                                    <td>{{ $document_section }}</td>
                                    <td>{{ $requirement_type }}</td>
                                    <td>{{ $visibility }}</td>
                                    <td>
                                        @if(check_route_access('dr.edit.form'))
                                            <a href="{{ route('dr.edit.form',encrypt($document->id)) }}" data-toggle="tooltip" data-original-title="Edit" onclick="return  showLoader('#full-overlay');"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                        @endif
                                        
                                        @if(check_route_access('dr.delete'))
                                            <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Delete" onclick="return confirmDelete('{{ route('dr.delete',encrypt($document->id)) }}');" > <i class="fa fa-close text-danger m-r-10"></i> </a>
                                        @endif
                                        @if(!empty($document->document_template))
                                            <a href="{{ $document->download_template_link }}"><i class="fa fa-download"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> 
@endsection
@section('scripts')
 <script>
    $(document).ready(function() {
          $('#document_list').DataTable();
    });
 </script>
@endsection