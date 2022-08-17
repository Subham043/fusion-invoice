@extends('reports.layouts.master')

@section('content')

    <h1 style="margin-bottom: 0;">Event Statement</h1>
    
    <h3 style="margin-top: 0;">{{ $results['from_date'] }} - {{ $results['to_date'] }}</h3>
    <br>
    <table class="alternate">
        <thead>
        <tr>
            <th>{{ trans('fi.event_date') }}</th>
            <th style="text-transform:capitalize;">{{ $results['type'] }}</th>
            <th>{{ trans('fi.client') }}</th>
            <th class="amount">{{ trans('fi.subtotal') }}</th>
            <th class="amount">{{ trans('fi.discount') }}</th>
            <th class="amount">{{ trans('fi.tax') }}</th>
            <th class="amount">{{ trans('fi.total') }}</th>
            <th class="amount">{{ trans('fi.paid') }}</th>
            <th class="amount">{{ trans('fi.balance') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($results['records'] as $result)
            <tr>
                <td>{{ $result['formatted_invoice_date'] }}</td>
                <td>{{ $result['number'] }}</td>
                <td>{{ $result['summary'] }}</td>
                <td class="amount">{{ $result['formatted_subtotal'] }}</td>
                <td class="amount">{{ $result['formatted_discount'] }}</td>
                <td class="amount">{{ $result['formatted_tax'] }}</td>
                <td class="amount">{{ $result['formatted_total'] }}</td>
                <td class="amount">{{ $result['formatted_paid'] }}</td>
                <td class="amount">{{ $result['formatted_balance'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@stop