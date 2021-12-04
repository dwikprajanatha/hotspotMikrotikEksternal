@extends('admin.template')

@section('header', "Edit Pengumuman")


@section('body')
    
<section class="content">
    <div class="container-fluid">

        
            <div class="row">
                
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Pengumuman</h3>
                        </div>
                        <div class="card-body">
                            <form action="#" method="POST" enctype=multipart/form-data>
                                @csrf

                                <input type="hidden" name="id" value="{{$pengumuman->id}}">

                                <div class="form-group">
                                    <label for="title">Judul</label>
                                    <input type="text" name="title" id="title" class="form-control" value="{{isset($pengumuman->title) ? $pengumuman->title : ''}}">
                                </div>

                                <div class="form-group">
                                    <label for="desc">Deskripsi</label>
                                    <input type="text" name="desc" id="desc" class="form-control" value="{{isset($pengumuman->desc) ?$pengumuman->title : '-'}}">
                                </div>

                                <!-- Carousel Gambar Lama -->

                                <!-- UPLOAD GAMBAR -->
                                <div class="form-group">
                                    <label for="file">Upload Gambar</label>
                                    <input type="file" name="files" class="form-control" multiple>
                                </div>


                                <div class="form-group">
                                        <input type="submit" class="btn btn-primary" value="Submit">
                                </div>
                                @endif

                            </form>
                        </div>
                    </div>

            </div> <!-- /.row -->


                


    </div>
</section>

@endsection
