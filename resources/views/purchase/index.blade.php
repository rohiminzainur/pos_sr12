@extends('layouts.master')

@section('title')
    List Purchase
@endsection

@section('breadcrumb')
    @parent
    <li class="active">List Purchase</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="addForm()" class="btn btn-facebook"><i class="fa fa-plus-circle"></i>
                        New Transaction</button>
                    @empty(!session('purchases_id'))
                        <a href="{{ route('purchase-details.index') }}" class="btn btn-success"><i class="fa fa-edit"></i>
                            Transaction Aktif</a>
                    @endempty
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-stiped table-bordered table-purchase">
                        <thead>
                            <th width="5%">No</th>
                            <th>Date</th>
                            <th>Supplier</th>
                            <th>Total Item</th>
                            <th>Total Price</th>
                            <th>Diskon</th>
                            <th>Total Bayar</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('purchase.supplier')
    @include('purchase.detail')
@endsection

@push('scripts')
    <script>
        let table, table1;

        $(function() {
            table = $('.table-purchase').DataTable({
                processing: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('purchases.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'supplier'
                    },
                    {
                        data: 'total_item'
                    },
                    {
                        data: 'total_price'
                    },
                    {
                        data: 'discount'
                    },
                    {
                        data: 'paid'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    }

                ]
            });
            $('.table-supplier').DataTable();
            table1 = $('.table-detail').DataTable({
                processing: true,
                bsort: false,
                dom: 'Brt',
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
                    }

                ]
            })
        });

        function addForm() {
            $('#modal-supplier').modal('show');
        }

        function showDetail(url) {
            $('#modal-detail').modal('show');
            table1.ajax.url(url);
            table1.ajax.reload();
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
    </script>
@endpush
