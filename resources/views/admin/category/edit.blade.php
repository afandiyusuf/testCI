@extends('layouts.blank')

@push('stylesheets')
<style type="text/css">
  .add-button{
    margin-top: 20px; 
  }

</style>
    <!-- Example -->
@endpush

@section('title')
  <h1>Edit Category</h1>
@endsection

@section('main_container')
    {!! Form::open(array('url'=>'admin/category/'.$category->id.'/update','method'=>'POST','class'=>'col-md-10')) !!}
    {{ csrf_field() }}
    <div class="form-group">
        <label for="category">Category:</label>
        <input type="text" class="form-control" id="category" name="category" value="{{$category->category}}">
    </div>
     <div class="form-group">
        <input type="submit" class="form-control btn btn-primary" id="submit" name="submit" value="update">
    </div>
    {!! Form::close() !!}
    <div class="clearfix"></div> 
    @if (count($errors) > 0)
    <div class="alert alert-danger col-md-10">
           <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

@endsection

@section('footer')
        <div class="pull-right">
            Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
        </div>
        <div class="clearfix"></div>
@endsection

@push('scripts')
    <script src="{{ asset("/js/main.js")}}"></script>
@endpush