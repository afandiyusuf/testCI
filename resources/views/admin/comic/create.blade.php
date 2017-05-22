@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('title')
  <h1>Create New Comic</h1>

@endsection

@section('main_container')
  {!! Form::open(array('url'=>'admin/comic/insert','method'=>'POST', 'files'=>true)) !!}
  {{ csrf_field() }}
  <div class="form-group" >
    <label for="title">Title:</label>
    <input type="text" class="form-control" name="title" id="title" value={{ old('title') }}>
  </div>

  <div class="form-group">
    <label for="author">Name (Author):</label>
    <select class="form-control" id="author" name="old_author" onchange="checkAuthor()">
    @foreach ($authors as $author)
        <option value="{{$author->id}}"> {{$author->name}} </option>
    @endforeach
    </select>
  </div>
  <div class="form-group">
    <label for="genre">Genre</label>
     <select class="form-control" id="category" onchange="checkGenre()" name="old_category">
    @foreach ($categories as $category)
        <option value = "{{$category->id}}">{{$category->category}}</option>
    @endforeach
         
    </select>
  </div>
  
  <div class="form-group">
    <label for="description">Description</label>
     <input type="text" class="form-control" id="description" name="description" value="">
  </div>
  <div class="form-group">
    <label for="cover">Cover Image</label>
    <input type="file" class="form-control" id="image" name="image">
  </div>
  {!! Form::submit('Submit', array('class'=>'send-btn')) !!}
  {!! Form::close() !!}

  <br>
  @if (count($errors) > 0)
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@endsection


@section('footer')
<!-- footer content -->
<footer>
    <div class="pull-right">
        Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
    </div>
    <div class="clearfix"></div>
</footer>
@endsection

@push('scripts')
<script>
 
</script>
@endpush


