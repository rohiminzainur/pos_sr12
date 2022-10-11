@extends('layouts.master')

@section('title')
    Product
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Product List</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="btn-group">
                        <button onclick="addForm('{{ route('products.store') }}')" class="btn btn-primary btn-facebook"><i
                                class="fa fa-plus-circle"></i>
                            Add</button>
                        <button onclick="deleteSelected('{{ route('products.delete_selected') }}')" class="btn btn-danger"><i
                                class="fa fa-trash"></i>
                            Delete Selected</button>
                        <button onclick="printBarcode('{{ route('products.print_barcode') }}')" class="btn btn-github"><i
                                class="fa fa-barcode"></i>
                            Print Barcode</button>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <form action="" method="post" class="form-product">
                        @csrf
                        <table class="table table-stiped table-bordered">
                            <thead>
                                <th>
                                    <input type="checkbox" name="select_all" id="select_all">
                                </th>
                                <th width="5%">No</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Purchase_Price</th>
                                <th>Discount</th>
                                <th>Selling_Price</th>
                                <th>Stock</th>
                                <th width="15%"><i class="fa fa-cog"></i></th>
                            </thead>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @includeIf('product.form')
@endsection

@push('scripts')
    <script>
        let table;

        $(function() {
            $('.table').DataTable({
                processing: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('products.data') }}'
                },
                columns: [{
                        data: 'select_all'
                    },
                    {
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'code_product'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'name_category'
                    },
                    {
                        data: 'brand'
                    },
                    {
                        data: 'purchase_price'
                    },
                    {
                        data: 'discount'
                    },
                    {
                        data: 'selling_price'
                    },
                    {
                        data: 'stock'
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
                            alert('Data Tersimpan');
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
            $('#modal-form .modal-title').text('Add Product');
            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=name]').focus();
        }

        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Product');
            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=name]').focus();

            $.get(url)
                .done((response) => {
                    $('#modal-form [name=name]').val(response.name);
                    $('#modal-form [name=categories_id]').val(response.categories_id);
                    $('#modal-form [name=brand]').val(response.brand);
                    $('#modal-form [name=purchase_price]').val(response.purchase_price);
                    $('#modal-form [name=discount]').val(response.discount);
                    $('#modal-form [name=selling_price]').val(response.selling_price);
                    $('#modal-form [name=stock]').val(response.stock);
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
                    });
            }
        }

        function deleteSelected(url) {
            if ($('input:checked').length > 1) {
                if (confirm('Yakin ingin menghapus data?')) {
                    $.post(url, $('.form-product').serialize())
                        .done((response) => {
                            table.ajax.reload();
                        })
                        .fail((errors) => {
                            alert('Tidak dapat menghapus data');
                            return;
                        });
                }
            } else {
                alert('Pilih data yang akan dihapus');
                return;
            }
        }

        function printBarcode(url) {
            if ($('input:checked').length < 1) {
                alert('Pilih data yang akan diprint!!');
                return;
            } else if ($('input:checked').length < 3) {
                alert('Pilih minimal 3 data untuk diprint!!')
            } else {
                $('.form-product').attr('action', url).attr('target', '_blank').submit();
            }
        }
    </script>
@endpush
