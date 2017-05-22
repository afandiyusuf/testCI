@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <!--<link href=" <link href="{{ asset("css/myFile.min.css") }}" rel="stylesheet">" rel="stylesheet">-->
@endpush

@section('main_container')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
                <div class="page-title">
                  <div class="title_left">
                    <h3>User List<small>  Data semua user</small></h3>
                  </div>
                </div>
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                          <div class="x_title">
                            <h2>User table<small>Users</small></h2>
                            <ul class="nav navbar-right panel_toolbox">
                              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                              </li>
                              <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                  <li><a href="#">Settings 1</a>
                                  </li>
                                  <li><a href="#">Settings 2</a>
                                  </li>
                                </ul>
                              </li>
                              <li><a class="close-link"><i class="fa fa-close"></i></a>
                              </li>
                            </ul>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <p class="text-muted font-13 m-b-30">
                                Semua data user
                            </p>
                            <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                            <table id="user_table" class="table table-striped table-bordered dataTable" role="grid" aria-describedby="datatable_info">
                              <thead>
                                <td>No</td>
                                <td>Name</td>
                                <td>Comic</td>
                                <td>Comment</td>
                                <td>Created At</td>
                                <td>Is Publish</td>
                                <td>Is Deleted</td>
                                <td>Action</td>
                              </thead>
                              <tbody>
                            </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                </div> {{-- row --}}

        </div>
    </div>
    <!-- /page content -->
    <!-- footer content -->
    <footer>
        <div class="pull-right">
            Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
        </div>
        <div class="clearfix"></div>
    </footer>

    <!-- /footer content -->
@endsection

@push('scripts')
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script>
        
        $(document).ready(function() {
            $('#user_table').DataTable( {
                "processing": true,
                "serverSide": true,
                "ordering" : false,
                "columns":[
                    {"data":"no"},
                    {"data":"user"},
                    {"data":"title"},
                    {"data":"comment"},
                    {"data":"created_at"},
                    {"data":"published"},
                    {"data":"deleted"},
                    {"data":null,"defaultContent":"ACTION"}
                ],
                "ajax": "{{
                    asset("/admin/comment/get")
                }}"
            } );
        } );
    </script>
@endpush