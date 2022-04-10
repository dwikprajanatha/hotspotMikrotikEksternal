@extends('admin.template')

@section('header', "Buat Load Balancing")

@push('css')
@endpush

@section('body')

<section class="content">
    <div class="container-fluid">

        
            <div class="row">
                <div class="col-md-7">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Tambah Load Balancing</h3>
                        </div>
                        <div class="card-body">
                            <form id="formLoadBalancing" action="{{route('admin.mikrotik.post.loadBalancing')}}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="group">Nama</label>
                                    <input type="text" name="nama" id="nama" class="form-control">
                                </div>

                                <hr style="border-width:2px">

                                <div class="form-group">
                                    <p>ISP #1</p>
                                    <div class="row">
                                        <div class="col-4">
                                            <!-- <label for="interface">Interface</label> -->
                                            <!-- <input type="text" name="interface[]" id="interface" class="form-control"> -->
                                            <label>Interface</label>
                                            <select class="form-control" name="interface[]">
                                                <option selected hidden></option>
                                                @foreach($interfaces as $interface)
                                                <option value="{{$interface['name']}}">{{$interface['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-4">
                                            <label for="ip_address">IP Address</label>
                                            <input type="text" name="ip_address[]" id="ip_address" class="form-control">
                                        </div>
                                        
                                        <div class="col-4">
                                            <label for="netmask">Netmask</label>
                                            <input type="text" name="network[]" id="netmask" class="form-control">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-4">
                                            <label for="gateway">IP Gateway</label>
                                            <input type="text" name="gateway[]" id="gateway" class="form-control">
                                        </div>

                                        <div class="col-4">
                                            <label for="dns">DNS</label>
                                            <input type="text" name="dns[]" id="dns" class="form-control">
                                        </div>

                                        <div class="col-4">
                                            <label for="dns">Bandwidth (Mbps)</label>
                                            <input type="bandwidth" name="bandwidth[]" id="dns" class="form-control">
                                        </div>
                                    </div>

                                </div>

                                <hr style="border-width:2px">

                                <div class="form-group">
                                    <p>ISP #2</p>
                                    <div class="row">
                                        <div class="col-4">
                                            <label>Interface</label>
                                            <select class="form-control" name="interface[]">
                                                <option selected hidden></option>
                                                @foreach($interfaces as $interface)
                                                <option value="{{$interface['name']}}">{{$interface['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-4">
                                            <label for="ip_address">IP Address</label>
                                            <input type="text" name="ip_address[]" id="ip_address" class="form-control">
                                        </div>
                                        
                                        <div class="col-4">
                                            <label for="netmask">Netmask</label>
                                            <input type="text" name="network[]" id="netmask" class="form-control">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-4">
                                            <label for="gateway">IP Gateway</label>
                                            <input type="text" name="gateway[]" id="gateway" class="form-control">
                                        </div>

                                        <div class="col-4">
                                            <label for="dns">DNS</label>
                                            <input type="text" name="dns[]" id="dns" class="form-control">
                                        </div>

                                        <div class="col-4">
                                            <label for="bandwidth">Bandwidth (Mbps)</label>
                                            <input type="bandwidth" name="bandwidth[]" id="bandwidth" class="form-control">
                                        </div>
                                    </div>

                                    
                                </div>
                                
                                <div id="isp"></div>
                                
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" id="tambahISP">Tambah ISP</button>
                                </div>
                                
                                <hr style="border-width:2px">

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
                                <dt class="col-sm-4">Nama</dt>
                                <dd class="col-sm-8">Nama yang diberikan untuk load balancing</dd>
                                <dt class="col-sm-4">Interface</dt>
                                <dd class="col-sm-8">Interface atau ether yang terkoneksi ke ISP</dd>
                                <dt class="col-sm-4">IP Address</dt>
                                <dd class="col-sm-8">IP Address untuk interface pada router mikrotik</dd>
                                <dt class="col-sm-4">Netmask</dt>
                                <dd class="col-sm-8">Netmask dari IP diatas (contoh : 255.255.255.0)</dd>
                                <dt class="col-sm-4">Gateway</dt>
                                <dd class="col-sm-8">Gateway pada interface</dd>
                                <dt class="col-sm-4">DNS</dt>
                                <dd class="col-sm-8">DNS Server interface pada router mikrotik</dd>
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

    var x = 3;

	$(document).ready(function(){

        $("#tambahISP").click(function(){
            console.log('masuk');
            $('#isp').append(
                ''
                    
                )
            x = x + 1;
        });

		$("#formLoadBalancing").validate({
			errorElement: "p",
			errorClass: "text-danger",
			rules: {
				nama: {
					required: true,
					nowhitespace: true,
				},
				interface: {
					required: true,
					nowhitespace: true,
				},
				ip_address: {
					required: true,
					ipv4: true,
				},
				gateway: {
					required: true,
					ipv4: true,
				},
				dns: {
					required: true,
					ipv4: true,
				},
			}, 

			messages: {
                nama: {
					required: "Nama Kategori diperlukan",
					ipv4: "Harus IP Address",
				},
				interface: {
					required: "Interface diperlukan",
                    ipv4: "Harus IP Address",
				},
				ip_address: {
					required: "IP Address diperlukan",
                    ipv4: "Harus IP Address",
				},
                gateway: {
					required: "IP Gateway diperlukan",
                    ipv4: "Harus IP Address",
				},
				dns: {
					required: "DNS Server diperlukan",
                    ipv4: "Harus IP Address",
				},
			},
		});
	});
</script>


@endpush