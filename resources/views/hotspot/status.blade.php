@extends('admin.template')

@section('body')
    
 <!-- Main content -->
 <section class="content">

    <!-- Default box -->

    <div class="row">

            <div class="col-3"></div>

            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Status Pengguna</h3>

                        <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                            <i class="fas fa-times"></i>
                        </button>
                        </div>
                    </div>
                    <div class="card-body">

                        <table class="table table-responsive table-borderless">
                                <tbody>
                                <tr><td> Username : </td><td>{{$request['username']}}</td></tr>
                                <tr><td> IP address : </td><td>{{$request['ip']}}</td></tr>
                                <tr><td>bytes up/down : </td><td>{{$request['bytes_up']}} / {{$request['bytes_down']}}</td></tr>
                                @if(isset($request['session_time_left']))
                                <tr><td>Time Left: </td><td>{{$request['session_time_left']}}</td></tr>
                                @else
                                <tr><td>connected: <i class="glyphicon glyphicon-time"></i> </td><td>{{$request['uptime']}}</td></tr>
                                @endif
                            </tbody>
                        </table>
                        <center><a href="{{$request['link_logout']}}" class="btn btn-primary">Log Out</a></center>                  
                    </div>
                </div>
            </div>
            
            <div class="col-3"></div>
        
    </div>

    <div class="row">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistik Pengguna 7 Hari terakhir</h3>

                <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                    <i class="fas fa-times"></i>
                </button>
                </div>
            </div>
            <div class="card-body">

                <table class="table table-responsive table-bordered">
                    <thead>
                        <th>No.</th>
                        <th>Waktu Login</th>
                        <th>Waktu Logout</th>
                        <th>Lama Akses</th>
                        <th>Total Upload</th>
                        <th>Total Download</th>
                    </thead>
                    <tbody>
                        @foreach($data_usage as $data)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$data->acctstarttime}}</td>
                            <td>{{$data->acctstoptime}}</td>
                            @if(!empty($data->acctstoptime))
                            <td>{{$data->acctsessiontime}}</td>
                            @else
                            <td> - </td>
                            @endif
                            <td>{{ number_format(floatval($data->acctinputoctets) , 2 ,'.' , '') }} GB</td>
                            <td>{{ number_format(floatval($data->acctoutputoctets) , 2 ,'.' , '') }} GB</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>

    
 </section>

@endsection

