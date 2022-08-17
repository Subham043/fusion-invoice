@extends('layouts.master')

@section('content')

    <section class="content-header">
        <h1 class="pull-left">
            {{ trans('fi.inventory') }}
        </h1>
        <div class="pull-right">
            <a href="{{ route('inventory.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ trans('fi.new') }}</a>
        </div>
        <div class="clearfix"></div>
    </section>

    <section class="content">

        @include('layouts._alerts')

        <div class="row">

            <div class="col-xs-12">

                <div class="box box-primary">

                    <div class="box-body no-padding">
                        <table class="table table-hover">

                            <thead>
                            <tr>
                                <th>{!! Sortable::link('name', trans('fi.name')) !!}</th>
                                <th>{!! Sortable::link('category', 'Category') !!}</th>
				<th>{!! Sortable::link('sub-category', 'Sub-Category') !!}</th>
				<th>{!! Sortable::link('color', 'Color') !!}</th>
				<th>{!! Sortable::link('style', 'Style') !!}</th>
				<th>{!! Sortable::link('location', 'Location') !!}</th>
                                <th>{!! Sortable::link('price', trans('fi.price')) !!}</th>
                                <th>{!! Sortable::link('total', 'Quantity') !!}</th>
                               <th>{{ trans('fi.options') }}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($itemLookups as $itemLookup)
                                <tr>
                                    <td>{{ $itemLookup->name }}</td>
                                    <td>{{ $itemLookup->category }}</td>
				    <td>{{ $itemLookup->getAttribute('sub-category') }}</td>
				    <td>{{ $itemLookup->color }}</td>
 				    <td>{{ $itemLookup->style }}</td>
					<td>{{ $itemLookup->location }}</td>
                                    <td>{{ $itemLookup->formatted_price }}</td>
                                    <td>{{ $itemLookup->total }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                {{ trans('fi.options') }} <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li><a href="{{ route('inventory.edit', [$itemLookup->id]) }}"><i class="fa fa-edit"></i> {{ trans('fi.edit') }}</a></li>
                                               <!--<li><a href="{{ route('inventory.delete', [$itemLookup->id]) }}" onclick="return confirm('{{ trans('fi.delete_record_warning') }}');"><i class="fa fa-trash-o"></i> {{ trans('fi.delete') }}</a></li>--> 
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>

                </div>

                <div class="pull-right">
                    {!! $itemLookups->appends(request()->except('page'))->render() !!}
                </div>

            </div>

        </div>

    </section>

@stop
