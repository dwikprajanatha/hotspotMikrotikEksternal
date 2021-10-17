@extends('admin.template')

@section('header', "Dashboard")

@push('css')
<!-- DataTables -->

@endpush

@section('body')
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <div class="row">

          <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{$data['userBaru']}}</h3>

                <p>User Baru</p>
              </div>
              <div class="icon">
                <i class="fas fa-user"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div><!-- /.small-box bg-info -->
          </div><!-- /.col-lg-3 col-6 -->

          <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>{{$data['totalUser']}}</h3>

                <p>Total User</p>
              </div>
              <div class="icon">
                <i class="fas fa-users"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div><!-- /.small-box bg-info -->
          </div><!-- /.col-lg-3 col-6 -->
        

        <div class="col-lg-3 col-6">
          <!-- small card -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3>{{$data['totalAktif']}}</h3>

              <p>User Aktif Hari ini</p>
              

            </div>
            <div class="icon">
              <i class="fas fa-globe"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div><!-- /.small-box bg-info -->
        </div><!-- /.col-lg-3 col-6 -->

        <div class="col-lg-3 col-6">
          <!-- small card -->
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>15</h3>

              <p>Laporan Baru</p>    

            </div>
            <div class="icon">
              <i class="fas fa-exclamation-circle"></i>
            </div>
            <a href="#" class="small-box-footer">
              More info <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div><!-- /.small-box bg-info -->
        </div><!-- /.col-lg-3 col-6 -->

      </div><!-- /.row -->

      <div class="row">
        <div class="col-6">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Proporsi Pengguna</h3>
  
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <canvas id="platform" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div><!-- /.card-body -->
          </div><!-- /.card -->

        </div><!-- /.col-6 -->


        <div class="col-6">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Proporsi Pengguna</h3>
  
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <canvas id="umur" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div><!-- /.card-body -->
          </div><!-- /.card -->

        </div><!-- /.col-6 -->
        

      </div><!-- /.row -->

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

<!-- ChartJS -->
<script src="{{asset('admin/plugins/chart.js/Chart.min.js')}}"></script>

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

<script>

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.

    var platformCanvas = $('#platform').get(0).getContext('2d')
    var platformUmur = $('#umur').get(0).getContext('2d')

    var pieOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.

    var base_url = window.location.origin;

    $.ajax({
        url: base_url + '/api/report/platform/all',
        type: "GET",
        headers: {'Accept': 'application/json'},
        data: {'api_token': '<?php echo(Auth::user()->api_token) ?>' },
        success: function(response){
                       
                  var pieData = {
                    labels: [
                        'Organik',
                        'Facebook',
                        'Google',
                    ],
                    datasets: [
                      {
                        data: response.data,
                        backgroundColor : ['#0F9D58', '#3b5998', '#DB4437'],
                      }
                    ]
                  }

                  new Chart(platformCanvas, {
                    type: 'pie',
                    data: pieData,
                    options: pieOptions
                  });

                },
      });


      $.ajax({
        url: base_url + '/api/report/umur/all',
        type: "GET",
        headers: {'Accept': 'application/json'},
        data: {'api_token': '<?php echo(Auth::user()->api_token) ?>' },
        success: function(response){
                       
                  var pieData = {
                    labels: [
                        'Organik',
                        'Facebook',
                        'Google',
                    ],
                    datasets: [
                      {
                        data: response.data,
                        backgroundColor : ['#0F9D58', '#3b5998', '#DB4437'],
                      }
                    ]
                  }

                  new Chart(platformUmur, {
                    type: 'pie',
                    data: pieData,
                    options: pieOptions
                  });

                },
      });


</script>

@endpush