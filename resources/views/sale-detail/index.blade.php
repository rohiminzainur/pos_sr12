@extends('layouts.master')

@section('title')
    Transaction Sale
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

        .table-sale tbody tr:last-child {
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
    <li class="active">Transaction Sale</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <form class="form-product">
                        @csrf
                        <div class="form-group row text-center">
                            <label for="code_product" class="col-lg-2">Code Product</label>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <input type="hidden" name="sales_id" id="sales_id" value="{{ $sales_id }}">
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
                    <table class="table table-stiped table-bordered table-sale">
                        <thead>
                            <th width="5%">No</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th width="15%">Total</th>
                            <th>Discount</th>
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
                            <form action="{{ route('transactions.save') }}" class="form-sale" method="post">
                                @csrf
                                <input type="hidden" name="sales_id" value="{{ $sales_id }}">
                                <input type="hidden" name="total" id="total">
                                <input type="hidden" name="total_item" id="total_item">
                                <input type="hidden" name="paid" id="paid">
                                <input type="hidden" name="members_id" id="members_id" value="{{ $memberSelected->id }}">
                                <div class="form-group row">
                                    <label for="totalrp" class="col-lg-3 control-label">Total</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="totalrp" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="code_member" class="col-lg-3 control-label">Member</label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="code_member"
                                                value="{{ $memberSelected->code_member }}">
                                            <span class="input-group-btn">
                                                <button onclick="showMember()" class="btn btn-bitbucket" type="button"><i
                                                        class="fa fa-plus-circle"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="discount" class="col-lg-3 control-label">Discount</label>
                                    <div class="col-lg-8">
                                        <input type="number" name="discount" id="discount" readonly class="form-control"
                                            value="{{ !empty($memberSelected->members_id) ? $discount : 0 }}">
                                    </div>
                                </div>
                                <div class="form-group
                                            row">
                                    <label for="paidrp" class="col-lg-3 control-label">Paid</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="paidrp" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="received" class="col-lg-3 control-label">Received</label>
                                    <div class="col-lg-8">
                                        <input type="number" id="received" class="form-control" name="received"
                                            value="{{ $sale->received ?? 0 }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="back" class="col-lg-3 control-label">back Money</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="back" name="back" class="form-control"
                                            value="0" readonly>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-sm pull-right btn-save"><i
                            class="fa fa-floppy-o"></i>
                        Save Transaction</button>
                </div>
            </div>
        </div>
    </div>
    @includeIf('sale-detail.product')
    @includeIf('sale-detail.member')
@endsection

@push('scripts')
    <script>
        let table, table2;

        $(function() {
            $('body').addClass('sidebar-collapse');
            table = $('.table-sale').DataTable({
                    processing: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route('transactions.data', $sales_id) }}',
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
                            data: 'selling_price'
                        },
                        {
                            data: 'total'
                        },
                        {
                            data: 'discount'
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
                    setTimeout(() => {
                        $('#received').trigger('input');
                    }, 300);
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
                $.post(`{{ url('/transactions') }}/${id}`, {
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

            $('#received').on('input', function() {
                if ($(this).val() == "") {
                    $(this).val(0).select();
                }
                loadForm($('#discount').val(), $(this).val());
            }).focus(function() {
                $(this).select();
            })

            $('.btn-save').on('click', function() {
                $('.form-sale').submit();
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
            $.post('{{ route('transactions.store') }}', $('.form-product').serialize())
                .done(response => {
                    $('#code_product').focus();
                    table.ajax.reload(() => loadForm($('#discount').val()));
                })
                .fail(errors => {
                    alert('Tidak dapat menyimpan data');
                    return;
                })
        }

        function showMember() {
            $('#modal-member').modal('show');
        }

        function selectMember(id, code) {
            $('#members_id').val(id);
            $('#code_member').val(code);
            $('#discount').val('{{ $discount }}');
            loadForm($('#discount').val());
            $('#received').val(0).focus().select();
            hideMember();

        }

        function hideMember() {
            $('#modal-member').modal('hide');
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

        function loadForm(discount = 0, received = 0) {

            $('#total').val($('.total').text());
            $('#total_item').val($('.total_item').text());

            $.get(`{{ url('/transactions/loadform') }}/${discount}/${$('.total').text()}/${received}`)
                .done(response => {
                    $('#totalrp').val('Rp. ' + response.totalrp);
                    $('#paidrp').val('Rp. ' + response.paidrp);
                    $('#paid').val(response.paid);
                    $('.show-paid').text('Bayar: Rp. ' + response.paidrp);
                    $('.show-terbilang').text(response.terbilang);

                    $('#back').val('Rp.' + response.backrp);
                    if ($('#received').val() != 0) {
                        $('.show-paid').text('Kembali: Rp. ' + response.backrp);
                        $('.show-terbilang').text(response.back_terbilang);
                    }
                })
                .fail(errors => {
                    alert('Tidak dapat menampilkan data');
                    return;
                })
        }
    </script>
@endpush
