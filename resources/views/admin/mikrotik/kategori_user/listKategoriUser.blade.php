@extends('admin.template')

@section('header', 'Kategori Hotspot User')

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
              <h3 class="card-title">Hotspot User Active</b></h3>
            </div><!-- /.card-header -->
            
            <div class="card-body">

            <a href="{{route('admin.mikrotik.showCreateGroupUser')}}" class="btn btn-primary">Tambah Kategori</a>

              <table id="datatables1" class="table table-bordered table-hover">
                  <thead>
                  
                      <tr>
                          <th>No.</th>
                          <th>Kategori</th>
                          <th>Receive Rate</th>
                          <th>Transfer Rate</th>
                          <th>Min Receive Rate</th>
                          <th>Min Transfer Rate</th>
                          <th>Shared User</th>
                          <th>Priority</th>
                          <th>Action</th>
                      </tr>

                  </thead>

                  <tbody>

                  @foreach ($kategori as $k)

                      <tr>
                          <td>{{$loop->iteration}}</td>
                          <td>{{$k->group}}</td>
                          <td>{{$k->rx_rate}}</td>
                          <td>{{$k->tx_rate}}</td>
                          <td>{{$k->min_rx_rate}}</td>
                          <td>{{$k->min_tx_rate}}</td>
                          <td>{{$k->port_limit}}</td>
                          <td>{{$k->priority}}</td>
                          <td>
                              <a href="{{route('admin.mikrotik.editCreateGroupUser', ['id' => $k->id])}}" class="btn btn-primary">Edit</a>
                              <a href="#" class="btn btn-danger">Disable</a>
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