@extends('layouts.master')

@section('content')

    <script type="text/javascript">
        $(function () {
            $('#name').focus();
        });
    </script>

    @if ($editMode == true)
        {!! Form::model($itemLookup, ['route' => ['inventory.update', $itemLookup->id]]) !!}
    @else
        {!! Form::open(['route' => 'inventory.store']) !!}
    @endif

    <section class="content-header">
        <h1 class="pull-left">
            {{ trans('fi.inventory_form') }}
        </h1>
        <div class="pull-right">
            <button class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('fi.save') }}</button>
        </div>
        <div class="clearfix"></div>
    </section>

    <section class="content">

        @include('layouts._alerts')

	@if ($editMode == true)
	<br/>
	<h4>Barcode : </h4>
	<label style="text-align:center;">
	<img src="{{asset('assets/barcode/inventory'.$itemLookup->id.'-barcode.png')}}" />
	<br>
	PRD-{{$itemLookup->id}}</label>
	<br/><br/>
    	@endif


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
                            <label class="">{{ trans('fi.description') }}: </label>
                            {!! Form::textarea('description', null, ['id' => 'description', 'class' => 'form-control']) !!}
                        </div>
			</div>

			<div class="col-lg-4">
                        <div class="form-group">
                            <label class="">{{ trans('fi.price') }}: </label>
                            {!! Form::text('price', (($editMode) ? $itemLookup->formatted_numeric_price: null), ['id' => 'price', 'class' => 'form-control']) !!}
                        </div>
			</div>

			<div class="col-lg-4">
                        <div class="form-group">
                            <label class="">{{ trans('fi.tax_1') }}: </label>
                            {!! Form::select('tax_rate_id', $taxRates, null, ['class' => 'form-control']) !!}
                        </div>
			</div>

                        <div class="col-lg-4">
                        <div class="form-group">
                            <label class="">{{ trans('fi.total_q') }}: </label>
                            {!! Form::text('total', null, ['id' => 'total', 'class' => 'form-control']) !!}
                        </div>
			</div>


                       <!-- <div class="form-group">
                            <label class="">{{ trans('fi.tax_2') }}: </label>
                            {!! Form::select('tax_rate_2_id', $taxRates, null, ['class' => 'form-control']) !!}
                        </div>-->

			<div class="col-lg-6">
			<div class="form-group">
                            <label class="">Category: </label>
			    {!! Form::select('category',$InventoryCategory,null, ['id' => 'category','class'=>'form-control']) !!}
                        </div>
                        </div>

			<div class="col-lg-6">
                        <div class="form-group">
                            <label class="">Sub Category: </label>
			    {!! Form::select('sub-category',$InventorySubCategory,null, ['id' => 'sub-category','class'=>'form-control']) !!}
                        </div>
			</div>
                        
			<div class="col-lg-6">
                        <div class="form-group">
                            <label class="">Product Name For Customer: </label>
                            {!! Form::text('product-name-customer', null, ['id' => 'product-name-customer', 'class' => 'form-control']) !!}
                        </div>
                        </div>

			<div class="col-lg-6">
                        <div class="form-group">
                            <label class="">Product Name For Employee: </label>
                            {!! Form::text('product-name-employee', null, ['id' => 'product-name-employee', 'class' => 'form-control']) !!}
                        </div>
                        </div>

			<div class="col-lg-6">
			<div class="form-group">
                            <label class="">Location: </label>
			    {!! Form::select('location',$InventoryLocation,null, ['id' => 'location','class'=>'form-control']) !!}
                        </div>
                        </div>

			<div class="col-lg-6">
                        <div class="form-group">
                            <label class="">Item Location: </label>
			    {!! Form::select('item-location',$InventoryItemLocation,null, ['id' => 'item-location','class'=>'form-control']) !!}
                        </div>
			</div>

                        
			<div class="col-lg-6">
                        <div class="form-group">
                            <label class="">Purchase Price: </label>
                            {!! Form::text('purchase-price', null, ['id' => 'purchase-price', 'class' => 'form-control']) !!}
                        </div>
                        </div>

			<div class="col-lg-6">
                        <div class="form-group">
                            <label class="">Purchase Date: </label>
                            {!! Form::date('purchase-date', null, ['id' => 'purchase-date', 'class' => 'form-control']) !!}
                        </div>
                        </div>

			<div class="col-lg-4">
                        <div class="form-group">
                            <label class="">Height: </label>
                            {!! Form::text('height', null, ['id' => 'height', 'class' => 'form-control']) !!}
                        </div>
                        </div>

			<div class="col-lg-4">
                        <div class="form-group">
                            <label class="">Length: </label>
                            {!! Form::text('length', null, ['id' => 'length', 'class' => 'form-control']) !!}
                        </div>
                        </div>

			<div class="col-lg-4">
                        <div class="form-group">
                            <label class="">Width: </label>
                            {!! Form::text('width', null, ['id' => 'width', 'class' => 'form-control']) !!}
                        </div>
                        </div>

			<div class="col-lg-4">
                        <div class="form-group">
                            <label class="">Color: </label>
			    {!! Form::select('color',$InventoryColor,null, ['id' => 'color','class'=>'form-control']) !!}
                        </div>
			</div>

			<div class="col-lg-4">
                        <div class="form-group">
                            <label class="">Style: </label>
			    {!! Form::select('style',$InventoryStyle,null, ['id' => 'color','class'=>'form-control']) !!}
                        </div>
			</div>

			<div class="col-lg-4">
                        <div class="form-group">
                            <label class="">Placement: </label>
                            {!! Form::text('placement', null, ['id' => 'placement', 'class' => 'form-control']) !!}
                        </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {!! Form::close() !!}

<script>
	let sub_category_selector  = {!! $subCategorySelector !!}
	document.getElementById('category').onchange = function(){

		let newOptions = '';
		if (sub_category_selector.filter(e => e.name === document.getElementById('category').value).length > 0) {
  			/* vendors contains the element we're looking for */
			sub_category_selector.filter(e => e.name === document.getElementById('category').value)[0].inventory_sub_category.forEach((item)=>{
				newOptions+=`<option value="${item.name}">${item.name}</option>`
			})
			document.getElementById('sub-category').innerHTML = newOptions;
		}else{
		 document.getElementById('sub-category').innerHTML = `<option></option>`;
		}
	};
</script>

<script>
	let item_location_selector  = {!! $itemLocationSelector !!}
	document.getElementById('location').onchange = function(){

		let newOptions = '';
		if (item_location_selector.filter(e => e.name === document.getElementById('location').value).length > 0) {
  			/* vendors contains the element we're looking for */
			item_location_selector.filter(e => e.name === document.getElementById('location').value)[0].inventory_item_location.forEach((item)=>{
				newOptions+=`<option value="${item.name}">${item.name}</option>`
			})
			document.getElementById('item-location').innerHTML = newOptions;
		}else{
		 document.getElementById('item-location').innerHTML = `<option></option>`;
		}
	};
</script>


@stop
