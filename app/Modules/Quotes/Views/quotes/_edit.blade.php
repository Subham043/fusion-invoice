@include('quotes._js_edit')

<section class="content-header">
    <h1 class="pull-left">{{ trans('fi.quote') }} #{{ $quote->number }}</h1>

    @if ($quote->viewed)
        <span style="margin-left: 10px;" class="label label-success">{{ trans('fi.viewed') }}</span>
    @else
        <span style="margin-left: 10px;" class="label label-default">{{ trans('fi.not_viewed') }}</span>
    @endif

    @if ($quote->invoice)
        <span class="label label-info"><a href="{{ route('invoices.edit', [$quote->invoice_id]) }}" style="color: inherit;">{{ trans('fi.converted_to_invoice') }} #{{ $quote->invoice->number }}</a></span>
    @endif

    <div class="pull-right">
        <a href="{{ route('quotes.pdf', [$quote->id]) }}" target="_blank" id="btn-pdf-quote"
           class="btn btn-default"><i class="fa fa-print"></i> {{ trans('fi.pdf') }}</a>
        @if (config('fi.mailConfigured'))
            <a href="javascript:void(0)" id="btn-email-quote" class="btn btn-default email-quote"
               data-quote-id="{{ $quote->id }}" data-redirect-to="{{ route('quotes.edit', [$quote->id]) }}"><i
                    class="fa fa-envelope"></i> {{ trans('fi.email') }}</a>
        @endif
        @if ($quote->quote_status_id >= "3" && $quote->invoice_id=="0" && $quote->user_id==Auth::id() )
        <a href="javascript:void(0)" id="btn-quote-to-invoice"
           class="btn btn-default"><i class="fa fa-check"></i> {{ trans('fi.quote_to_invoice') }}</a>
        @elseif ($quote->quote_status_id >= "3" && $quote->invoice_id!="0" && $quote->user_id==Auth::id() )
        <a href="{{ route('invoices.edit', [$quote->invoice_id]) }}"
           class="btn btn-default"><i class="fa fa-check"></i> {{ trans('fi.quote_to_invoice') }}</a>
           @endif

        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                {{ trans('fi.other') }} <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                <li><a href="javascript:void(0)" id="btn-copy-quote"><i
                            class="fa fa-copy"></i> {{ trans('fi.copy') }}</a></li>
                <li><a href="{{ route('clientCenter.public.quote.show', [$quote->url_key]) }}" target="_blank"><i
                            class="fa fa-globe"></i> {{ trans('fi.public') }}</a></li>
                <li class="divider"></li>
               <!-- <li><a href="{{ route('quotes.delete', [$quote->id]) }}"
                       onclick="return confirm('{{ trans('fi.delete_record_warning') }}');"><i
                            class="fa fa-trash-o"></i> {{ trans('fi.delete') }}</a></li> -->
            </ul>
        </div>

        <div class="btn-group">
            @if ($returnUrl)
                <a href="{{ $returnUrl }}" class="btn btn-default"><i
                        class="fa fa-backward"></i> {{ trans('fi.back') }}</a>
            @endif
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-primary btn-save-quote"><i
                    class="fa fa-save"></i> {{ trans('fi.save') }}</button>
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                <li><a href="#" class="btn-save-quote"
                       data-apply-exchange-rate="1">{{ trans('fi.save_and_apply_exchange_rate') }}</a></li>
            </ul>
        </div>

    </div>

    <div class="clearfix"></div>
</section>

