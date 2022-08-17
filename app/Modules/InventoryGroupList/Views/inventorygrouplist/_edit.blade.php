@include('invoices._js_edit')

<section class="content-header">
    <h1 class="pull-left">Inventory Group List</h1>


    <div class="pull-right">
        
        <div class="btn-group">
            @if ($returnUrl)
                <a href="{{ $returnUrl }}" class="btn btn-default"><i
                        class="fa fa-backward"></i> {{ trans('fi.back') }}</a>
            @endif
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-primary btn-update-invoice"><i
                    class="fa fa-save"></i> {{ trans('fi.save') }}</button>
            
        </div>

    </div>

    <div class="clearfix"></div>
</section>

<section class="content">

    <div class="row">

        <div class="col-lg-10">

            <div id="form-status-placeholder"></div>

			<br/>
			<h4>Barcode : </h4>
			<label style="text-align:center;">
			<img src="{{asset('assets/barcode/inventory-group-list-'.$invoice->id.'-barcode.png')}}" />
			<br>
			GRP-{{$invoice->id}}</label>
			<br/><br/>



            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Name</h3>
                        </div>
                        <div class="box-body">
                            {!! Form::text('name', $invoice->name, ['id' => 'name', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>

	<div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Description</h3>
                        </div>
                        <div class="box-body">
                            {!! Form::textarea('summary', $invoice->summary, ['id' => 'summary', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">

                <div class="col-sm-12 table-responsive" style="overflow-x: visible;">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">{{ trans('fi.items') }}</h3>

                            <div class="box-tools pull-right">
                                
                                <button class="btn btn-primary btn-sm" id="btn-add-item"><i
                                        class="fa fa-plus"></i> {{ trans('fi.add_item') }}</button>
                            </div>
                        </div>

                        <div class="box-body">
                            <table id="item-table" class="table table-hover">
                                <thead>
                                <tr>
                                    <th style="width: 20%;">{{ trans('fi.product') }}</th>
                                    <th style="width: 25%;">{{ trans('fi.description') }}</th>
                                    <th style="width: 10%;">{{ trans('fi.qty') }}</th>
                                    <th style="width: 10%;">{{ trans('fi.price') }}</th>
                                    <!--<th style="width: 10%;">{{ trans('fi.tax_1') }}</th>-->
                                   <!-- <th style="width: 10%;">{{ trans('fi.tax_2') }}</th>-->
                                    <!--<th style="width: 10%; text-align: right; padding-right: 25px;">{{ trans('fi.total') }}</th>-->
                                    <th style="width: 5%;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr id="new-item" style="display: none;">
                                    <td>
                                        {!! Form::hidden('inventory_group_list_id', $invoice->id) !!}
                                        {!! Form::hidden('', $invoice->id, ['class'=> 'inventory_group_list_id']) !!}
                                        {!! Form::hidden('inventory_id', '', ['class'=> 'inventory_id']) !!}
                                        <!--{!! Form::text('name', null, ['class' => 'form-control']) !!}-->
                                        {!! Form::select('name', $inventory, "null", ['class' => 'form-control']) !!}
                                        <br>
                                        <input type="hidden" name="save_item_as_lookup" tabindex="999">
                                    </td>
                                    <td>{!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 1]) !!}</td>
                                    <td class="info-td">
                                        {!! Form::text('quantity', null, ['class' => 'form-control']) !!}
                                        
                                    </td>
                                    <td>{!! Form::text('price', null, ['class' => 'form-control']) !!}</td>
                                    <!--<td>{!! Form::select('tax_rate_id', $taxRates, config('fi.itemTaxRate'), ['class' => 'form-control']) !!}</td>-->
                                   <!-- <td>{!! Form::select('tax_rate_2_id', $taxRates, config('fi.itemTax2Rate'), ['class' => 'form-control']) !!}</td>-->
                                    <!--<td></td>-->
                                    <td></td>
                                </tr>
                                @foreach ($invoice->items as $item)
                                    <tr class="item" id="tr-item-{{ $item->id }}">
                                        <td>
                                            {!! Form::hidden('inventory_group_list_id', $invoice->id) !!}
                                            {!! Form::hidden('', $invoice->id, ['class'=> 'inventory_group_list_id']) !!}
                                            {!! Form::hidden('id', $item->id) !!}
                                            {!! Form::hidden('inventory_id', $item->inventory_id, ['class'=> 'inventory_id']) !!}
                                            <!--{!! Form::text('name', $item->name, ['class' => 'form-control item-lookup']) !!}-->
                                            {!! Form::select('name', $inventory, $item->name, ['class' => 'form-control']) !!}
                                        </td>
                                        <td>{!! Form::textarea('description', $item->description, ['class' => 'form-control', 'rows' => 1]) !!}</td>
                                        <td class="info-td">
                                            {!! Form::text('quantity', $item->formatted_quantity, ['class' => 'form-control']) !!}
                                        </td>
                                        <td>{!! Form::text('price', $item->formatted_numeric_price, ['class' => 'form-control']) !!}</td>
                                        <!--<td>{!! Form::select('tax_rate_id', $taxRates, $item->tax_rate_id, ['class' => 'form-control']) !!}</td>-->
                                       <!-- <td>{!! Form::select('tax_rate_2_id', $taxRates, $item->tax_rate_2_id, ['class' => 'form-control']) !!}</td>-->
                                        <td>
                                            <a class="btn btn-xs btn-default btn-delete-inventory-group-list-item" href="javascript:void(0);"
                                               title="{{ trans('fi.delete') }}" data-item-id="{{ $item->id }}">
                                                <i class="fa fa-times"></i>
                                            </a>
                                            
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="box-body">
                            <label><a href="{{ route('inventory.create') }}" target="_blank" class="btn btn-primary btn-sm" ><i
                                        class="fa fa-plus"></i> Add new item in inventory</a></label>
                        </div>
                    </div>
                </div>

            </div>

            
        </div>

        <div class="col-lg-2" style="position:sticky;top:125px;">

            <div id="div-totals">
                @include('inventorygrouplist._edit_totals')
            </div>

            
        </div>

    </div>

</section>
