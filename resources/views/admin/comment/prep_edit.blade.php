@extends('layouts.blank')

@push('stylesheets')
</style>
    <!-- Example -->
@endpush

@section('title')
@endsection

@section('main_container')
{!! Form::open(array('url'=>'admin/comment/edit/'.$id,'method'=>'GET','class'=>'col-md-10')) !!}
    {{ csrf_field() }}
    <div class="form-group">
        <label for="name">Nama:</label>
        <input type="text" class="form-control" id="name" name="name" value="{{$name}}" readonly>
    </div>
	<div class="input-group col-md-12">
		<textarea class="form-control custom-control" rows="4" name="comment" style="resize:none">{{$comment}}</textarea>     
	</div>
     <div class="form-group">
        <input type="submit" class="form-control btn btn-primary" id="submit" name="submit" value="update">
    </div>
{!! Form::close() !!}
@endsection

@section('footer')
        <div class="pull-right">
            Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
        </div>
        <div class="clearfix"></div>
@endsection