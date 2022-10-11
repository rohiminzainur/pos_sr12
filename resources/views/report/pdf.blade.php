<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report Income</title>
    <link rel="stylesheet" href="{{ asset('AdminLTE-2/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
</head>

<body>
    <h3 class="text-center">Report Income</h3>
    <h4 class="text-center">
        Date {{ tanggal_indonesia($start, false) }}
        s/d
        Date {{ tanggal_indonesia($end, false) }}
    </h4>

    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Date</th>
                <th>Sale</th>
                <th>Purchase</th>
                <th>Expense</th>
                <th>Income</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    @foreach ($row as $col)
                        <td>{{ $col }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <script src="{{ asset('AdminLTE-2/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
</body>

</html>
