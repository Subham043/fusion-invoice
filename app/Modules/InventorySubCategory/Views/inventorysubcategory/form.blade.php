@extends('layouts.master')

@section('content')

    <script type="text/javascript">
        $(function () {
            $('#name').focus();
        });
    </script>

    @if ($editMode == true)
        {!! Form::model($itemLookup, ['route' => ['inventory_sub_category.update', $itemLookup->id]]) !!}
    @else
        {!! Form::open(['route' => 'inventory_sub_category.store']) !!}
    @endif

    <section class="content-header">
        <h1 class="pull-left">
            Inventory Sub-Category Form
        </h1>
        <div class="pull-right">
            <button class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('fi.save') }}</button>
        </div>
        <div class="clearfix"></div>
    </section>

    <section class="content">

        @include('layouts._alerts')

        <div class="row">

            <div class="col-md-12">

                <div class="box box-primary">

                    <div class="box-body row">
	
			<div class="col-lg-12">
                        <div class="form-group">
                            <label class="">{{ trans('fi.name') }}: </label>
                            {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control']) !!}
                        </div>
			</div>

			<div class="col-lg-12">
                        <div class="form-group">
                            <label class="">Inventory Category: </label>
                            {!! Form::select('inventory_category_id',$InventoryCategory,null, ['id' => 'inventory_category_id','class'=>'form-control']) !!}
                        </div>
			</div>


                    </div>

                </div>

            </div>

        </div>

    </section>

    {!! Form::close() !!}
@stop
