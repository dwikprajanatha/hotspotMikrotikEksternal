@extends('admin.template')

@section('header', "List Pengumuman")

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
                <h3 class="card-title">List <b>Pengumuman</b></h3>
              </div><!-- /.card-header -->
              
              <div class="card-body">
              <!-- <button class="btn btn-primary"><i class="fas fa-plus"></i>Tambah Akun</button> -->
              <a href="{{route('admin.account.create.view')}}" class="btn btn-primary"><i class="fas fa-plus" style="padding-right: 5px;"></i>Tambah Akun</a>
                <table id="datatables1" class="table table-bordered table-hover">
                
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>

                    </thead>
                    <tbody>

                    @foreach ($pengumuman as $p)

                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$p->title}}</td>
                            <td>{{$p->desc}}</td>
                            <td>{{$u->isDeleted == 0 ? 'Aktif' : 'Non-Aktif'}}</td>

                            <td>
                                <a href="#" class="btn btn-primary"><i class="fas fa-edit" style="padding-right:5px"></i>Edit</a>
                                @if($u->isDeleted == 0)
                                <a href="#" class="btn btn-danger"><i class="fas fa-times" style="padding-right:5px"></i>Matikan</a>
                                @else
                                <a href="#" class="btn btn-success"><i class="fas fa-check" style="padding-right:5px"></i>Aktifkan</a>
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