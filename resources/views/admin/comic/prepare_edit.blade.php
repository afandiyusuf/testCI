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
  <h1>{{$comics['title']}}</h1>
@endsection

@section('main_container')
<div id="cover" class="col-md-4 center" style="display: table; margin: auto">
  <img src="{{$comics['thumb_url']}}" class="col-md-12" />
</div>
<div class="clearfix"></div>
<!-- EDIT MODAL -->
<div id="col-md-12" >
  <div class="col-md-12">

  <!-- Modal content-->
  <div class="col-md-12">
    <div class="modal-header">
      <h3>Edit Komik</h3>
    </div>
    <div class="modal-body">
       {!! Form::open(array('url'=>'admin/comic/'.$comics->id.'/edit','method'=>'POST','class'=>'col-md-12','files'=>true)) !!}
      {{ csrf_field() }}
      <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" name="title" id="title" value="{{$comics['title']}}">
        <div class="form-group">
          <label for="author">Name (Author):</label>
          <select class="form-control" id="author" name="old_author" onchange="checkAuthor()">
          @foreach ($authors as $author)
            @if($author->id == $comics['author']['id'])
              <option selected="selected" value = "{{$author->id}}">{{$author->name}}</option>
            @else
              <option value="{{$author->id}}"> {{$author->name}} </option>
            @endif
          @endforeach
           @if ($comics['author']['id'] == 0)
              <option selected="selected" value = "0"></option>
            @endif
          </select>
        </div>
        <div class="form-group" id="new-author" style="display:none">
          <label for="author-new">New Name (Author):</label>
          <input type="text" class="form-control" id="author-new" name="name" value="">
        </div>
        <div class="form-group">
          <label for="genre">Category</label>
          <select class="form-control" id="category" onchange="checkGenre()" name="old_category">
          @foreach ($categories as $category)
            @if ($category->id == $comics['category']['id'])
            <option selected="selected" value = "{{$category->id}}">{{$category->category}}</option>
            @else
            <option value = "{{$category->id}}">{{$category->category}}</option>
            @endif

            
          @endforeach
          @if ($comics['category']['id'] == 0)
              <option selected="selected" value = "0"></option>
            @endif
          </select>
        </div>
        <div class="form-group" id="new-category" style="display:none">
          <label for="category-new">New Genre:</label>
          <input type="text" class="form-control" id="category-new" name="category" value="">
        </div>
        <div class="form-group">
          <label for="description">Description</label>
          <input type="text" class="form-control" id="description" name="description" value="{{$comics['description']}}">
        </div>
        <div class="form-group">
          <label for="cover">Cover Image</label>
          <input type="file" class="form-control" id="image" name="image">
        </div>
        <div class="alert alert-danger">
            Biarkan file cover kosong jika ingin memakai cover lama (tidak mengedit cover)
              @if (count($errors) > 0)
               <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
             @endif
        </div>
      {!! Form::submit('Update', array('class'=>'btn btn-primary send-btn')) !!}
      {!! Form::close() !!}
      <div class="clearfix"></div>
      <hr>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
    </div>
  </div>
</div>




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
  $(function(){
    if($("#category").val() == "new")
    {
      $("#new-category").css('display','inherit');
    }else{
      $("#new-category").css('display','none');
    }

     if($("#author").val() == "new")
    {
      $("#new-author").css('display','inherit');
    }else{
      $("#new-author").css('display','none');
    }

  });
  function checkGenre()
  {
    if($("#category").val() == "new")
    {
      $("#new-category").css('display','inherit');
    }else{
      $("#new-category").css('display','none');
    }
  }

  function checkAuthor()
  {
    if($("#author").val() == "new")
    {
      $("#new-author").css('display','inherit');
    }else{
      $("#new-author").css('display','none');
    }
  }
</script>
@endpush