@extends('admin.template')

@section('header', "Report Penggunaan Internet")

@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">

 <!-- Tempusdominus Bootstrap 4 -->
 <link rel="stylesheet" href="{{asset('admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
 
@endpush

@section('body')
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

      {{-- REPORT OPTIONS --}}
        <div class="row">
          <div class="col-3">

              @if ($range == 'weekly')

              <div class="form-group">
                <label>Pilih Minggu ke :</label>
                <div class="input-group date" id="datepickerMinggu" data-target-input="nearest">
                  <input type="text" class="form-control datetimepicker-input" id="datepickerInput" data-target="#datepickerMinggu"/>
                  <div class="input-group-append" data-target="#datepickerMinggu" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                  </div>
                </div>
              </div>

              @elseif ($range == 'monthly')

              <div class="form-group">
                <label>Pilih Bulan :</label>
                <div class="input-group date" id="datepickerBulan" data-target-input="nearest">
                  <input type="text" class="form-control datetimepicker-input" id="datepickerInput" data-target="#datepickerBulan"/>
                  <div class="input-group-append" data-target="#datepickerBulan" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                  </div>
                </div>
              </div>


              @elseif ($range == 'yearly')

              <div class="form-group">
                <label>Pilih Tahun :</label>
                <div class="input-group date" id="datepickerTahun" data-target-input="nearest">
                  <input type="text" class="form-control datetimepicker-input" id="datepickerInput" data-target="#datepickerTahun"/>
                  <div class="input-group-append" data-target="#datepickerTahun" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                  </div>
                </div>
              </div>

              @endif
              
          </div>

          <div class="col-4">
            <div class="form-group">
              <label>&nbsp</label>
              <div>
                {{-- <input type="text" class="form-control datetimepicker-input" id="datepickerMingguValue" data-target="#datepickerMinggu"/> --}}
                <button class="btn btn-primary" id="reload">Reload!</button>
              </div>
            </div>
          </div>

        </div>


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
                      </tr>
                      </thead>

                      <tbody>
                      {{-- <tr>
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
                      </tr> --}}
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

