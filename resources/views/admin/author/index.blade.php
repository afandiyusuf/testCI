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
  <h1>Manage Author</h1>
@endsection

@section('main_container')

    <!-- page content -->
        @if(count($author)>0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Author Name</th>
                        <th> Action </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($author as $a)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$a['name']}}</td>
                        <td>
                            <a href="{{url('admin/author/'.$a->id.'/delete')}}" class="btn btn-danger">Delete</a>
                            <a href="{{url('admin/author/'.$a->id.'/edit')}}" class="btn btn-warning">Edit</a>
                            <a href="{{url('admin/comic/search?keyword='.$a['name'])}}" class="btn btn-primary">Search Komik</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        <div class="clearfix"></div>
        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#authorModal">Craete Author</button>
        @if (count($errors) > 0)
        <div class="alert alert-danger">
               <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <!-- Modal -->
<div id="authorModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create Author</h4>
      </div>
      <div class="modal-body">
        {!! Form::open(array('url'=>'admin/author/create','method'=>'POST')) !!}
          {{ csrf_field() }}
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>
             <div class="form-group">
                
                <input type="submit" class="form-control btn btn-primary" value="Submit">
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

    <!-- /page content -->

    <!-- footer content -->
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