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
  
@endsection

@section('main_container')
<div class="container col-md-12">
  <h2>Role List</h2>
  <p>List Role yang dimiliki saat ini</p>   
  <div class="clearfix"></div>         
  <table class="table table-striped col-md-8">
    <thead>
      <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Permission</th>
        @if (Auth::user()->can('delete-role'))
          <th>Assign Role</th>
        @endif
      </tr>
    </thead>
    <tbody>
      @foreach ($allRole as $role)
        @if($role->name != "developer-super-admin")
        <tr>
          <td>
            {{$role->name}}
          </td>
          <td>
            {{$role->description}}
          </td>
          <td>
            @foreach($role->permission as $permission)
              {{$permission->name}} || 
            @endforeach
          </td>
          @if (Auth::user()->can('delete-role'))
           <td>
            <a href="{{url('admin/role/'.$role->id.'/delete')}}" class="btn btn-danger">DELETE</a>
          </td>
          @endif
        </tr>
        @endif
      @endforeach
    </tbody>
</table> 
</div>
<div class="clearfix"></div>
<h1 class="col-md-12"> Create New Role </h1>
<div class="clearfix"></div>
<hr>
<div class="clearfix"></div>
{!! Form::open(array('url'=>'admin/role/insert/dummy','method'=>'GET', 'files'=>true)) !!}
{{ csrf_field() }}
  <div class="col-md-12">
    <div class="col-md-12">
      <div class="form-group">
        <label for="role">New Role:</label>
        <input type="text" class="form-control" name="role" id="role" value={{ old('role') }}>
      </div>
      <div class="form-group">
        <label for="description">Description:</label>
        <input type="text" class="form-control" name="description" id="description" value={{ old('description') }}>
      </div>
    </div>
  </div>
  <div class="col-md-12">
    @foreach($allPermission as $permission)
    @if($permission['name'] != "base-admin")
    <div class="panel panel-default col-md-12">
      <div class="panel-heading">
        {{$permission['name']}}
      </div>
      <div class="panel-body">
      @foreach($permission['data'] as $detail)
        <div class="col-md-2">
          <div class="checkbox col-md-12">
            <label><input type="checkbox" name="permission[]" value="{{$detail['id']}}">{{$detail['display_name']}}</label>
            <p>
              {{$detail['description']}}
            </p>
          </div>
        </div>
      @endforeach
      </div>
    </div>
    @endif
    @endforeach
  </div>
  <div class="col-md-12">
{!! Form::submit('Create Role', array('class'=>'send-btn')) !!}
</div>
{!! Form::close() !!}
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