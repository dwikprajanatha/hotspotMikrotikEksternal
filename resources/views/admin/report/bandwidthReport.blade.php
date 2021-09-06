@extends('admin.template')

@section('header', "Report Penggunaan Internet")

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
              <h3 class="card-title">Pertumbuhan Pengguna</h3>

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
              <canvas id="chartPertumbuhanUser" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div><!-- /.card-body -->
          </div><!-- /.card -->

        </div><!-- /.col-12 -->

      </div><!-- /.row -->


      <div class="row">
        <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Penggunaan Bandwidth</h3>
  
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
                <canvas id="chartPenggunaanBandwidth" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div><!-- /.card-body -->
            </div><!-- /.card -->
          </div><!-- /.col-12 -->

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
                  <canvas id="chartProporsiPengguna" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div><!-- /.card-body -->
              </div><!-- /.card -->

        </div>

        <div class="col-6">

            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Proporsi Umur User</h3>
      
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
                    <canvas id="chartProporsiUmur" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div><!-- /.card-body -->
            </div><!-- /.card -->

        </div>
      </div><!-- /.row -->

      <div class="row">
          <div class="col-12">
               <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Penggunaan Per User</h3>
      
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                <table id="datatablePenggunaanUser" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Username</th>
                        <th>Kategori</th>
                        <th>Platform</th>
                        <th>Penggunaan</th>
                        <th>last login</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>MadeMade</td>
                        <td>Dewasa</td>
                        <td>Original</td>
                        <td>4.1 GB</td>
                        <td>22-02-2021</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>MadeCenik</td>
                        <td>Remaja</td>
                        <td>Facebook</td>
                        <td>5.2 GB</td>
                        <td>21-02-2021</td>
                    </tr>
                    </tbody>

                </table>
                </div><!-- /.card-body -->
              </div><!-- /.card -->
          </div>
      </div>

      <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Penggunaan Berdasarkan Waktu</h3>
      
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
                    <canvas id="chartWaktuPenggunaan" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div><!-- /.card-body -->
            </div><!-- /.card -->
        </div><!-- /.col-12 -->
      </div><!-- /.row -->

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

@endsection

@push('javascript')
<!-- ChartJS -->
<script src="{{asset('admin/plugins/chart.js/Chart.min.js')}}"></script>

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

$(document).ready(function(){

    //all options and dataset configuration start here

    // LINE CHART OPTIONS
    var lineChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      elements: {
          line: {
              tension : 0,
              fill : false,
          },
      },
      legend: {
        display: false,
      },
      scales: {
        xAxes: [{
          gridLines : {
            display : false,
          }
        }],
        yAxes: [{
          gridLines : {
            display : false,
          }
        }]
      }
    };

    // LINE CHART DATA
    var lineChartData = {
      labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
        {
          label               : 'User',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : 5,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          data                : [28, 48, 40, 19, 86, 27, 90],
          
        }
      ]
    };

    
    // PIE CHART OPTIONS
    var pieChartOptions = {
        maintainAspectRatio : false,
        responsive : true,
    };


    // PIE CHART DATA
    var pieChartData = {
      labels: [
          'Organik',
          'Facebook',
          'Google',
      ],
      datasets: [
        {
          data: [60,25,15],
          backgroundColor : ['#0F9D58', '#3b5998', '#DB4437'],
        }
      ]
    };

    // BAR CHART OPTION
    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    };

    //BAR CHART DATA
    var barChartData = {
      labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
        {
          label               : '',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : 5,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          data                : [28, 48, 40, 19, 86, 27, 90],
          
        }
      ]
    };


    //all options and dataset configuration end here

    //Line Chart Pertumbuhan User
    var ctx_lineChartPertumbuhanUser = $('#chartPertumbuhanUser').get(0).getContext('2d');

    var dataPertumbuhanUser = $.extend(true,{},lineChartData);
    dataPertumbuhanUser.datasets[0].label = "User";
    dataPertumbuhanUser.datasets[0].data = [1,2,3,4,5,6,7];

    var optionsLineChartPertumbuhan = lineChartOptions;

    var lineChartPertumbuhanUser = new Chart(ctx_lineChartPertumbuhanUser, {
      type: 'line',
      data: dataPertumbuhanUser,
      options: optionsLineChartPertumbuhan,
    });

    // Line Chart Penggunaan Bandwidth
    var ctx_lineChartPenggunaanBandwidth = $('#chartPenggunaanBandwidth').get(0).getContext('2d');

    var dataPenggunaanBandwidth = $.extend(true,{},lineChartData);
    dataPenggunaanBandwidth.datasets[0].label = "Penggunaan";
    dataPenggunaanBandwidth.datasets[0].data = [7,6,5,4,3,2,1];

    var optionsLinePenggunaanBandwidth = lineChartOptions;

    var lineChartPenggunaanBandwidth = new Chart(ctx_lineChartPenggunaanBandwidth, {
        type: 'line',
        data: dataPenggunaanBandwidth,
        options: optionsLinePenggunaanBandwidth,
    });
    
    // Pie Chart Proporsi Asal User
    var ctx_pieChartProporsiUser = $('#chartProporsiPengguna').get(0).getContext('2d');

    var dataProporsiUser =  $.extend(true,{},pieChartData);
    dataProporsiUser.labels = ['Organik','Facebook','Google'];

    dataProporsiUser.datasets[0].data = [10,20,25];
    dataProporsiUser.datasets[0].backgroundColor = ['#0F9D58', '#3b5998', '#DB4437'];

    var optionsProporsiUser = pieChartOptions;

    var pieChartProporsiUser = new Chart(ctx_pieChartProporsiUser, {
        type : 'pie',
        options : optionsProporsiUser,
        data : dataProporsiUser,
    });

    //Pie Chart Proporsi Umur
    var ctx_pieChartProporsiUmur = $('#chartProporsiUmur').get(0).getContext('2d');

    var dataProporsiUmur = $.extend(true,{},pieChartData);
    dataProporsiUmur.labels = ['Dewasa', 'Remaja', 'Anak'];
    dataProporsiUmur.datasets[0].data = [30,50,20];
    dataProporsiUmur.datasets[0].backgroundColor = ['#0F9D58', '#3b5998', '#DB4437'];

    var optionsProporsiUmur = pieChartOptions;

    var pieChartProporsiUmur = new Chart(ctx_pieChartProporsiUmur, {
        type : 'pie',
        options : optionsProporsiUmur,
        data : dataProporsiUmur,
    });

    //Datatables penggunaan per user
    $('#datatablePenggunaanUser').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });

    //Bar Chart Waktu Penggunaan
    var ctx_chartWaktuPenggunaan = $('#chartWaktuPenggunaan').get(0).getContext('2d');
    
    var optionChartWaktuPenggunaan = barChartOptions;

    var dataChartWaktuPenggunaan = barChartData;
    dataChartWaktuPenggunaan.datasets[0].label = "Jumlah Penggunaan";
    dataChartWaktuPenggunaan.labels = ["00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00"]
    dataChartWaktuPenggunaan.datasets[0].data = [19, 36, 26, 7, 12, 36, 46, 16, 41, 22, 6, 38, 41, 23, 43, 14, 26, 19, 43, 46, 9, 44, 27, 23]

    var chartWaktuPenggunaan = new Chart(ctx_chartWaktuPenggunaan, {
        type    : 'bar',
        options : optionChartWaktuPenggunaan,
        data    : dataChartWaktuPenggunaan,
    });

});

</script>

@endpush