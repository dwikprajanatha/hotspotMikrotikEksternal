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
                            <h3 class="card-title">Akun Admin</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.account.create')}}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="nip">NIP</label>
                                    <input type="text" name="nip" id="nip" class="form-control"  placeholder="NIP" value="{{isset($user->nip) ? $user->nip : ''}}">
                                    @error('nip')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" name="nama" id="nama" class="form-control"  placeholder="Nama" value="{{isset($user->nama) ? $user->nama : ''}}">
                                    @error('nama')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" id="username" class="form-control"  placeholder="username" value="{{isset($user->username) ? $user->username : ''}}">
                                    @error('username')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email"  id="email" class="form-control"  placeholder="Email" value="{{isset($user->email) ? $user->email : ''}}">
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if(!isset($user->email))
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" id="password"  class="form-control"  placeholder="Password">
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                @endif
                                
                                @if(isset($user->role))

                                <div class="form-group">
                                    <label for="roleSelector">Role Akun</label>
                                    <select class="custom-select form-control-border" name="role" id="roleSelector">
                                        <option value="1" {{$user->role == 1 ? 'selected' : ''}}>Root Admin</option>
                                        <option value="2" {{$user->role == 2 ? 'selected' : ''}}>Admin Network</option>
                                        <option value="3" {{$user->role == 3 ? 'selected' : ''}}>Admin</option>
                                        
                                    </select>
                                </div>

                                @else

                                <div class="form-group">
                                    <label for="roleSelector">Role Akun</label>
                                    <select class="custom-select form-control-border" name="role" id="roleSelector">
                                        <option value="1">Root Admin</option>
                                        <option value="2">Admin Network</option>
                                        <option value="3">Admin</option>
                                    </select>
                                </div>

                                @endif

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