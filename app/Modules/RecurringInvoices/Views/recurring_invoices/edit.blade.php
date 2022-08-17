@extends('layouts.master')

@section('javascript')

    @include('layouts._datepicker')
    @include('layouts._typeahead')
    @include('inventory._js_inventory')

@stop

@section('content')

    <div id="div-recurring-invoice-edit">

        @include('recurring_invoices._edit')

    </div>

@stop
