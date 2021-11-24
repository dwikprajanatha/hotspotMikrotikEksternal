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
                            <form id="formEditKategori" action="{{route('admin.mikrotik.CreateGroupUser')}}" method="POST">
                                @csrf

                                <input type="hidden" name="id" value="{{$kategori->id}}">

                                <div class="form-group">
                                    <label for="group">Nama Kategori (Group Name)</label>
                                    <input type="text" name="group" id="group" class="form-control" value="{{$kategori->group}}">
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="rx_rate">Receive Rate</label>
                                            <input type="text" name="rx_rate" id="rx_rate" class="form-control" value="{{$kategori->rx_rate}}">
                                        </div>

                                        <div class="col-6">
                                            <label for="tx_rate">Transfer Rate</label>
                                            <input type="text" name="tx_rate" id="tx_rate" class="form-control" value="{{$kategori->tx_rate}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="min_rx_rate">Minimum Receive Rate</label>
                                            <input type="text" name="min_rx_rate" id="min_rx_rate" class="form-control" value="{{$kategori->min_rx_rate}}">
                                        </div>

                                        <div class="col-6">
                                            <label for="min_tx_rate">Minimum Transfer Rate</label>
                                            <input type="text" name="min_tx_rate" id="min_tx_rate" class="form-control" value="{{$kategori->min_tx_rate}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="idle_timeout">Idle Timeout</label>
                                            <input type="text" name="idle_timeout" id="idle_timeout" class="form-control" value="{{$kategori->idle_timeout}}">
                                        </div>

                                        <div class="col-6">
                                            <label for="session_timeout">Session Timeout</label>
                                            <input type="text" name="session_timeout" id="session_timeout" class="form-control" value="{{$kategori->session_timeout}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="port_limit">Shared User</label>
                                            <input type="text" name="port_limit" id="port_limit" class="form-control" value="{{$kategori->port_limit}}"> 
                                        </div>

                                        <div class="col-6">
                                            <label for="priority">Priority</label>
                                            <input type="text" name="priority" id="priority" class="form-control" value="{{$kategori->priority}}"> 
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

@push("javascript")

<script src="{{asset('login/vendor/validate/jquery.validate.min.js')}}"></script>
<script src="{{asset('login/vendor/validate/additional-methods.min.js')}}"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$("#formEditKategori").validate({
			errorElement: "p",
			errorClass: "text-danger",
			rules: {
				group: {
					required: true,
					nowhitespace: true,
				},
				rx_rate: {
					required: true,
					nowhitespace: true,
				},
				tx_rate: {
					required: true,
					nowhitespace: true,
				},
				min_rx_rate: {
					required: true,
					nowhitespace: true,
				},
				min_tx_rate: {
					required: true,
					nowhitespace: true,
				},
				idle_timeout: {
					required: true,
					nowhitespace: true,
                    number: true,
				},
                session_timeout: {
					required: true,
					nowhitespace: true,
                    number: true,
				},
                port_limit: {
					required: true,
					nowhitespace: true,
                    number: true,
				},
                priority: {
					required: true,
					nowhitespace: true,
                    number: true,
                    range: [1,8],
				},
			},

			messages: {
                group: {
					required: "Nama Kategori diperlukan",
					nowhitespace: "Mohon tidak menggunakan spasi",
				},
				rx_rate: {
					required: "RX rate diperlukan",
                    nowhitespace: "Mohon tidak menggunakan spasi",
				},
				tx_rate: {
					required: "TX rate diperlukan",
                    nowhitespace: "Mohon tidak menggunakan spasi",
				},
				min_rx_rate: {
					required: "Minimum RX rate diperlukan",
                    nowhitespace: "Mohon tidak menggunakan spasi",
				},
				min_tx_rate: {
					required: "Minimum TX rate diperlukan",
                    nowhitespace: "Mohon tidak menggunakan spasi",
				},
				idle_timeout: {
					required: "idle timeout diperlukan",
					nowhitespace: "Mohon tidak menggunakan spasi",
                    number: "Harus Angka",
				},
                session_timeout: {
					required: "session timeout diperlukan",
					nowhitespace: "Mohon tidak menggunakan spasi",
                    number: "Harus Angka",
				},
                port_limit: {
					required: "port limit diperlukan",
					nowhitespace: "Mohon tidak menggunakan spasi",
                    number: "Harus Angka",
				},
                priority: {
					required: "priority diperlukan",
					nowhitespace: "Mohon tidak menggunakan spasi",
                    number: "Harus Angka",
                    range: "Antara 1 sampai 8",
				}
			},
		});
	});
</script>


@endpush