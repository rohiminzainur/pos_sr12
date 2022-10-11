@extends('layouts.master')

@section('title')
    Transaction Sale
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Transaction Sale</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="alert alert-success alert-dismissible">
                        <i class="fa fa-check icon">
                            Data Transaction Finish.
                        </i>
                    </div>
                </div>
                <div class="box-footer">
                    @if ($setting->type_nota == 1)
                        <button class="btn btn-warning"
                            onclick="notaSmall('{{ route('transactions.nota_small') }}', 'Nota Small')">Print Ulang
                            Nota</button>
                    @else
                        <button class="btn btn-warning"
                            onclick="notaBig('{{ route('transactions.nota_big') }}', 'Nota PDF')">Print Ulang Nota</button>
                    @endif
                    <a href="{{ route('transactions.new') }}" class="btn btn-primary">Transaction New</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Tambahkan untuk delete cookie innerHeight terlebih dahulu
        document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

        function notaSmall(url, title) {
            popupCenter(url, title, 635, 500);
        }

        function notaBig(url, title) {
            popupCenter(url, title, 900, 675);
        }

        function popupCenter(url, title, w, h) {

            const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
            const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;

            const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document
                .documentElement.clientWidth : screen.width;
            const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document
                .documentElement.clientHeight : screen.height;

            const systemZoom = width / window.screen.availWidth;
            const left = (width - w) / 2 / systemZoom + dualScreenLeft
            const top = (height - h) / 2 / systemZoom + dualScreenTop
            const newWindow = window.open(url, title,
                `scrollbars=yes, width=${w / systemZoom}, height=${h / systemZoom}, top=${top}, left=${left}`
            )

            if (window.focus) newWindow.focus();
        }
    </script>
@endpush
