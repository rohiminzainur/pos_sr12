@extends('layouts.master')

@section('title')
    Transaction Purchase
@endsection

@push('css')
    <style>
        .show-paid {
            font-size: 5em;
            text-align: center;
            height: 100px;
        }

        .show-terbilang {
            padding: 10px;
            background: #f0f0f0;
        }

        .table-purchase tbody tr:last-child {
            display: none;
        }

        @media(max-width: 768px) {
            .show-pay {
                font-size: 3em;
                height: 70px;
                padding-top: 5px;
            }
        }
    </style>
@endpush
@section('breadcrumb')
    @parent
    <li class="active">Transaction Purchase</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <table>
                        <tr>
                            <td>Supplier</td>
                            <td> : {{ $supplier->name }}</td>
                        </tr>
                        <tr>
                            <td>Phone Number</td>
                            <td> : {{ $supplier->phone_number }}</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td> : {{ $supplier->address }}</td>
                        </tr>
                    </table>
                </div>
                <div class="box-body">
                    <form class="form-product">
                        @csrf
                        <div class="form-group row text-center">
                            <label for="code_product" class="col-lg-2">Code Product</label>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <input type="hidden" name="purchases_id" id="purchases_id" value="{{ $purchases_id }}">
                                    <input type="hidden" name="products_id" id="products_id">
                                    <input type="text" class="form-control" name="code_product" id="code_product">
                                    <span class="input-group-btn">
                                        <button onclick="showProduct()" class="btn btn-bitbucket" type="button"><i
                                                class="fa fa-plus-circle"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                    <table class="table table-stiped table-bordered table-purchase">
                        <thead>
                            <th width="5%">No</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th width="15%">Total</th>
                            <th>Sub-Total</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="show-paid bg-primary">
                            </div>
                            <div class="show-terbilang"></div>
                        </div>
                        <div class="col-lg-4">
                            <form action="{{ route('purchases.store') }}" class="form-purchase" method="post">
                                @csrf
                                <input type="hidden" name="purchases_id" value="{{ $purchases_id }}">
                                <input type="hidden" name="total" id="total">
                                <input type="hidden" name="total_item" id="total_item">
                                <input type="hidden" name="paid" id="paid">
                                <div class="form-group row">
                                    <label for="totalrp" class="col-lg-3 control-label">Total</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="totalrp" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="discount" class="col-lg-3 control-label">Discount</label>
                                    <div class="col-lg-8">
                                        <input type="number" name="discount" id="discount" class="form-control"
                                            value="{{ $discount }}">
                                    </div>
                                </div>
                                <div class="form-group
                                            row">
                                    <label for="paidrp" class="col-lg-3 control-label">Paid</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="paidrp" class="form-control">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-sm pull-right btn-save"><i class="fa fa-floppy-o"></i>
                        Save Transaction</button>
                </div>
            </div>
        </div>
    </div>
    @includeIf('purchase-detail.product')
@endsection

@push('scripts')
    <script>
        let table, table2;

        $(function() {
            $('body').addClass('sidebar-collapse');
            table = $('.table-purchase').DataTable({
                    processing: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route('purchase-details.data', $purchases_id) }}',
                    },
                    columns: [{
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
                            data: 'purchase_price'
                        },
                        {
                            data: 'total'
                        },
                        {
                            data: 'subtotal'
                        },
                        {
                            data: 'aksi',
                            searchable: false,
                            sortable: false
                        }

                    ],
                    dom: 'Brt',
                    bSort: false,
                })
                .on('draw.dt', function() {
                    loadForm($('#discount').val());
                });
            table2 = $('.table-product').DataTable();
            $(document).on('input', '.quantity', function() {
                let id = $(this).data('id');
                let total = parseInt($(this).val());

                if (total < 1) {
                    $(this).val(1);
                    alert('Total stock tidak boleh kurang dari 1');
                    return;
                }
                if (total > 10000) {
                    $(this).val(10000);
                    alert('Total stock tidak boleh lebih dari 10000');
                    return;
                }
                $.post(`{{ url('/purchase-details') }}/${id}`, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'put',
                        'total': total
                    })
                    .done(response => {
                        $(this).on('mouseout', function() {
                            table.ajax.reload(() => loadForm($('#discount').val()));
                        });
                    })
                    .fail(errors => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            });

            $(document).on('input', '#discount', function() {
                if ($(this).val() == "") {
                    $(this).val(0).select();
                }
                loadForm($(this).val());
            });

            $('.btn-save').on('click', function() {
                $('.form-purchase').submit();
            });
        });

        function showProduct() {
            $('#modal-product').modal('show');
        }

        function hideProduct() {
            $('#modal-product').modal('hide');
        }

        function selectProduct(id, code) {
            $('#products_id').val(id);
            $('#code_product').val(code);
            hideProduct();
            addProduct();
        }

        function addProduct() {
            $.post('{{ route('purchase-details.store') }}', $('.form-product').serialize())
                .done(response => {
                    $('#code_product').focus();
                    table.ajax.reload(() => loadForm($('#discount').val()));
                })
                .fail(errors => {
                    alert('Tidak dapat menyimpan data');
                    return;
                })
        }


        function deleteData(url) {
            if (confirm('Yakin ingin dihapus?')) {
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        table.ajax.reload(() => loadForm($('#discount').val()));
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus data');
                        return;
                    })
            }
        }

        function loadForm(discount = 0) {
            $('#total').val($('.total').text());
            $('#total_item').val($('.total_item').text());

            $.get(`{{ url('/purchase-details/loadform') }}/${discount}/${$('.total').text()}`)
                .done(response => {
                    $('#totalrp').val('Rp. ' + response.totalrp);
                    $('#paidrp').val('Rp. ' + response.paidrp);
                    $('#paid').val(response.paid);
                    $('.show-paid').text('Rp. ' + response.paidrp);
                    $('.show-terbilang').text(response.terbilang);
                })
                .fail(errors => {
                    alert('Tidak dapat menampilkan data');
                    return;
                })
        }
    </script>
@endpush