<section class="content">

    <div class="row">

        <div class="col-lg-10">

            @include('layouts._alerts')

            <div id="form-status-placeholder"></div>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">{{ trans('fi.summary') }}</h3>
                        </div>
                        <div class="box-body">
                            {!! Form::text('summary', $quote->summary, ['id' => 'summary', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-sm-6" id="col-from">

                    @include('quotes._edit_from')

                </div>

                <div class="col-sm-6" id="col-to">

                    @include('quotes._edit_to')

                </div>

            </div>

            <div class="row">

                <div class="col-lg-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab-product-items" data-toggle="tab">Product Items</a></li>
                            <li><a href="#tab-group-items" data-toggle="tab">Group Items</a></li>
                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="tab-product-items">
				
				<div class="row">

                <div class="col-sm-12 table-responsive" style="overflow-x: visible;">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">{{ trans('fi.items') }}</h3>

                            <div class="box-tools pull-right">
                                <a href="{{ route('quotes.itemChecklistPrint', [$quote->id]) }}" target="_blank" id="btn-pdf-quote"
           class="btn btn-default"><i class="fa fa-print"></i> Item Checklist</a>
                                <button class="btn btn-primary btn-sm" id="btn-add-item"><i
                                        class="fa fa-plus"></i> {{ trans('fi.add_item') }}</button>
                            </div>
                        </div>

                        <div class="box-body">
                            <table id="item-table" class="table table-hover">
                                <thead>
                                <tr>
                                    <th style="width: 20%;">{{ trans('fi.product') }} <span style="color:red;">*</span></th>
                                    <th style="width: 25%;">{{ trans('fi.description') }}</th>
                                    <th style="width: 10%;">{{ trans('fi.qty') }} <span style="color:red;">*</span></th>
                                    <th style="width: 10%;">{{ trans('fi.price') }} <span style="color:red;">*</span></th>
                                    <th style="width: 10%;">{{ trans('fi.tax_1') }}</th>
                                   <!-- <th style="width: 10%;">{{ trans('fi.tax_2') }}</th>-->
                                    <th style="width: 10%; text-align: right; padding-right: 25px;">{{ trans('fi.total') }}</th>
                                    <th style="width: 5%;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr id="new-item" style="display: none;">
                                    <td>
                                        {!! Form::hidden('quote_id', $quote->id) !!}
                                        {!! Form::hidden('', $quote->event_date, ['class'=> 'eventDate']) !!}
                                        {!! Form::hidden('', $quote->invoice_id, ['class'=> 'invoiceId']) !!}
                                        {!! Form::hidden('', $quote->id, ['class'=> 'quoteId']) !!}
                                        {!! Form::hidden('id', '') !!}
                                        {!! Form::hidden('availableQuan', '') !!}
                                        {!! Form::hidden('inventory_id', '', ['class'=> 'inventory_id']) !!}
                                        <!--{!! Form::text('name', null, ['class' => 'form-control']) !!}-->
                                        {!! Form::select('name', $inventory, "null", ['class' => 'form-control']) !!}<br>
                                        <input type="hidden" name="save_item_as_lookup" tabindex="999">
                                    </td>
                                    <td>{!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 1]) !!}</td>
                                    <td class="info-td">
                                        {!! Form::text('quantity', null, ['class' => 'form-control']) !!}
                                        <div class="tooltip-custom"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                          <span class="tooltiptext">
                                              <table style="width:100%;">
                                                  <thead>
                                                      <tr style="width:100%">
                                                          <td style="text-align:left">Total Quantity :</td>
                                                          <td class="total-quan"></td>
                                                      </tr>
                                                      <tr style="width:100%">
                                                          <td style="text-align:left">Reserved Quantity :</td>
                                                          <td class="reserved-quan"></td>
                                                      </tr>
                                                      <tr style="width:100%">
                                                          <td style="text-align:left">Alloted Quantity :</td>
                                                          <td class="alloted-quan"></td>
                                                      </tr>
                                                      <tr style="width:100%">
                                                          <td style="text-align:left">Available Quantity :</td>
                                                          <td class="available-quan"></td>
                                                      </tr>
                                                  </thead>
                                              </table>
                                          </span>
                                        </div>
                                    </td>
                                    <td>{!! Form::text('price', null, ['class' => 'form-control']) !!}</td>
                                    <td>{!! Form::select('tax_rate_id', $taxRates, config('fi.itemTaxRate'), ['class' => 'form-control']) !!}</td>
                                   <!-- <td>{!! Form::select('tax_rate_2_id', $taxRates, config('fi.itemTax2Rate'), ['class' => 'form-control']) !!}</td>-->
                                    <td style="text-align: right; padding-right: 25px;" class="productTotalPrice"></td>
                                    <td>
					<a class="btn btn-xs btn-default btn-delete-invoice-group-item-unsaved" onclick="deleteUnsavedGroupItem()" href="javascript:void(0);"
                                               title="{{ trans('fi.delete') }}">
                                                <i class="fa fa-times"></i>
                                            </a>
				    </td>
                                </tr>
                                
                                @foreach ($quote->items as $item)
                                    <tr class="item" id="tr-item-{{ $item->id }}">
                                        <td>
                                            {!! Form::hidden('quote_id', $quote->id) !!}
                                            {!! Form::hidden('', $quote->event_date, ['class'=> 'eventDate']) !!}
                                            {!! Form::hidden('', $quote->invoice_id, ['class'=> 'invoiceId']) !!}
                                            {!! Form::hidden('', $quote->id, ['class'=> 'quoteId']) !!}
                                            {!! Form::hidden('id', $item->id) !!}
                                            {!! Form::hidden('availableQuan', $item->getAvailableQuantity()) !!}
                                            {!! Form::hidden('inventory_id', $item->inventory_id, ['class'=> 'inventory_id']) !!}
                                            <!--{!! Form::text('name', $item->name, ['class' => 'form-control item-lookup']) !!}-->
                                            {!! Form::select('name', $inventory, $item->name, ['class' => 'form-control']) !!}
                                        </td>
                                        <td>{!! Form::textarea('description', $item->description, ['class' => 'form-control', 'rows' => 1]) !!}</td>
                                        <td class="info-td">
                                            {!! Form::text('quantity', $item->formatted_quantity, ['class' => 'form-control']) !!}
                                            <div class="tooltip-custom"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                          <span class="tooltiptext">
                                              <table style="width:100%;">
                                                  <thead>
                                                      <tr style="width:100%">
                                                          <td style="text-align:left">Total Quantity :</td>
                                                         @if ($item->inventory()->count() > 0)
                                                                <td class="total-quan">{{$item->inventory->total}}</td>
                                                            @else
                                                                <td class="total-quan">0</td>
                                                            @endif
                                                      </tr>
                                                      <tr style="width:100%">
                                                          <td style="text-align:left">Reserved Quantity :</td>
                                                          <td class="reserved-quan">{{$item->getReservedQuantity()}}</td>
                                                      </tr>
                                                      <tr style="width:100%">
                                                          <td style="text-align:left">Alloted Quantity :</td>
                                                          <td class="alloted-quan">{{$item->getAllocatedQuantity()}}</td>
                                                      </tr>
                                                      <tr style="width:100%">
                                                          <td style="text-align:left">Available Quantity :</td>
                                                          <td class="available-quan">{{$item->getAvailableQuantity()}}</td>
                                                      </tr>
                                                  </thead>
                                              </table>
                                          </span>
                                        </div>
                                        </td>
                                        <td>{!! Form::text('price', $item->formatted_numeric_price, ['class' => 'form-control']) !!}</td>
                                        <td>{!! Form::select('tax_rate_id', $taxRates, $item->tax_rate_id, ['class' => 'form-control']) !!}</td>
                                       <!-- <td>{!! Form::select('tax_rate_2_id', $taxRates, $item->tax_rate_2_id, ['class' => 'form-control']) !!}</td>-->
                                        <td style="text-align: right; padding-right: 25px;">{{ $item->amount->formatted_subtotal }}</td>
                                        <td>
                                            <a class="btn btn-xs btn-default btn-delete-quote-item" href="javascript:void(0);"
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
                            <label><a href="{{ route('inventory.create') }}" class="btn btn-primary btn-sm" ><i
                                        class="fa fa-plus"></i> Add new item in inventory</a></label>
                        </div>
                    </div>
                </div>

            </div>
                              
                            </div>

                            <div class="tab-pane" id="tab-group-items">
                                
				<div class="row">

                <div class="col-sm-12 table-responsive" style="overflow-x: visible;">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Groups</h3>

                            <div class="box-tools pull-right">
                                <a href="{{ route('quotes.itemChecklistPrint', [$quote->id]) }}" target="_blank" id="btn-pdf-quote"
           class="btn btn-default"><i class="fa fa-print"></i> Item Checklist</a>

                                <button class="btn btn-primary btn-sm" id="btn-add-group-item"><i
                                        class="fa fa-plus"></i> Add Group Item</button>
                            </div>
                        </div>

                        <div class="box-body">
                            <table id="item-group-table" class="table table-hover">
                                <thead>
                                <tr>
                                    <th style="width: 20%;">Group <span style="color:red;">*</span></th>
                                    <th style="width: 55%;">{{ trans('fi.description') }}</th>
                                    <th style="width: 10%;">{{ trans('fi.qty') }} <span style="color:red;">*</span></th>
				    <th style="width: 10%;">Price <span style="color:red;">*</span></th>
                                    <th style="width: 10%; text-align: right; padding-right: 25px;">{{ trans('fi.total') }}</th>
                                    <th style="width: 5%;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr id="new-group-item" style="display: none;">
                                    <td>
                                        {!! Form::hidden('quote_id', $quote->id) !!}
                                        {!! Form::hidden('', $quote->event_date, ['class'=> 'eventDate']) !!}
                                        {!! Form::hidden('', $quote->invoice_id, ['class'=> 'invoiceId']) !!}
                                        {!! Form::hidden('', $quote->id, ['class'=> 'quoteId']) !!}
                                        {!! Form::hidden('id', '') !!}
                                        {!! Form::hidden('inventory_group_list_id', '', ['class'=> 'inventory_group_list_id']) !!}
                                        <!--{!! Form::text('name', null, ['class' => 'form-control']) !!}-->
                                        {!! Form::select('group_name', $inventory_group_list, "null", ['class' => 'form-control']) !!}
                                        <br>
                                    </td>
                                    <td>{!! Form::textarea('group_description', null, ['class' => 'form-control', 'rows' => 1]) !!}</td>
                                    <td class="info-td">
                                        {!! Form::text('group_quantity', null, ['class' => 'form-control']) !!}                                    
                                    </td>
                                    <td>{!! Form::text('group_price', null, ['class' => 'form-control']) !!}</td>
                                    <td style="text-align: right; padding-right: 25px;" class="groupTotalPrice"></td>
                                    <td>
						<a class="btn btn-xs btn-default btn-delete-invoice-group-item-unsaved" onclick="deleteUnsavedGroupItem()" href="javascript:void(0);"
                                               title="{{ trans('fi.delete') }}">
                                                <i class="fa fa-times"></i>
                                            </a>
				    </td>
                                </tr>
                                @foreach ($quote->groupitems as $item)
                                    <tr class="item_group" id="tr-group-item-{{ $item->id }}">
                                        <td>
                                            {!! Form::hidden('quote_id', $quote->id) !!}
                                            {!! Form::hidden('', $quote->event_date, ['class'=> 'eventDate']) !!}
                                            {!! Form::hidden('', $quote->invoice_id, ['class'=> 'invoiceId']) !!}
                                            {!! Form::hidden('', $quote->id, ['class'=> 'quoteId']) !!}
                                            {!! Form::hidden('id', $item->id) !!}
                                            {!! Form::hidden('inventory_group_list_id', $item->inventory_id, ['class'=> 'inventory_group_list_id']) !!}
                                            <!--{!! Form::text('name', $item->name, ['class' => 'form-control item-lookup']) !!}-->
                                            {!! Form::select('group_name', $inventory_group_list, $item->name, ['class' => 'form-control']) !!}
                                        </td>
                                        <td>{!! Form::textarea('group_description', $item->description, ['class' => 'form-control', 'rows' => 1]) !!}</td>
                                        <td class="info-td">
                                            {!! Form::text('group_quantity', $item->formatted_quantity, ['class' => 'form-control']) !!}
                                        </td>
                                        <td>{!! Form::text('group_price', $item->price, ['class' => 'form-control']) !!}</td>
                                        <td style="text-align: right; padding-right: 25px;">{{ $item->total }}</td>
                                        <td>
                                            <a class="btn btn-xs btn-default btn-delete-quote-group-item" href="javascript:void(0);"
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
                            <label><a href="{{ route('inventorygrouplist.create') }}" class="btn btn-primary btn-sm" ><i
                                        class="fa fa-plus"></i> Add new item in inventory group list</a></label>
                        </div>
                    </div>
                </div>

            </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <div class="row">

                <div class="col-lg-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab-additional" data-toggle="tab">{{ trans('fi.additional') }}</a></li>
                            <li><a href="#tab-notes" data-toggle="tab">{{ trans('fi.notes') }}</a></li>
                            <li><a href="#tab-attachments" data-toggle="tab">{{ trans('fi.attachments') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-additional">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ trans('fi.terms_and_conditions') }}</label>
                                            {!! Form::textarea('terms', $quote->terms, ['id' => 'terms', 'class' => 'form-control', 'rows' => 5]) !!}
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ trans('fi.footer') }}</label>
                                            {!! Form::textarea('footer', $quote->footer, ['id' => 'footer', 'class' => 'form-control', 'rows' => 5]) !!}
                                        </div>
                                    </div>
                                </div>

                                @if ($customFields->count())
                                    <div class="row">
                                        <div class="col-md-12">
                                            @include('custom_fields._custom_fields_unbound', ['object' => $quote])
                                        </div>
                                    </div>
                                @endif

                            </div>
                            <div class="tab-pane" id="tab-notes">
                                <div class="row">
                                    <div class="col-lg-12">
                                        @include('notes._notes', ['object' => $quote, 'model' => 'FI\Modules\Quotes\Models\Quote', 'showPrivateCheckbox' => true, 'hideHeader' => true])
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-attachments">
                                <div class="row">
                                    <div class="col-lg-12">
                                        @include('attachments._table', ['object' => $quote, 'model' => 'FI\Modules\Quotes\Models\Quote'])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-2">

            <div id="div-totals">
                @include('quotes._edit_totals')
            </div>

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">{{ trans('fi.options') }}</h3>
                </div>

                <div class="box-body">

                    <div class="form-group">
                        <label>{{ trans('fi.quote') }} #</label>
                        {!! Form::text('number', $quote->number, ['id' => 'number', 'class' =>
                        'form-control
                        input-sm', 'readonly'=>'true']) !!}
                    </div>
                    
                    <div class="form-group">
                        <label>{{ trans('fi.event_date') }} <span style="color:red;">*</span></label>
                        {!! Form::text('event_date', $quote->formatted_event_date, ['id' =>
                        'event_date', 'class' => 'form-control input-sm']) !!}
                    </div>

                    <div class="form-group">
                        <label>{{ trans('fi.quote_date') }} <span style="color:red;">*</span></label>
                        {!! Form::text('quote_date', $quote->formatted_quote_date, ['id' =>
                        'quote_date', 'class' => 'form-control input-sm']) !!}
                    </div>

                    <div class="form-group">
                        <label>{{ trans('fi.expires') }} <span style="color:red;">*</span></label>
                        {!! Form::text('expires_at', $quote->formatted_expires_at, ['id' => 'expires_at', 'class'
                        => 'form-control input-sm']) !!}
                    </div>

                    <div class="form-group">
                        <label>{{ trans('fi.discount') }}</label>
                        <div class="input-group">
                            {!! Form::text('discount', $quote->formatted_numeric_discount, ['id' =>
                            'discount', 'class' => 'form-control input-sm']) !!}
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ trans('fi.currency') }} <span style="color:red;">*</span></label>
                        {!! Form::select('currency_code', $currencies, $quote->currency_code, ['id' =>
                        'currency_code', 'class' => 'form-control input-sm']) !!}
                    </div>

                    <div class="form-group">
                        <label>{{ trans('fi.exchange_rate') }} <span style="color:red;">*</span></label>
                        <div class="input-group">
                            {!! Form::text('exchange_rate', $quote->exchange_rate, ['id' =>
                            'exchange_rate', 'class' => 'form-control input-sm']) !!}
                            <span class="input-group-btn">
                                <button class="btn btn-default btn-sm" id="btn-update-exchange-rate" type="button"
                                        data-toggle="tooltip" data-placement="left"
                                        title="{{ trans('fi.update_exchange_rate') }}"><i class="fa fa-refresh"></i>
                                </button>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ trans('fi.status') }} <span style="color:red;">*</span></label>
                        {!! Form::select('quote_status_id', $statuses, $quote->quote_status_id,
                        ['id' => 'quote_status_id', 'class' => 'form-control input-sm']) !!}
                    </div>

                    <div class="form-group">
                        <label>{{ trans('fi.template') }} <span style="color:red;">*</span></label>
                        {!! Form::select('template', $templates, $quote->template,
                        ['id' => 'template', 'class' => 'form-control input-sm']) !!}
                    </div>

                </div>
            </div>
        </div>

    </div>

</section>
