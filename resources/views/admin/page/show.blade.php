@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('title')
  <h1>Create Panel</h1> <a href="{{url('admin/comic/'.$comic['id'].'/page')}}" class="btn btn-primary"> Kembali ke halaman komik</a>
  Page : {{$page_num}}/{{$total_page}}
@endsection

@section('main_container_no_margin')
<div class="col-md-8" style="margin-left: 0px; padding-left: 0px">
  <div class="col-md-12" id="game" style="margin-left: 0px; padding-left: 0px">
  </div>
</div>
<div class="col-md-4">
  <p>
  <strong>
    CTRL + Scroll UP untuk zoom in<br>
    CTRL + Scroll Down untuk zoom out <br>
    CTRL + 0 Untuk reset zoom level
  </strong>
  </p>
</div>
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
<script type="text/javascript" src="{{asset('vendor/bower_components/phaser/build/phaser.js')}}"></script>
<script type="text/javascript">
  //var image_
  var prev_id = {{$prev_id}};
  var next_id = {{$next_id}};
  var page_id = {{$page['id']}};
  
  var btn_back = "{{url('assets/btn_admin_back.png')}}";
  var btn_next = "{{url('assets/btn_admin_next.png')}}";
  var btn_reset = "{{url('assets/btn_admin_reset.png')}}";
  var btn_save = "{{url('assets/btn_admin_save.png')}}";
  
  var history_api_url = "{{url('api/comic/'.$comic['id'].'/page/'.$page['id'].'/get_panel')}}";
  var $next = {{$next_id}};
  @if($next_id != "null")
    var next_url = "{{url('admin/comic/'.$comic['id'].'/page/'.$next_id)}}"
  @else
    var next_url = "{{url('admin/comic/'.$comic['id'].'/page')}}"
  @endif

   @if($prev_id != "null")
    var prev_url = "{{url('admin/comic/'.$comic['id'].'/page/'.$prev_id)}}"
  @else
    var prev_url = "{{url('admin/comic/'.$comic['id'].'/page')}}"
  @endif


  var image_url = "{{$page->image_name}}";
  var add_panel_api = "{{url('api/comic/'.$comic['id'].'/page/'.$page['id'].'/add')}}"
  var id_page = {{$page->id}};
  var base_image_url = "{{url('assets/10x10.png')}}";
  var csrfToken = "{{csrf_token()}}";
</script>
<script type="text/javascript" src="{{asset('js/panel_editor/editState.js')}}"></script>
<script type="text/javascript" src="{{asset('js/panel_editor/showState.js')}}"></script>
<script type="text/javascript" src="{{asset('js/panel_editor/main.js')}}"></script>

@endpush


