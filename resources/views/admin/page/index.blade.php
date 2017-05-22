@extends('layouts.blank')

@push('stylesheets')
<style type="text/css">
  .add-button{
    margin-top: 20px; 
  }
.gallery_product{
    height: 300px;
  }
</style>
    <!-- Example -->
@endpush

@section('title')
  <h1>Cari komik lainnya</h1>
@endsection

@section('main_container')
  
  {!! Form::open(array('url'=>'admin/comic/search','method'=>'GET','class'=>'col-md-10')) !!}
  {{ csrf_field() }}
  {!! Form::text('keyword'); !!}
  {!! Form::submit('search', array('class'=>'send-btn')) !!}
  {!! Form::close() !!}
  
  <br>
  <hr>
  <div class="col-md-12">
    <h1>{{$comics->title}}</h1>
  </div>
  <div class="clearfix"></div>
  <hr>
  <div class="col-md-12">
   <div class="col-md-4">
    <a href="#">
        <img class="col-md-12" src="{{$comics['cover_url']}}"/>

    </a>
   </div>
   <div class="col-md-8">
    <table class="col-md-12">
       <tr>
        <td> Title</td> <td>:</td><td>{{$comics['title']}}</td>
      </tr>
      <tr>
          <td> Author</td><td>:</td><td>{{$comics['author']['name']}}</td>
      </tr>
       <tr>
        <td> Category</td><td>:</td><td>{{$comics['category']['category']}}</td>
      </tr>
       <tr>
        <td> Description</td><td>:</td><td>{{$comics['description']}}</td>
      </tr>
      <tr>
        <td> Total Page</td><td>:</td><td>{{count($comics['pages'])}}</td>
      </tr>
    </table>
    <div class="clearfix"></div>
    <hr>
    <div class="col-md-12">
      <a href="{{url('admin/comic/'.$comics['id'].'/prepare_edit')}}" class="col-md-5 btn btn-warning" type="button" class="btn btn-info">
        EDIT KOMIK
      </button>
      @if(Auth::user()->can('delete-comic'))
      <a href="{{url('admin/comic/'.$comics["id"].'/delete')}}" class="col-md-5 btn btn-danger" type="button">
        DELETE KOMIK
      </a>
      @endif
      
      @if($comics['published'] == 0)
      <a href="{{url('admin/comic/'.$comics["id"].'/publish/1')}}" class="col-md-10 btn btn-primary" type="button">
        PUBLISH
      </a>
      @else
      <a href="{{url('admin/comic/'.$comics["id"].'/publish/0')}}" class="col-md-10 btn btn-danger" type="button">
        UN PUBLISH
      </a>
      @endif
      @if (count($comics)>0)
        <button type="button" class="col-md-10 btn btn-info" data-toggle="modal" data-target="#myModal">Add new page</button>
      @endif
    </div>
   </div>

   
  <div class="clearfix">
  </div>
  <div class ="col-md-12">
  <hr>
  @foreach ($comics->pages as $page)
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 filter hdpe">
      <div class="gallery_product" style="background-image: url('{{$page->image_name}}'); background-size: contain; background-repeat: no-repeat; background-position: center;">
        <a class-="col-md-12" href="{{url('admin/comic/'.$comics['id'].'/page/'.$page['id'])}}">
        <img class="col-lg-12" style="height: 100%; width: 100%;" src="{{url('storage/blank.png')}}" class="img-responsive">
      </a>
      </div>
      <div class="col-md-12">
        <table class="col-md-12">
          <tr>
            <td>No</td><td>:</td><td>{{($page->page_num)}}</td>
          </tr>
          <tr>
            <td>Total Panel</td><td>:</td><td>{{$page->total_panel}}</td>
          </tr>
        </table>
        <div class="clearfix"></div>
        <hr>
        <a href="{{url('admin/comic/'.$comics['id'].'/page/'.$page['id'])}}" class="btn btn-primary col-md-12">EDIT PANEL</a>
        <a href={{url('admin/comic/'.$comics->id.'/page/'.$page->id.'/delete')}}  class="btn btn-danger col-md-12">DELETE PAGE</a>
        <div class="clearfix"></div>
        <hr>
      </div>
    </div>
  @endforeach
   </div>
     
  @if (count($comics)>0)
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Add New Page</h4>
    </div>
    <div class="modal-body">
      {!! Form::open(array('url'=>'admin/comic/'.$comics->id.'/add','method'=>'POST','class'=>'col-md-10','files'=>true)) !!}
      {{ csrf_field() }}
      <div class="form-group">
        <label for="cover">Cover Image</label>
        <input type="file" class="form-control" id="image" name="image">
      </div>
      <br>
      <input type="hidden" name="num" value="{{count($comics->pages)+1}}"/> 
      {!! Form::submit('Upload', array('class'=>'send-btn')) !!}
      {!! Form::close() !!}
      <div class="clearfix"></div>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
    </div>
  </div>
</div>
   @endif

<!-- EDIT MODAL -->
<div id="editModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
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
          <label for="genre">Genre</label>
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
      {!! Form::submit('Submit', array('class'=>'send-btn')) !!}
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