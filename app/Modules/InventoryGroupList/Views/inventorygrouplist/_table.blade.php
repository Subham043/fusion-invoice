<table class="table table-hover">

    <thead>
    <tr>
        <th>{!! Sortable::link('name', 'name', 'inventory_group_list') !!}</th>
        <th class="hidden-sm hidden-xs">Description</th>
        <th style="text-align: right; padding-right: 25px;">{!! Sortable::link('total', trans('fi.total'), 'inventory_group_list') !!}</th>
	<th class="hidden-sm hidden-xs">Consolidated Price</th>
        <th>{{ trans('fi.options') }}</th>
    </tr>
    </thead>

    <tbody>
    @foreach ($invoices as $invoice)
        <tr>
            <td class="hidden-sm hidden-xs">{{ $invoice->name }}</td>
	    <td class="hidden-sm hidden-xs">{{ $invoice->summary }}</td>
            <td style="text-align: right; padding-right: 25px;">{{ $invoice->total }}</td>
		<td  class="hidden-sm hidden-xs">{{ $invoice->custom_price }}</td>
            <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                        {{ trans('fi.options') }} <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="{{ route('inventorygrouplist.edit', [$invoice->id]) }}"><i
                                    class="fa fa-edit"></i> {{ trans('fi.edit') }}</a></li>
                      <!--  <li><a href="{{ route('invoices.delete', [$invoice->id]) }}"
                               onclick="return confirm('{{ trans('fi.delete_record_warning') }}');"><i
                                    class="fa fa-trash-o"></i> {{ trans('fi.delete') }}</a></li> -->
                    </ul>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>

</table>
