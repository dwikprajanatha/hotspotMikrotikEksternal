@extends('admin.template')

@section('header', "Create Account")

@push('css')
@endpush

@section('body')

<section class="content">
    <div class="container-fluid">

        
            <div class="row">
                <div class="col-md-7">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Kategori Akun Hotspot</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.mikrotik.CreateGroupUser')}}" method="POST">
                                @csrf

                                <input type="hidden" name="id" value="{{$kategori->id}}">
                                
                                <div class="form-group">
                                    <label for="group">Nama Kategori (Group Name)</label>
                                    <input type="text" name="group" id="group" class="form-control" value={{}}>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="rx_rate">Receive Rate</label>
                                            <input type="text" name="rx_rate" id="rx_rate" class="form-control">
                                        </div>

                                        <div class="col-6">
                                            <label for="tx_rate">Transfer Rate</label>
                                            <input type="text" name="tx_rate" id="tx_rate" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="min_rx_rate">Minimum Receive Rate</label>
                                            <input type="text" name="min_rx_rate" id="min_rx_rate" class="form-control">
                                        </div>

                                        <div class="col-6">
                                            <label for="min_tx_rate">Minimum Transfer Rate</label>
                                            <input type="text" name="min_tx_rate" id="min_tx_rate" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="idle_timeout">Idle Timeout</label>
                                            <input type="text" name="idle_timeout" id="idle_timeout" class="form-control">
                                        </div>

                                        <div class="col-6">
                                            <label for="session_timeout">Session Timeout</label>
                                            <input type="text" name="session_timeout" id="session_timeout" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="port_limit">Shared User</label>
                                            <input type="text" name="port_limit" id="port_limit" class="form-control" value="1"> 
                                        </div>

                                        <div class="col-6">
                                            <label for="priority">Priority</label>
                                            <input type="text" name="priority" id="priority" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                        <input type="submit" class="btn btn-primary" value="Submit">
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>


    </div>
</section>



@endsection

@push("js")

@endpush