@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('title')
 <h3>Create Comic</h3>
@endsection

@section('main_container')
<!-- page content -->
  @if(Session::has('success'))
  <div class="alert-box success">
    <h2>{!! Session::get('success') !!}</h2>
  </div>
    @endif
      
  <div class="secure">Upload form</div>

    {!! Form::open(array('url'=>'apply/upload','method'=>'POST', 'files'=>true)) !!}
      
    <div class="control-group">
      <div class="controls">

          {!! Form::file('image') !!}
          
        <p class="errors">{!!$errors->first('image')!!}</p>

          @if(Session::has('error'))
        <p class="errors">{!! Session::get('error') !!}</p>
          @endif
      </div>

    </div>
    <div id="success"> </div>

    {!! Form::submit('Submit', array('class'=>'send-btn')) !!}
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


