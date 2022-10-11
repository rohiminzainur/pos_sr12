@extends('layouts.master')

@section('title')
    Setting
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Setting</li>
@endsection

@section('content')
    <div class="controller">
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <form action="{{ route('settings.update') }}" class="form-setting" method="post" data-toggle="validator"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="alert alert-info alert-dismissible" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>
                                <i class="icon fa fa-check">Change Success</i>
                            </div>
                            <div class="form-group row">
                                <label for="company_name" class="col-md-2 control-label">Company Name</label>
                                <div class="col-lg-6">
                                    <input type="text" name="company_name" class="form-control" id="company_name"
                                        required autofocus>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="phone_number" class="col-md-2 control-label">Phone Number</label>
                                <div class="col-lg-6">
                                    <input type="text" name="phone_number" class="form-control" id="phone_number"
                                        required>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="address" class="col-lg-2 control-label">Address</label>
                                <div class="col-lg-6">
                                    <textarea name="address" class="form-control" id="address" rows="3" required></textarea>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="path_logo" class="col-md-2 control-label">Logo Company</label>
                                <div class="col-lg-4">
                                    <input type="file" name="path_logo" class="form-control" id="path_logo"
                                        onchange="preview('.show-logo', this.files[0])">
                                    <span class="help-block with-errors"></span>
                                    <br>
                                    <div class="show-logo"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="path_member_card" class="col-md-2 control-label">Design Card Member</label>
                                <div class="col-lg-4">
                                    <input type="file" name="path_member_card" class="form-control" id="path_member_card"
                                        onchange="preview('.show-card-member', this.files[0])">
                                    <span class="help-block with-errors"></span>
                                    <br>
                                    <div class="show-card-member"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="discount" class="col-md-2 control-label">Discount</label>
                                <div class="col-lg-4">
                                    <input type="number" name="discount" class="form-control" id="discount" required>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="type_nota" class="col-md-2 control-label">Type Nota</label>
                                <div class="col-lg-3">
                                    <select name="type_nota" id="type_nota" class="form-control" required>
                                        <option value="1">Nota Small</option>
                                        <option value="2">Nota Big</option>
                                    </select>
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
            showData();
            $('.form-setting').validator().on('submit', function(e) {
                if (!e.preventDefault()) {
                    $.ajax({
                            url: $('.form-setting').attr('action'),
                            type: $('.form-setting').attr('method'),
                            data: new FormData($('.form-setting')[0]),
                            async: false,
                            processData: false,
                            contentType: false
                        })
                        .done(response => {
                            showData();
                            $('.alert').fadeIn();

                            setTimeout(() => {
                                $('.alert').fadeOut();
                            }, 3000);
                        })
                        .fail(errors => {
                            alert('Tidak dapat menyimpan data');
                            return;
                        })
                }
            });
        });

        function showData() {
            $.get('{{ route('settings.show') }}')
                .done(response => {
                    $('[name=company_name]').val(response.company_name);
                    $('[name=phone_number]').val(response.phone_number);
                    $('[name=address]').val(response.address);
                    $('[name=discount]').val(response.discount);
                    $('[name=type_nota]').val(response.type_nota);
                    $('title').text(response.company_name + ' | Setting');
                    $('.logo-lg').text(response.company_name);

                    $('.show-logo').html(`<img src="{{ url('/') }}${response.path_logo}" width="200">`);
                    $('.show-card-member').html(
                        `<img src="{{ url('/') }}${response.path_member_card}" width="300">`);
                    $('[rel=icon]').attr('href', `{{ url('/') }}${response.path_logo}`)
                })
                .fail(errors => {
                    alert('Tidak dapat menampilkan data');
                    return;
                });
        }
    </script>
@endpush
