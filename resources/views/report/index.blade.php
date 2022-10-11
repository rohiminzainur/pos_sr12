@extends('layouts.master')

@section('title')
    Report Income {{ tanggal_indonesia($dateStart, false) }} s/d {{ tanggal_indonesia($dateEnd, false) }}
@endsection

@push('css')
    <link rel="stylesheet"
        href="{{ asset('AdminLTE-2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Report</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="updatePeriode()" class="btn btn-primary btn-facebook"><i class="fa fa-plus-circle"></i>
                        Change Periode</button>
                    <a href="{{ route('report.export_pdf', [$dateStart, $dateEnd]) }}" target="_blank"
                        class="btn btn-danger"><i class="fa fa-file-pdf-o"></i>
                        Export PDF</a>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <th width="5%">No</th>
                            <th>date</th>
                            <th>Sale</th>
                            <th>Purchase</th>
                            <th>Expense</th>
                            <th>Income</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @includeIf('report.form')
@endsection

@push('scripts')
    <script src="{{ asset('AdminLTE-2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
    </script>
    <script>
        let table;

        $(function() {
            $('.table').DataTable({
                processing: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('report.data', [$dateStart, $dateEnd]) }}',
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
                        data: 'sale'
                    },
                    {
                        data: 'purchase'
                    },
                    {
                        data: 'expense'
                    },
                    {
                        data: 'income'
                    }
                ],
                dom: 'Brt',
                bSort: false,
                bPaginate: false,
            });

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        });

        function updatePeriode(url) {
            $('#modal-form').modal('show');
        }
    </script>
@endpush
