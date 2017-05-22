@extends('layouts.blank')

@push('stylesheets')
<style type="text/css">
  .gallery_product{
    height: 480px;
  }
  .add-button{
    margin: 20px; 
    margin-top: 15%;
  }

</style>
    <!-- Example -->
@endpush

@section('title')
  <h1>Search And Edit Comic</h1>
   Page: {{$current_page+1}}/{{$total_pagination}} <br>
   Total comic : {{$total_comic}} <br><br>
  @if($current_page != 0)
  <a href="{{url('admin/comic/search/'.$is_publish.'/'.($current_page-1).'/'.$total.'?_token='.csrf_token().'&keyword='.$keyword )}}" class="btn btn-primary">PREV</a>
  @endif
  @if($current_page+1 != $total_pagination)
  <a href="{{url('admin/comic/search/'.$is_publish.'/'.($current_page+1).'/'.$total.'?_token='.csrf_token().'&keyword='.$keyword )}}" class="btn btn-primary">NEXT</a>
  @endif 
  <a href="{{url('admin/comic/create')}}" class="btn btn-primary">create new comic</a>
@endsection

@section('main_container')
  
  {!! Form::open(array('url'=>'admin/comic/search/'.$is_publish,'method'=>'GET','class'=>'col-md-10')) !!}
  {{ csrf_field() }}
  {!! Form::text('keyword',$keyword); !!}
  {!! Form::submit('search', array('class'=>'send-btn')) !!}
  {!! Form::close() !!}
  
  <br>
  <hr>
  <div class ="col-md-10">
  @foreach ($comics as $comic)
    <div class="gallery_product col-md-4 filter hdpe">
      
      <div class="col-md-12" style="background-image: url('{{$comic['thumb_url']}}'); background-size: contain; background-repeat: no-repeat; background-position: center;">
        <a href="{{url('admin/comic/'.$comic['id'].'/page')}}">
          <img class="col-lg-12" src="{{url('storage/blank.png')}}" class="img-responsive" style="height: 200px">
        </a>
      </div>

      <div class="col-md-12">
        <hr>
        <table class="col-md-12">
          <tr>
            <td>Title</td><td>:</td><td>{{$comic['title']}}</td>
          </tr>
          <tr>
            <td>Author</td><td>:</td><td>{{$comic['name']}}</td>
          </tr>
          <tr>
            <td>Total Page</td><td>:</td><td>{{$comic['total_page']}}</td>
          </tr>
        </table>

      </div>
      <div class="clearfix"></div>
      <br>
      <div class="col-md-12">
        <a href="{{url('admin/comic/'.$comic['id'].'/page')}}" class="btn btn-primary col-md-12 btn-sm"> DETAIL</a>
        @if(Auth::user()->can('edit-comic'))
        <a  href="{{url('admin/comic/'.$comic['id'].'/prepare_edit')}}" class="btn btn-warning col-md-12 btn-sm"> EDIT KOMIK</a>
        @endif
        @if(Auth::user()->can('delete-comic'))
        <a  href="{{url('admin/comic/'.$comic['id'].'/delete')}}" class="btn btn-danger col-md-12 btn-sm"> DELETE KOMIK</a>
        @endif
      </div>
    </div>

  @endforeach

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
@endpush