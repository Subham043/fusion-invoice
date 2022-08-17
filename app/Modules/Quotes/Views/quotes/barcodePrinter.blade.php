<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Barcode Printer</title>
    <link rel="stylesheet" media="print" href="{{ asset('assets/plugins/chosen/print.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">

    <style>
        @page {
            size:A4;
            margin: 25px;
        }
        
        .wrapper {
        	overflow: visible !important;
        }
        
        section.content-header {
        	padding: 13px;
        	background: #17abe6 !important;
        	margin-bottom:30px;
        }
        
        .box-header {
        	background: white;
        	position: sticky;
        	top:95px;
        	z-index: 100;
        	border-bottom: 1px solid #888;
        }


        body {
            color: #001028;
            background: #FFFFFF;
            font-family : DejaVu Sans, Helvetica, sans-serif;
            font-size: 12px;
            margin-bottom: 50px;
        }

        a {
            color: #5D6975;
            border-bottom: 1px solid currentColor;
            text-decoration: none;
        }

        h1 {
            color: #5D6975;
            font-size: 2.8em;
            line-height: 1.4em;
            font-weight: bold;
            margin: 0;
        }

        table {
            width: 100%;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        th, .section-header {
            padding: 5px 10px;
            color: #5D6975;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: normal;
            text-align: left;
        }

        td {
            padding: 10px;
        }

        table.alternate tr:nth-child(odd) td {
            background: #F5F5F5;
        }

        th.amount, td.amount {
            text-align: right;
        }

        .info {
            color: #5D6975;
            font-weight: bold;
        }

        .terms {
            padding: 10px;
        }

        .footer {
            position: fixed;
            height: 50px;
            width: 100%;
            bottom: 0;
            text-align: center;
        }
        
        #cp-logo{
            width: 30%;
            object-fit: contain;
        }

    </style>
</head>
<body>
    
    <section class="content-header" id="print-section">

    <div class="pull-right">
        <button  onclick="window.print();"
           class="btn btn-default"><i class="fa fa-print"></i> {{ trans('fi.print') }}</button>

    </div>

    <div class="clearfix"></div>
</section>

<br>
<table class="alternate">
    <tbody>
    @foreach ($quotes as $item)
	@if(file_exists(public_path('assets/barcode/').('quote-item-checklist-'.$item->id.'-barcode.png')))
        <tr>
            <td><label style="text-align:center;">
	   <img src="{{asset('assets/barcode/quote-item-checklist-'.$item->id.'-barcode.png')}}" />
	   <br>
	   CHK-{{$item->id}}</label></td>
        </tr>
	@endif
    @endforeach

    </tbody>
</table>

<div class="pull-right">
     {!! $quotes->appends(request()->except('page'))->render() !!}
</div>





</body>
</html>
