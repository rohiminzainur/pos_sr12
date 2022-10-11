@extends('layouts.master')

@section('title')
    List Sale
@endsection

@section('breadcrumb')
    @parent
    <li class="active">List Sale</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body table-responsive">
                    <table class="table table-stiped table-bordered table-sale">
                        <thead>
                            <th width="5%">No</th>
                            <th>Date</th>
                            <th>Code Member</th>
                            <th>Total Item</th>
                            <th>Total Price</th>
                            <th>Discount</th>
                            <th>Total Paid</th>
                            <th>cashier</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('sale.detail')
@endsection

@push('scripts')
    <script>
        let table, table1;

        $(function() {
            table = $('.table-sale').DataTable({
                processing: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('sales.data') }}',
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
                        data: 'code_member'
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
                        data: 'cashier'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    }

                ]
            });
            table1 = $('.table-detail').DataTable({
                processing: true,
                bSort: false,
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
                        data: 'selling_price'
                    },
                    {
                        data: 'total'
                    },
                    {
                        data: 'subtotal'
                    },
                ]
            })
        });

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
