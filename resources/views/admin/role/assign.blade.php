@extends('layouts.blank')

@push('stylesheets')
@endpush

@section('title')
@endsection

@section('main_container')
{!! Form::open(array('url'=>'admin/role/assign','method'=>'GET', 'files'=>true)) !!}
{{ csrf_field() }}
<div class="form-group">
  <label for="email">Search User Email</label>
  <input type="text" class="form-control" name="email" id="email" value={{ old('email') }}>
</div>
{!! Form::submit('Search User', array('class'=>'send-btn btn btn-primary')) !!}
{!! Form::close() !!}
@if($user != "NULL")
<table class="table table-striped col-md-8">
    <thead>
      <tr>
        <th>Username</th>
        <th>Email</th>
        <th>Current Role : Description</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>{{$user->username}}</td>
        <td>{{$user->email}}</td>
        <td>
          @if($current_role == "NULL")
            normal user
          @else
          <div>
            @foreach($current_role as $role)
                <div class="col-md-7"> 
                  <strong>{{$role->name}}</strong> <br>&nbsp-{{$role->description}}<br>
                </div>
                @if(Auth::user()->can('unassign-role') && $user->email != "admin@gmail.com")
                  <a href="{{url('admin/role/'.$user->id.'/unassign/'.$role->id)}}" class="btn btn-danger col-md-4">Cabut ({{$role->name}}) Role</a>
                @endif
            @endforeach
            </div>
          @endif
        </td>
        <td>
          {!! Form::open(array('url'=>'admin/role/assign/'.$user->id,'method'=>'GET', 'class'=>'col-md-12')) !!}
          {{ csrf_field() }}
          <div class="form-group">
          <label for="sel1">Role</label>
          <select class="form-control" id="sel1" name="role">
            @foreach ($allRole as $role)
              @if($role->name != "developer-super-admin")
                <option value="{{$role->id}}">{{$role->name}}</option>
              @endif
            @endforeach
          </select>
          </div>
          {!! Form::submit('Assign Role', array('class'=>'send-btn btn btn-primary')) !!}
          {!! Form::close() !!}
        </td>
      </tr>
  </tbody>
</table>

@endif
<div class="clearfix"></div>
<hr>

<h1>
  User With Role
</h1>
<table class="table table-striped col-md-8">
    <thead>
      <tr>
        <th>Username</th>
        <th>Email</th>
        <th>Current Role</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>

      @foreach($allUserWithRole as $userWithRole)
        <tr>
          <td>
            {{$userWithRole->username}}
          </td>
          <td>
            {{$userWithRole->email}}
          </td>
          <td>
            @foreach($userWithRole->role as $role)
              <div class="col-md-12"> 
               <strong>{{$role->name}}</strong> <br>
               - {{$role->description}}
               <br>
               <br>
              </div>
            @endforeach
          </td>
          <td>
            {!! Form::open(array('url'=>'admin/role/assign','method'=>'GET', 'files'=>true)) !!}
            {{ csrf_field() }}
              <input type="hidden" class="form-control" name="email" id="email" value="{{$userWithRole->email}}">
            {!! Form::submit("Edit User's Role", array('class'=>'send-btn btn btn-primary')) !!}
            {!! Form::close() !!}
          </td>
        </tr>
      @endforeach
    </tbody>
</table>

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