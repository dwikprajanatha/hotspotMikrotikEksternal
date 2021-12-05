@extends('admin.template')

@section('header', "Edit Pengumuman")


@section('body')
    
<section class="content">
    <div class="container-fluid">

        
            <div class="row">
                <div class="col-md-7">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Pengumuman</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.pengumuman.update')}}" method="POST" enctype=multipart/form-data>
                                @csrf

                                <input type="hidden" name="id" value="{{$pengumuman->id}}">

                                <div class="form-group">
                                    <label for="title">Judul</label>
                                    <input type="text" name="title" id="title" class="form-control" value="{{isset($pengumuman->title) ? $pengumuman->title : ''}}">
                                </div>

                                <div class="form-group">
                                    <label for="desc">Deskripsi</label>
                                    <textarea rows="3" name="desc" id="desc" class="form-control">{{isset($pengumuman->desc) ? $pengumuman->desc : '-'}}</textarea>
                                </div>

                                <!-- Carousel Gambar Lama -->
                                <table>
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Gambar</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>

                                    </thead>
                                    @foreach($files as $file)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td><img src="{{asset('storage/'.$file->link))}}" style="display:block;" width="100%" height="100%"></td>
                                        <td>{{$file->status == 1 ? 'Aktif' : 'Non-Aktif'}}</td>
                                        <td>
                                            @if($file->status == 1)
                                            <a href="{{route('admin.pengumuman.pic.disable', ['id' => $file->id])}}" class="btn btn-danger"><i class="fas fa-times" style="padding-right:5px"></i>Matikan</a>
                                            @else
                                            <a href="{{route('admin.pengumuman.pic.enable', ['id' => $file->id])}}" class="btn btn-success"><i class="fas fa-check" style="padding-right:5px"></i>Aktifkan</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>


                                <!-- UPLOAD GAMBAR -->
                                <div class="form-group">
                                    <label for="file">Upload Gambar</label>
                                    <input type="file" name="files[]" id="file" class="form-control" multiple>
                                </div>


                                <div class="form-group">
                                        <input type="submit" class="btn btn-primary" value="Submit">
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div> <!-- /.row -->


                


    </div>
</section>

@endsection
