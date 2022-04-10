@extends('admin.template')

@section('header', "Keluhan")


@section('body')
    
<section class="content">
    <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Keluhan Pengguna</h3>
                        </div>
                        <div class="card-body">
                            <form action="#">

                                <div class="form-group">
                                    <label for="title">NIK Pengirim</label>
                                    <input type="text" name="title" id="title" class="form-control" value="{{$det_keluhan->nik}}" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="title">Nama Pengirim</label>
                                    <input type="text" name="title" id="title" class="form-control" value="{{$det_keluhan->nama}}" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="desc">Deskripsi</label>
                                    <textarea rows="3" name="desc" id="desc" class="form-control" readonly>{{$det_keluhan->isi}}</textarea>
                                </div>

                                <div class="form-group">
                                        <a href="{{route('admin.listKeluhan')}}" class="btn btn-primary">Kembali</a>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div> <!-- /.row -->


    </div>
</section>

@endsection
