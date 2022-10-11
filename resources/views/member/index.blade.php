@extends('layouts.master')

@section('title')
    Member
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="addForm('{{ route('members.store') }}')" class="btn btn-primary btn-facebook"><i
                            class="fa fa-plus-circle"></i>
                        Tambah</button>
                    <button onclick="printMember('{{ route('members.print_member') }}')" class="btn btn-github"><i
                            class="fa fa-id-card"></i>
                        Print Member</button>
                </div>
                <div class="box-body table-responsive">
                    <form action="" method="post" class="form-member">
                        @csrf
                        <table class="table table-stiped table-bordered">
                            <thead>
                                <th width="5%">
                                    <input type="checkbox" name="select_all" id="select_all">
                                </th>
                                <th width="5%">No</th>
                                <th>Code Member</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Phone Number</th>
                                <th width="15%"><i class="fa fa-cog"></i></th>
                            </thead>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @includeIf('member.form')
@endsection

@push('scripts')
    <script>
        let table;

        $(function() {
            $('.table').DataTable({
                processing: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('members.data') }}'
                },
                columns: [{
                        data: 'select_all',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'code_member'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'address'
                    },
                    {
                        data: 'phone_number'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    }

                ]
            });
            $('#modal-form').validator().on('submit', function(e) {
                if (!e.preventDefault()) {
                    $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                        .done((response) => {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                        })
                        .fail((errors) => {
                            alert('Tidak dapat menyimpan data');
                            return;
                        });
                }
            })
            $('[name=select_all]').on('click', function() {
                $(':checkbox').prop('checked', this.checked);
            })
        });

        function addForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Add Member');
            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=name]').focus();
        }

        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Member');
            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=name]').focus();

            $.get(url)
                .done((response) => {
                    $('#modal-form [name=name]').val(response.name);
                    $('#modal-form [name=phone_number]').val(response.phone_number);
                    $('#modal-form [name=address]').val(response.address);
                })
                .fail((errors) => {
                    alert('Tidak dapat menampilkan data');
                    return;
                });
        }

        function deleteData(url) {
            if (confirm('Yakin ingin dihapus?')) {
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus data');
                        return;
                    })
            }
        }

        function printMember(url) {
            if ($('input:checked').length < 1) {
                alert('Pilih data yang akan diprint!!');
                return;
            } else {
                $('.form-member').attr('action', url).attr('target', '_blank').submit();
            }
        }
    </script>
@endpush
