@extends('layouts.blank')

@push('stylesheets')
</style>
    <!-- Example -->
@endpush

@section('title')
<h1 class="col-md-12">All Comments</h1>

@if(isset($filter))
	@if($filter == "all")
	<a class="text-muted">
	@else
	<a  href="{{url('admin/comment/get?filter=all')}}" class="text-primary">
	@endif
@else
	<a  class="text-muted">
@endif
All({{$total_all}}) 
</a> | 

@if(isset($filter))
	@if($filter == "published")
	<a class="text-muted">
	@else
	<a  href="{{url('admin/comment/get?filter=published')}}" class="text-primary">
	@endif
@else
	<a  href="{{url('admin/comment/get?filter=published')}}" class="text-primary">
@endif
Published({{$total_published}}) 
</a> |

@if(isset($filter))
	@if($filter == "deleted")
	<a class="text-muted">
	@else
	<a  href="{{url('admin/comment/get?filter=deleted')}}" class="text-primary">
	@endif
@else
	<a  href="{{url('admin/comment/get?filter=deleted')}}" class="text-primary">
@endif
Deleted({{$total_deleted}}) 
</a>
@endsection

@section('main_container')
		<table class="col-md-12 table table-striped"">
			<thead>
				<th>
				@if($srt_name != "null")
					@if($srt_name == "asc")
						<a href={{url('admin/comment/get?filter='.$filter.'&srt_name=desc')}}> Penulis<div class="glyphicon glyphicon-chevron-down"></div> </a>
					@else
						<a href={{url('admin/comment/get?filter='.$filter.'&srt_name=asc')}}> Penulis<div class="glyphicon glyphicon-chevron-up"></div></a>
					@endif
				@else
					<a href={{url('admin/comment/get?filter='.$filter.'&srt_name=desc')}}> Penulis </a>
				@endif
				</th>

				<th>
				@if($srt_comment != "null")
					@if($srt_comment == "desc")
						<a href={{url('admin/comment/get?filter='.$filter.'&srt_comment=asc')}}> Komentar<div class="glyphicon glyphicon-chevron-down"></div> </a>
					@else
						<a href={{url('admin/comment/get?filter='.$filter.'&srt_comment=desc')}}> Komentar<div class="glyphicon glyphicon-chevron-up"></div></a>
					@endif
				@else
					<a href={{url('admin/comment/get?filter='.$filter.'&srt_comment=desc')}}> Komentar </a>
				@endif
				</th>
				<th>
				@if($srt_judul != "null")
					@if($srt_judul == "desc")
						<a href={{url('admin/comment/get?filter='.$filter.'&srt_judul=asc')}}> Judul Komik<div class="glyphicon glyphicon-chevron-down"></div> </a>
					@else
						<a href={{url('admin/comment/get?filter='.$filter.'&srt_judul=desc')}}> Judul Komik<div class="glyphicon glyphicon-chevron-up"></div></a>
					@endif
				@else
					<a href={{url('admin/comment/get?filter='.$filter.'&srt_judul=desc')}}> Judul Komik </a>
				@endif
				</th>
				<th>
				@if($srt_tanggal != "null")
					@if($srt_tanggal == "desc")
						<a href={{url('admin/comment/get?filter='.$filter.'&srt_tanggal=asc')}}> Tanggal dikirim<div class="glyphicon glyphicon-chevron-down"></div> </a>
					@else
						<a href={{url('admin/comment/get?filter='.$filter.'&srt_tanggal=desc')}}> Tanggal dikirim<div class="glyphicon glyphicon-chevron-up"></div></a>
					@endif
				@else
					<a href={{url('admin/comment/get?filter='.$filter.'&srt_tanggal=desc')}}> Tanggal dikirim</a>
				@endif
				@if(isset($srt_date))
					@if($srt_date == "desc")
						<div class="glyphicon glyphicon-chevron-down"></div>
					@else
						<div class="glyphicon glyphicon-chevron-down"></div>
					@endif
				@endif
				</th>
			</thead>
			<tbody>
				@foreach($data as $comment)
				<tr>
					<td>{{$comment->username}}</td>
					<td>
						@if ($comment->id_parent != null)
						<div class="col-md-12">
							<strong>Balasan dari: <a href="{{url('admin/comment/get?single_id='.$comment->id_parent)}}" class="text-warning">{{$comment->parent_username}}</a> </strong>
						</div>
						<hr>
						 
						@endif
						<div class="col-md-12">
						{{$comment->comment}}
						@if($comment->is_deleted)
							<br>
							<strong class="text-danger">Comment ini telah dihapus!</strong>
						@endif
						</div>
						<div class="clearfix"></div>
						<hr>
						<div class="col-md-12">
						@if(Auth::user()->can('edit-comment') && $comment->is_deleted == 0)
						 	<a href="{{url('admin/comment/prepare_edit/'.$comment->id)}}" class="text-primary">edit</a> |
						@endif

						@if(Auth::user()->can('publish-comment'))
							@if($comment->is_deleted == 0)
							<a href="{{url('admin/comment/delete/'.$comment->id)}}" class="text-danger">Delete</a>
							@endif
						@endif
						
						@if(Auth::user()->can('unpublish-comment'))
							@if($comment->is_deleted == 1)
							<a href="{{url('admin/comment/restore/'.$comment->id)}}" class="text-warning">Restore</a>
							@endif
						@endif
						</div>
					</td>
					<td><a href="{{url('admin/comic/'.$comment->comic_id.'/page')}}">{{$comment->comic_title}}</a></td>
					<td>{{$comment->created_at}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@if($single_id == "null")
		<div class="col-md-10">
		<ul class="pagination">
			@for ($i=0;$i<$total_page;$i++)
				@if($i+1 == $current_page)
				<li class="active"><a>{{$i+1}}</a></li>
				@else
				<li><a href="{{url('admin/comment/get/'.($i+1).'/'.$total_show.'?filter='.$filter.'&srt_name='.$srt_name.'&srt_comment='.$srt_comment.'&srt_judul='.$srt_judul.'&srt_tanggal='.$srt_tanggal)}}">{{$i+1}}</a></li>
				@endif
			@endfor
		</ul>
		</div>
		@endif
@endsection

@section('footer')
        <div class="pull-right">
            Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
        </div>
        <div class="clearfix"></div>
@endsection