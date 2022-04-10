@extends('admin.template')

@section('header', "Edit Hotspot User")

@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    
@endpush

@section('body')
    
<section class="content">
    <div class="container-fluid">

        
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Akun User Hotspot</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.user.update')}}" method="POST">
                                @csrf

                                <input type="hidden" name="user_id" value="{{$user->user_id}}">
                                <input type="hidden" name="platform" value="{{$platform}}">
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" name="nama" id="nama" class="form-control" disabled value="{{isset($user->nama) ? $user->nama : ''}}">
                                </div>

                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <input type="text" name="alamat" id="alamat" class="form-control" disabled value="{{isset($user->alamat) ? $user->alamat : '-'}}">
                                </div>

                                <div class="form-group">
                                    <label for="kategori">Kategori</label>
                                    <input type="text" name="kategori" id="kategori" class="form-control"  disabled disabled value="{{isset($user->kategori) ? $user->kategori : ''}}">
                                </div>


                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" id="username" class="form-control" disabled value="{{isset($user->username) ? $user->username : ''}}">
                                </div>

                                @if($platform == "organik" && $user->path != null)
                                <div class="form-group">
                                    <label for="group">User Profiles</label>
                                    <select class="custom-select form-control-border" name="group" id="group">

                                        @foreach($groups as $g)
                                        <option value="{{$g->id}}" {{$user->group_id == $g->id ? 'selected' : ''}}>{{$g->group}}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ktpModal">
                                    Lihat KTP
                                </button>

                                <!-- MODAL View KTP -->
                                <div class="modal fade" id="ktpModal" tabindex="-1" role="dialog" aria-labelledby="ktpModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ktpModalLabel">Foto KTP</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="{{asset('storage/'.$user->path)}}" style="display:block;" width="100%" height="100%">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="group">Status Identitas</label>
                                    <select class="custom-select form-control-border" name="status_validasi" id="group">
                                        <option selected value="0">Belum Valid</option>
                                        <option value="1">Valid</option>
                                        <option value="2">Tidak Valid</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                        <input type="submit" class="btn btn-primary" value="Submit">
                                </div>
                                @endif

                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-6">

                    <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Custom Rules</h3>
                    </div><!-- /.card-header -->
                    
                    <div class="card-body">

                        <form action="{{route('admin.custom.rules.add')}}" method="post">
                            @csrf
                            <div class="row">

                                <input type="hidden" name="user_id" value="{{$user->user_id}}">
                                <input type="hidden" name="platform" value="{{$platform}}">

                                <div class="col-7">
                                    <select class="custom-select form-control-border" name="attribute">
                                        <option value="quota">Quota</option>
                                    </select>

                                    <!-- <input type="text" class="form-control" placeholder="Attribute"> -->
                                </div>

                                <div class="col-3">
                                    <input type="text" class="form-control" name="value" placeholder="Value in GB">
                                </div>

                                <div class="col-2">
                                    <input type="submit" class="btn btn-primary" value="Add">
                                </div>
                               
                            </div>

                        </form>

                        <div class="row">
                            <div class="col-12">
                                <table id="datatables1" class="table table-bordered table-hover">
                                    <thead>
                                    
                                        <tr>
                                            <th>No.</th>
                                            <th>Attribute</th>
                                            <th>Value</th>
                                            <th>Action</th>
                                        </tr>

                                    </thead>

                                    <tbody>
                                    @foreach ($custom_rules as $custom)

                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$custom->attribute}}</td>
                                            <td>{{$custom->value}}{{$custom->attribute == 'quota' ? ' GB' : ''}}</td>
                                            <td><a href="{{route('admin.custom.rules.disable', [ 'user' => $platform, 'id' => $custom->id])}}" class="btn btn-danger">Disable</a></td>
                                        </tr>

                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        
                    </div><!-- /.card-body -->

                    </div><!-- /.card -->
                </div><!-- /.col-6 -->

            </div> <!-- /.row -->


                


    </div>
</section>

@endsection


@push('javascript')

<!-- DataTables  & Plugins -->
<script src="{{asset('admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('admin/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('admin/plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('admin/plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('admin/plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('admin/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('admin/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('admin/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>

<script>
  $(function () {
    $('#datatables1').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });

</script>

@endpush