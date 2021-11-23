@extends('admin.template')

@section('header', "Edit Hotspot User")


@section('body')
    
<section class="content">
    <div class="container-fluid">

        
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Akun User Hotspot</h3>
                        </div>
                        <div class="card-body">
                            <form action="#" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="nik">NIK</label>
                                    <input type="text" name="nik" id="nik" class="form-control"  placeholder="NIK" disabled value="{{isset($user->nik) ? $user->nik : ''}}">
                                </div>

                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" name="nama" id="nama" class="form-control"  placeholder="Nama" disabled value="{{isset($user->nama) ? $user->nama : ''}}">
                                </div>

                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <input type="text" name="alamat" id="alamat" class="form-control"  placeholder="Alamat" disabled value="{{isset($user->alamat) ? $user->alamat : ''}}">
                                </div>

                                <div class="form-group">
                                    <label for="kategori_umur">Kategori</label>
                                    <input type="text" name="kategori_umur" id="kategori_umur" class="form-control"  disabled placeholder="Kategori" disabled value="{{isset($user->kategori) ? $user->kategori : ''}}">
                                </div>

                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" id="username" class="form-control"  placeholder="username" disabled value="{{isset($user->username) ? $user->username : ''}}">
                                </div>

                                <div class="form-group">
                                    <label for="kategori_user">User Profiles</label>
                                    <select class="custom-select form-control-border" name="kategori_user" id="kategori_user">

                                        @foreach($groups as $g)
                                        <option value="{{$g->id}}" {{$user->group_id == $g->id ? 'selected' : ''}}>{{$g->group}}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="form-group">
                                        <input type="submit" class="btn btn-primary" value="Submit">
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-6">

                    <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Custom Rules</h3>
                    </div><!-- /.card-header -->
                    
                    <div class="card-body">

                        <form action="#" method="post">
                            @csrf
                            <div class="row">

                                <div class="col-7">
                                    <input type="text" class="form-control" placeholder="Attribute">
                                </div>

                                <div class="col-3">
                                    <input type="text" class="form-control" placeholder="Value">
                                </div>

                                <div class="col-2">
                                    <input type="submit" class="btn btn-primary" value="Add">
                                </div>
                               
                            </div>
                        </form>

                        <table id="datatables1" class="table table-bordered table-hover">
                            <thead>
                            
                                <tr>
                                    <th>No.</th>
                                    <th>Attribute</th>
                                    <th>Value</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>

                            </thead>

                            <tbody>

                            @foreach ($custom_rules as $custom)

                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$custom->attribute}}</td>
                                    <td>{{$custom->value}}</td>
                                    <td>{{$custom->status}}</td>
                                    <td><a href="#" class="btn btn-danger">Disable</a></td>
                                </tr>

                            @endforeach

                            </tbody>

                        </table>
                    </div><!-- /.card-body -->

                    </div><!-- /.card -->
                </div><!-- /.col-6 -->

            </div> <!-- /.row -->


                


    </div>
</section>

@endsection
