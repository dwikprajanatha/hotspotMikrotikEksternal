@extends('admin.template')

@section('header', "Buat Pengumuman")


@section('body')
    
<section class="content">
    <div class="container-fluid">

        
            <div class="row">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Buat Pengumuman</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.pengumuman.create')}}" method="POST" enctype=multipart/form-data>
                            @csrf

                            <div class="form-group">
                                <label for="title">Judul</label>
                                <input type="text" name="title" id="title" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="desc">Deskripsi</label>
                                <input type="textarea" rows="3" name="desc" id="desc" class="form-control">
                            </div>

                            <!-- UPLOAD GAMBAR -->
                            <div class="form-group">
                                <label for="file">Upload Gambar</label>
                                <input type="file" name="files" class="form-control" multiple>
                            </div>


                            <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Submit">
                            </div>

                        </form>
                    </div>
                </div>
            </div> <!-- /.row -->


                


    </div>
</section>

@endsection
