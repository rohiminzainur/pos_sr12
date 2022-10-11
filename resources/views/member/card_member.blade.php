<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Card Member</title>
    <style>
        .box {
            position: relative;
        }

        .card {
            width: 85.60mm;
        }

        .logo {
            position: absolute;
            top: 3pt;
            right: 0pt;
            font-size: 16pt;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: #fff !important;
        }

        .logo p {
            text-align: left;
            margin-right: 207pt;
            width: 40px;
            height: 40px;
            right: 160px;
        }

        .logo img {
            position: absolute;
            margin-top: 8pt;
            width: 60px;
            height: 50px;
            right: 16pt;
        }

        .member {
            position: absolute;
            top: 45pt;
            right: 32%;
            font-size: 12pt;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: #fff !important;
        }

        .name {
            position: absolute;
            top: 115pt;
            right: 16pt;
            font-size: 12pt;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: #fff !important;
        }

        .phone_number {
            position: absolute;
            margin-top: 10pt;
            right: 16pt;
            color: #fff;
        }

        .barcode {
            position: absolute;
            top: 105pt;
            left: .860rem;
            border: 1px solid #fff;
            padding: .5px;
            background: #fff;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <section style="border: 1px solid rgb(140, 140, 140);">
        <table width="100%">
            @foreach ($datamember as $key => $data)
                <tr>
                    @foreach ($data as $item)
                        <td class="text-center">
                            <div class="box">
                                <img src="{{ public_path($setting->path_member_card) }}" alt="card" width="100%">
                                <div class="logo">
                                    <p>{{ $setting->company_name }}</p>
                                    <img src="{{ public_path($setting->path_logo) }}" alt="logo">
                                </div>
                                <div class="member">
                                    <h1><b>MEMBER</b></h1>
                                </div>
                                <div class="name">
                                    {{ $item->name }}
                                </div>
                                <div class="phone_number">{{ $item->phone_number }}</div>
                                <div class="barcode text-left">
                                    <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG("$item->code_member", 'QRCODE') }}"
                                        alt="qrcode" height="45" width="45">
                                </div>
                            </div>
                        </td>
                        @if (count($datamember) == 1)
                            <td class="text-center"style="width: 50%;"></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </table>
    </section>
</body>

</html>
