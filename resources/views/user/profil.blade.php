@extends('layouts.master')

@section('title')
    Edit Profil
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Edit Profil</li>
@endsection

@section('content')
    <div class="controller">
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <form action="{{ route('users.update_profil') }}" class="form-profil" method="post" data-toggle="validator"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="alert alert-info alert-dismissible" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>
                                <i class="icon fa fa-check">Change Success</i>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-md-2 control-label">Name</label>
                                <div class="col-lg-6">
                                    <input type="text" name="name" class="form-control" id="name" required
                                        autofocus value="{{ $profil->name }}">
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="photo" class="col-md-2 control-label">Profil</label>
                                <div class="col-lg-4">
                                    <input type="file" name="photo" class="form-control" id="photo"
                                        onchange="preview('.show-photo', this.files[0])">
                                    <span class="help-block with-errors"></span>
                                    <br>
                                    <div class="show-photo">
                                        <img src="{{ url($profil->photo ?? '/') }}" alt="" width="200">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="old_password" class="col-md-2 control-label">Password Old</label>
                                <div class="col-md-6">
                                    <input type="password" name="old_password" id="old_password" class="form-control"
                                        minlength="6">
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-md-2 control-label">Password</label>
                                <div class="col-md-6">
                                    <input type="password" name="password" id="password" class="form-control"
                                        minlength="6">
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password_confirmation" class="col-md-2 control-label">Confirm Password</label>
                                <div class="col-md-6">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control" data-match="#password">
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer text-right">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Save
                                Change</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#old_password').on('keyup', function() {
                if ($(this).val() != "") $('#password, #password_confirmation').attr('required', true);
                else $('#password, #password_confirmation').attr('required', false);
            })

            $('.form-profil').validator().on('submit', function(e) {
                if (!e.preventDefault()) {
                    $.ajax({
                            url: $('.form-profil').attr('action'),
                            type: $('.form-profil').attr('method'),
                            data: new FormData($('.form-profil')[0]),
                            async: false,
                            processData: false,
                            contentType: false
                        })
                        .done(response => {
                            $('[name=name]').val(response.name);

                            $('.show-photo').html(
                                `<img src="{{ url('/') }}${response.photo}" width="200">`);
                            $('.img-profil').attr('src', `{{ url('/') }}/${response.photo}`);
                            $('.alert').fadeIn();
                            setTimeout(() => {
                                $('.alert').fadeOut();
                            }, 3000);
                        })
                        .fail(errors => {
                            if (errors.status == 422) {
                                alert(errors.responseJSON);
                            } else {
                                alert('Tidak dapat menyimpan data');
                            }
                            return;
                        });
                }
            });
        });
    </script>
@endpush
