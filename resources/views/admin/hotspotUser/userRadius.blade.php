@extends('admin.template')

@section('header', "Hotspot User")

@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    
@endpush

@section('body')
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Hotspot User <b>RADIUS</b></h3>
              </div><!-- /.card-header -->
              
              <div class="card-body">
                <table id="datatables1" class="table table-bordered table-hover">
                    <thead>
                    
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Kategori</th>
                            <th>User Profile</th>
                            <th>Status</th>
                            <th>Valid</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                    @foreach ($users as $u)

                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$u->nama}}</td>
                            <td>{{$u->alamat}}</td>
                            <td>{{$u->kategori}}</td>
                            <td>{{$u->group}}</td>
                            <td>{{$u->isDeleted == 0 ? 'Aktif' : 'Non-Aktif'}}</td>

                            @if($u->status == 0)
                            <td>Belum Valid</td>
                            @elseif($u->status == 1)
                            <td>Valid</td>
                            @elseif($u->status == 2)
                            <td>Tidak Valid</td>
                            @endif

                            <td>
                              <a href="{{route('admin.user.edit',['user' => 'organik', 'id' => $u->id])}}" class="btn btn-primary">Edit</a>
                              
                              @if($u->isDeleted == 0)
                              <a href="{{route('admin.user.delete',['user' => 'organik', 'id' => $u->id])}}" class="btn btn-danger">Matikan</a>
                              @else
                              <a href="{{route('admin.user.enable',['user' => 'organik', 'id' => $u->id])}}" class="btn btn-success">Aktifkan</a>
                              @endif
                            </td>
                        </tr>


                    @endforeach

                    </tbody>

                </table>
              </div><!-- /.card-body -->

            </div><!-- /.card -->
          </div><!-- /.col-12 -->
        </div> <!-- /.row -->
       
        
      </div><!-- /.container-fluid -->

    </section>
    <!-- /.content -->

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
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });

</script>

@endpush