<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('admin/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>



<script>

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


    //all function goes here

function lineChart(ctx, options, data){

  var line = new Chart(ctx, {
    type: 'line',
    data: data,
    options: options,
  });

};


function pieChart(ctx, options, data){

  var pie = new Chart(ctx, {
    type : 'pie',
    options : options,
    data : data,
  });

};

// function datatables(element,data){

//       element.DataTable({
//       "paging": true,
//       "lengthChange": false,
//       "searching": true,
//       "ordering": true,
//       "info": true,
//       "autoWidth": false,
//       "responsive": true,
//       "ajax" : data,
//     });

// }

function barChart(ctx, options, data){

  var bar = new Chart(ctx, {
    type    : 'bar',
    options : options,
    data    : data,
  });

};

  
//Line Chart Pertumbuhan User
function pertumbuhanUser(arr_data){
  var ctx_lineChartPertumbuhanUser = $('#chartPertumbuhanUser').get(0).getContext('2d');

  var dataPertumbuhanUser = $.extend(true,{},lineChartData);

  dataPertumbuhanUser.labels = arr_data.label;

  dataPertumbuhanUser.datasets[0].label = "User";
  dataPertumbuhanUser.datasets[0].data = arr_data.data;

  var optionsLineChartPertumbuhan = lineChartOptions;

  lineChart(ctx_lineChartPertumbuhanUser,optionsLineChartPertumbuhan, dataPertumbuhanUser);
}

function penggunaanBandwidth(arr_data){
  
  // Line Chart Penggunaan Bandwidth
  var ctx_barChartPenggunaanBandwidth = $('#chartPenggunaanBandwidth').get(0).getContext('2d');

  var dataPenggunaanBandwidth = $.extend(true,{},barChartData);

  dataPenggunaanBandwidth.labels = arr_data.label;

  dataPenggunaanBandwidth.datasets[0].label = "Penggunaan (GB)";
  dataPenggunaanBandwidth.datasets[0].data = arr_data.data;

  var optionsBarPenggunaanBandwidth = barChartOptions;

  barChart(ctx_barChartPenggunaanBandwidth, optionsBarPenggunaanBandwidth, dataPenggunaanBandwidth);


}

function proporsiPengguna(data){
  
  // Pie Chart Proporsi Asal User
  var ctx_pieChartProporsiUser = $('#chartProporsiPengguna').get(0).getContext('2d');

  var dataProporsiUser =  $.extend(true,{},pieChartData);
  dataProporsiUser.labels = ['Organik','Facebook','Google'];

  dataProporsiUser.datasets[0].data = data;
  dataProporsiUser.datasets[0].backgroundColor = ['#0F9D58', '#3b5998', '#DB4437'];

  var optionsProporsiUser = pieChartOptions;

  pieChart(ctx_pieChartProporsiUser, optionsProporsiUser, dataProporsiUser);

}

function proporsiUmurPengguna(data){

  //Pie Chart Proporsi Umur
  var ctx_pieChartProporsiUmur = $('#chartProporsiUmur').get(0).getContext('2d');

  var dataProporsiUmur = $.extend(true,{},pieChartData);
  dataProporsiUmur.labels = ['Dewasa', 'Remaja', 'Anak'];

  dataProporsiUmur.datasets[0].data = data;
  dataProporsiUmur.datasets[0].backgroundColor = ['#0F9D58', '#3b5998', '#DB4437'];

  var optionsProporsiUmur = pieChartOptions;

  pieChart(ctx_pieChartProporsiUmur, optionsProporsiUmur, dataProporsiUmur);

}

function penggunaanBerdasarkanWaktu(data) {
     //Bar Chart Waktu Penggunaan
  var ctx_chartWaktuPenggunaan = $('#chartWaktuPenggunaan').get(0).getContext('2d');

  var optionChartWaktuPenggunaan = barChartOptions;

  var dataChartWaktuPenggunaan = barChartData;
  dataChartWaktuPenggunaan.datasets[0].label = "Jumlah Penggunaan";
  dataChartWaktuPenggunaan.labels = ["00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00"]
  dataChartWaktuPenggunaan.datasets[0].data = data;
  // [19, 36, 26, 7, 12, 36, 46, 16, 41, 22, 6, 38, 41, 23, 43, 14, 26, 19, 43, 46, 9, 44, 27, 23]
  barChart(ctx_chartWaktuPenggunaan, optionChartWaktuPenggunaan, dataChartWaktuPenggunaan);

}

// function penggunaanPerUser(data) {

//     datatablesID = $("#datatablePenggunaanUser");
//     datatables(datatablesID, data);
  
// }

    //all function end here

$(document).ready(function(){

    var range = "<?php echo($range) ?>";

    $('#datepickerMinggu').datetimepicker({
        format    : "L",
      
    });

    $('#datepickerBulan').datetimepicker({
        viewMode : "years",
        format  : 'MM'
    });

    $('#datepickerTahun').datetimepicker({
        format  : 'YYYY'
    });

    $("#reload").click(function(){

      var date = $('.input-group.date').data('datetimepicker').date();
      
      date_str = moment(date).format('DDMMYYYY');
      var base_url = window.location.origin;

      // get data pertumbuhan user
      // $.get(base_url + '/api/report/pertumbuhan/'+ range +'/'+ date_str, function(data){
      //     var arr_data = data.data;
      //     console.log(arr_data);
      //     pertumbuhanUser(arr_data);
      // });
      
      $.ajax({
        url: base_url + '/api/report/pertumbuhan/'+ range +'/'+ date_str,
        type: "GET",
        headers: {'Accept': 'application/json'},
        data: {'api_token': '<?php echo(Auth::user()->api_token) ?>' },
        success: function(response){
                  var arr_data = response.data;
                  console.log(arr_data);
                  pertumbuhanUser(arr_data);
                },
      });


      // $.get(base_url + '/api/report/pengguna/'+ range +'/'+ date_str, function(data){
      //     var arr_data = data.data;
      //     console.log(arr_data);
      //     penggunaanBandwidth(arr_data);
      // });

      $.ajax({
        url: base_url + '/api/report/pengguna/'+ range +'/'+ date_str,
        type: "GET",
        headers: {'Accept': 'application/json'},
        data: {'api_token': '<?php echo(Auth::user()->api_token) ?>' },
        success: function(response){
                  var arr_data = response.data;
                  console.log(arr_data);
                  penggunaanBandwidth(arr_data);
                },
      });

      // $.get(base_url + '/api/report/platform/'+ range +'/'+ date_str, function(data){
      //     var arr_data = data.data;
      //     console.log(arr_data);
      //     proporsiPengguna(arr_data)
      // });

      $.ajax({
        url: base_url + '/api/report/platform/'+ range +'/'+ date_str,
        type: "GET",
        headers: {'Accept': 'application/json'},
        data: {'api_token': '<?php echo(Auth::user()->api_token) ?>' },
        success: function(response){
                  var arr_data = response.data;
                  console.log(arr_data);
                  proporsiPengguna(arr_data);
                },
      });

      // $.get(base_url + '/api/report/umur/'+ range +'/'+ date_str, function(data){
      //     var arr_data = data.data;
      //     console.log(arr_data);
      //     proporsiUmurPengguna(arr_data)
      // });

      $.ajax({
        url: base_url + '/api/report/umur/'+ range +'/'+ date_str,
        type: "GET",
        headers: {'Accept': 'application/json'},
        data: {'api_token': '<?php echo(Auth::user()->api_token) ?>' },
        success: function(response){
                  var arr_data = response.data;
                  console.log(arr_data);
                  proporsiUmurPengguna(arr_data);
                },
      });


      //  $.get(base_url + '/api/report/penggunaan/'+ range +'/'+ date_str, function(data){
      //      var arr_data = data.data;
      //      console.log(arr_data);
      //      penggunaanBerdasarkanWaktu(arr_data)
      //  });

      // $.get(base_url + '/api/report/penggunaan/'+ range +'/'+ date_str, function(data){
      //     var arr_data = data.data;
      //     console.log(arr_data);
      //     penggunaanPerUser(arr_data)
      // });

      // datatables here

      $("#datatablePenggunaanUser").dataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": true,
          "ajax" :  {
                      url: base_url + '/api/report/penggunaan/'+ range +'/'+ date_str,
                      type: "GET",
                      headers: {'Accept': 'application/json'},
                      data: {'api_token': '<?php echo(Auth::user()->api_token) ?>' },
                    },
          "columns": [
            {"data" : "no"},
            {"data" : "username"},
            {"data" : "kategori"},
            {"data" : "platform"},
            {"data" : "penggunaan"},
          ],
      });


                
    });

   



   

    

  
    




 
});

</script>

@endpush