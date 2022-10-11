<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota PDF</title>
    <style>
        table td {
            font-size: 14px;
        }

        table.data td,
        table.data th {
            border: 1px solid #ccc;
            padding: 5px;
        }

        table.data {
            border-collapse: collapse;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <table width="100%">
        <tr>
            <td rowspan="4" width="60%">
                <img src="{{ asset($setting->path_logo) }}" alt="{{ $setting->path_logo }}" width="120">
                <br><br>
                {{ $setting->address }}
                <br>
                <br>
            </td>
            <td>Date</td>
            <td>: {{ tanggal_indonesia(date('Y-m-d')) }}</td>
        </tr>
        <tr>
            <td>Code Member</td>
            <td>: {{ $sale->member->code_member ?? '' }}</td>
        </tr>
    </table>
    <table class="data" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Code</th>
                <th>Name</th>
                <th>Unit Price</th>
                <th>Total</th>
                <th>Discount</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->product->code_product }}</td>
                    <td class="text-right">{{ format_uang($item->selling_price) }}</td>
                    <td class="text-right">{{ format_uang($item->total) }}</td>
                    <td class="text-right">{{ $item->discount }}</td>
                    <td class="text-right">{{ format_uang($item->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-right"><b>Total Price</b></td>
                <td class="text-right"><b>{{ format_uang($sale->total_price) }}</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Discount</b></td>
                <td class="text-right"><b>{{ format_uang($sale->discount) }}</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Total Paid</b></td>
                <td class="text-right"><b>{{ format_uang($sale->paid) }}</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Received</b></td>
                <td class="text-right"><b>{{ format_uang($sale->received) }}</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Back Money</b></td>
                <td class="text-right"><b>{{ format_uang($sale->received - $sale->paid) }}</b></td>
            </tr>
        </tfoot>
    </table>

    <table width="100%">
        <tr>
            <td><b>Terimakasih telah berbelanja dan sampai jumpa</b></td>
            <td class="text-center">
                <h3>Cashier</h3>
                <br>
                <br>
                <br>
                {{ auth()->user()->name }}
            </td>
        </tr>
    </table>
</body>

</html>
