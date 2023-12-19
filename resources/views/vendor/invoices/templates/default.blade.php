<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $invoice->name }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style type="text/css" media="screen">
        html {
            font-family: sans-serif;
            line-height: 1.15;
            margin: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: left;
            background-color: #fff;
            font-size: 10px;
            margin: 36pt;
        }

        h4 {
            margin-top: 0;
            margin-bottom: 0.5rem;
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem;
        }

        strong {
            font-weight: bolder;
        }

        img {
            vertical-align: middle;
            border-style: none;
        }

        table {
            border-collapse: collapse;
        }

        th {
            text-align: inherit;
        }

        h4,
        .h4 {
            margin-bottom: 0.5rem;
            font-weight: 500;
            line-height: 1.2;
        }

        h4,
        .h4 {
            font-size: 1.5rem;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
        }

        .table.table-items td {
            border-top: 2px solid #dee2e6;
            border-bottom: 2px solid #dee2e6;

        }

        .topBottomBorder {
            border-top: 2px solid #dee2e6;
            border-bottom: 2px solid #dee2e6;

        }

        .topBottomBorderMonth {
            border-top: 2px solid #dee2e6;
            border-bottom: 2px solid #dee2e6;
            text-align: right;

        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;

        }

        .mt-5 {
            margin-top: 3rem !important;
        }

        .pr-0,
        .px-0 {
            padding-right: 0 !important;
        }

        .pl-0,
        .px-0 {
            padding-left: 0 !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-uppercase {
            text-transform: uppercase !important;
        }

        * {
            font-family: "DejaVu Sans";
        }

        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        table,
        th,
        tr,
        td,
        p,
        div {
            line-height: 1.1;
        }

        .party-header {
            font-size: 1.5rem;
            font-weight: 400;
        }

        .total-amount {
            font-size: 12px;
            font-weight: 700;
        }

        .border-0 {
            border: none !important;
        }

        .cool-gray {
            color: #6B7280;
        }

        .headText {
            font-weight: 500;
            color: rgb(31 71 132);
            font-size: 1.25rem;
            line-height: 1.75rem;
            font-family: ui-sans-serif,
        }
    </style>
</head>
@php
    use carbon\carbon;
    $y = 0;
    $k = 0;
@endphp

<body>
    {{-- Header --}}
    @if ($invoice->logo)
        <img src="{{ $invoice->getLogo() }}" alt="logo" height="100">
    @endif

    {{-- Seller - Buyer --}}
    <h2 class="text-4xl  text-center py-6 font-extrabold dark:text-white">Planning des permanences pour les Samedis du
        {{ carbon::parse($invoice->seller->currentRecord->date_debut)->format('d-m-Y') }} au
        {{ carbon::parse($invoice->seller->currentRecord->date_fin)->format('d-m-Y') }}</h2>
    <h2 class="text-4xl  text-center py-2 font-extrabold dark:text-white">SPT</h2>
    <table class="table">
        <thead>
            <tr>
                <th class="border-0 pl-0 party-header" width="48.5%">
                    Département
                </th>
                <th class="border-0" width="5%"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="px-0">
                    <p class="seller-name">
                        <strong>{{ $invoice->seller->nom_departements }}</strong>
                    </p>
                </td>
                <td class="border-0"></td>
                <td class="px-0">
                </td>
            </tr>
        </tbody>
    </table>

    {{-- Table --}}
    <table class="table table-items">
        <thead>
            <tr class="topBottomBorder">
                <th scope="col" class="topBottomBorder">Dates</th>
                @foreach ($invoice->seller->services as $service)
                    <th scope="col">{{ $service->nom_service }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{-- Items --}}
            @foreach ($invoice->seller->months as $value => $month)
                <tr>
                    <th class="topBottomBorderMonth">
                        {{$month}}
                    </th>
                    @for ($i = 0; $i < count($invoice->seller->services); $i++)
                        <th class="topBottomBorderMonth">
                        </th>
                    @endfor
                </tr>
                @foreach ($invoice->seller->days as $day)
                    @if (carbon::parse($day)->translatedFormat('F') == $month)
                        <tr class="topBottomBorder">
                            <th class="topBottomBorder">
                                {{ carbon::parse($day)->translatedFormat('l, d F Y') }}
                            </th>
                            @for ($k = 0; $k < $invoice->seller->services->count(); $k++)
                                <td class="topBottomBorder">
                                    <span>
                                        
                                        {{ $invoice->seller->userNames[$y + $k] }}
                                    </span>
                                </td>
                            @endfor
                            @php
                                $y = $y + $k;
                            @endphp
                        </tr>
                    @elseif($loop->last)
                    @break
                @endif
            @endforeach
        @endforeach
    </tbody>
</table>

</td>
</tr>
<br>
<p class="text-right">
    <strong>{!! $invoice->notes !!}</strong>
</p>


<p class="text-right"> <strong>Lomé, le {{ $invoice->getDate() }}</strong></p>


<script type="text/php">
            if (isset($pdf) && $PAGE_COUNT > 1) {
                $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("Verdana");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width);
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>
</body>

</html>
