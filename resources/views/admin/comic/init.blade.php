@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('title')
  <h1>Create Comic</h1>
@endsection

@section('main_container')
  <form>
    <a href="/admin/comic/create" class="btn btn-default">Create New Comic</a>
    <a href="/admin/comic/search" class="btn btn-default">Edit And Search Comic</a>
  </form>
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


