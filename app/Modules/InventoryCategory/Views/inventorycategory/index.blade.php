@extends('layouts.master')

@section('content')

    <section class="content-header">
        <h1 class="pull-left">
            {{ trans('fi.inventory') }} Category
        </h1>
	@if ($editMode == true)
        <div class="pull-right">
            <a href="{{ route('inventory_category.index') }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ trans('fi.new') }}</a>
        </div>
	@endif
        <div class="clearfix"></div>
    </section>

    <section class="content">

        @include('layouts._alerts')

	@if ($editMode == true)
        	{!! Form::model($itemLookup, ['route' => ['inventory_category.update', $itemLookup->id]]) !!}
    	@else
        	{!! Form::open(['route' => 'inventory_category.store']) !!}
    	@endif

	<div class="row">

            <div class="col-md-12">

                <div class="box box-primary">

                    <div class="box-body row">
	
			<div class="col-lg-12">
                        <div class="form-group">
                            <label class="">Inventory Category: </label>
                            {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control']) !!}
                        </div>
			<div class="pull-right">
            			<button class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('fi.save') }}</button>
        		</div>
			</div>


                    </div>

                </div>

            </div>

        </div>

	{!! Form::close() !!}


        <div class="row">

            <div class="col-xs-12">

                <div class="box box-primary">

                    <div class="box-body no-padding">
                        <table class="table table-hover">

                            <thead>
                            <tr>
                                <th>{!! Sortable::link('name', trans('fi.name')) !!}</th>
                                <th>{{ trans('fi.options') }}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($itemLookups as $itemLookup)
                                <tr>
                                    <td>{{ $itemLookup->name }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                {{ trans('fi.options') }} <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li><a href="{{ route('inventory_category.edit', [$itemLookup->id]) }}"><i class="fa fa-edit"></i> {{ trans('fi.edit') }}</a></li>
                                               <!-- <li><a href="{{ route('inventory_category.delete', [$itemLookup->id]) }}" onclick="return confirm('{{ trans('fi.delete_record_warning') }}');"><i class="fa fa-trash-o"></i> {{ trans('fi.delete') }}</a></li> -->
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
