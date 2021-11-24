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
                            <form id="formEditKategori" action="{{route('admin.mikrotik.updateGroupUser')}}" method="POST">
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

                <div class="col-md-5">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Read Me!</h3>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-4">Receive Rate</dt>
                                <dd class="col-sm-8">Receive Rate berarti yang diterima oleh Router, berarti Upload yang dilakukan oleh Device Client. Receive Rate berfungsi sebagai limitasi bandwidth upload maksimal yang didapat. contoh format penulisan adalah angka disertai Huruf M/k untuk menentukan Mbps atau Kbps, <mark>Contoh : 2M atau 512k </mark></dd>
                                <dt class="col-sm-4">Transfer/Transmit Rate</dt>
                                <dd class="col-sm-8">Transfer berarti yang dikirim oleh Router, berarti Download yang dilakukan oleh Device Client. Transmit Rate berfungsi sebagai limitasi bandwidth download maksimal yang didapat. contoh format penulisan adalah angka disertai Huruf M/k untuk menentukan Mbps atau Kbps, <mark>Contoh : 2M atau 512k </mark></dd>
                                <dt class="col-sm-4">Minimum Receive Rate</dt>
                                <dd class="col-sm-8">Minimum Receive Rate merupakan nilai bandwidth Upload terkecil yang wajib didapatkan ketika jaringan sedang sibuk atau padat. <mark>Contoh penulisan sama seperti Receive Rate dan Transfer Rate yaitu : 2M atau 512k </mark></dd>
                                <dt class="col-sm-4">Minimum Transfer Rate</dt>
                                <dd class="col-sm-8">Minimum Transfer Rate merupakan nilai bandwidth Download terkecil yang wajib didapatkan ketika jaringan sedang sibuk atau padat. <mark>Contoh penulisan sama seperti Receive Rate dan Transfer Rate yaitu : 2M atau 512k </mark></dd>
                                <dt class="col-sm-4">Idle Timeout</dt>
                                <dd class="col-sm-8">Idle Timeout merupakan nilai untuk menentukan berapa lama user akan di-disconnect ketika tidak terdapat aktifitas internet pada devicenya. <mark>contoh penulisannya menggunakan detik, misalnya ingin 1 jam, maka tulislah 3600</mark></dd>
                                <dt class="col-sm-4">Session Timeout</dt>
                                <dd class="col-sm-8">Idle Timeout merupakan nilai untuk menentukan berapa lama user dapat mengakses internet dalam satu sesi login dan akan di-disconnect dan harus login kembali. <mark>contoh penulisannya menggunakan detik, misalnya ingin 1 jam, maka tulislah 3600</mark></dd>
                                <dt class="col-sm-4">Shared User</dt>
                                <dd class="col-sm-8">Shared User merupakan nilai untuk menentukan berapa banyak device yang dapat menggunakan akun yang sama. <mark>contoh penulisannya normal angka saja</mark></dd>
                                <dt class="col-sm-4">Priority</dt>
                                <dd class="col-sm-8">Priority merupakan nilai untuk menentukan prioritas dari kategori (1 sampai dengan 8), semakin kecil semakin tinggi prioritasnya. <mark>contoh penulisannya normal angka saja</mark></dd>
                            </dl>
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