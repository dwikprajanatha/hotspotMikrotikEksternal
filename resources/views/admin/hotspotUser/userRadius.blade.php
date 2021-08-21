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
                    
                        @if(array_key_exists('email', $users[0]))
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Kategori</th>
                            <th>Action</th>
                        </tr>
                        @else
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Platform</th>
                            <th>Action</th>
                        </tr>
                        @endif

                    </thead>


                    <tbody>

                    @foreach ($users as $u)

                        <tr>
                            <th>{{$loop->iteration}}</th>
                            <th>{{$u->nama}}</th>
                            <th>{{$u->alamat}}</th>
                            <th>{{$u->kategori}}</th>
                            <th>
                                <button class="btn btn-primary"><i class="fa fa-pencil"></i></button>
                                <button class="btn btn-danger"><i class="fa fa-times"></i></button>
                            </th>
                        </tr>


                    @endforeach

                    </tbody>

                    <tfoot>

                        @if(array_key_exists('email', $users[0]))
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Kategori</th>
                            <th>Action</th>
                        </tr>
                        @else
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Platform</th>
                            <th>Action</th>
                        </tr>
                        @endif
                        
                    </tfoot>

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