<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>{{ config('fi.headerTitleText') }}</title>

    @include('layouts._head')

    @include('layouts._js_global')

    @yield('head')

    @yield('javascript')

</head>
<body class="{{ $skinClass }} sidebar-mini fixed">

<div class="wrapper">

    @include('layouts._header')

    <aside class="main-sidebar">

        <section class="sidebar">

            @if (config('fi.displayProfileImage'))
		<div>
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="{{ $profileImageUrl }}" alt="User Image"/>
                    </div>
                    <div class="pull-left info">
                        <p>{{ $userName }}</p>
                    </div>
                </div>
		@if (isset($displaySearch) and $displaySearch == true)
            
                <form action="{{ request()->fullUrl() }}" method="get" class="sidebar-form">
                    <input type="hidden" name="status" value="{{ request('status') }}"/>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="{{ trans('fi.search') }}..."/>
                        <span class="input-group-btn">
                            <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </form>
                
            @endif
		</div>
            @endif


            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('dashboard.index') }}">
                        <i class="fa fa-dashboard"></i> <span>{{ trans('fi.dashboard') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('clients.index', ['status' => 'active']) }}">
                        <i class="fa fa-users"></i> <span>{{ trans('fi.clients') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('quotes.index', ['status' => config('fi.quoteStatusFilter')]) }}">
                        <i class="fa fa-file-text-o"></i> <span>{{ trans('fi.quotes') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('invoices.index', ['status' => config('fi.invoiceStatusFilter')]) }}">
                        <i class="fa fa-file-text"></i> <span>{{ trans('fi.invoices') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('recurringInvoices.index') }}">
                        <i class="fa fa-refresh"></i> <span>{{ trans('fi.recurring_invoices') }}</span>
                    </a>
                </li>
		<li>
                    <a href="{{ route('scheduler.loadinloadoutcalendar') }}">
                        <i class="fa fa-calendar"></i> <span>Load In - Load Out Calendar</span>
                    </a>
                </li>
		<li>
                    <a href="{{ route('scheduler.fullcalendar') }}">
                        <i class="fa fa-th"></i> <span>Events {{ trans('Scheduler::texts.calendar') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('payments.index') }}">
                        <i class="fa fa-credit-card"></i> <span>{{ trans('fi.payments') }}</span>
                    </a>
                </li>

		<li class="treeview {{strpos(url()->current(),'inventory') !== false ? 'active' : ''}} ">
                    <a href="#">
                        <i class="fa fa-shopping-cart"></i>
                        <span>{{ trans('fi.inventory') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu {{strpos(url()->current(),'inventory') !== false ? 'menu-open' : ''}} ">
                        <li><a href="{{ route('inventory.index') }}"><i class="fa fa-caret-right"></i> {{ trans('fi.inventory') }}</a></li>
                        <li><a href="{{ route('inventory_category.index') }}"><i class="fa fa-caret-right"></i> Inventory Category</a></li>
                        <li><a href="{{ route('inventory_sub_category.index') }}"><i class="fa fa-caret-right"></i> Inventory Sub-Category</a></li>
			<li><a href="{{ route('inventory_location.index') }}"><i class="fa fa-caret-right"></i> Inventory Location</a></li>
                        <li><a href="{{ route('inventory_item_location.index') }}"><i class="fa fa-caret-right"></i> Inventory Item Location</a></li>
			<li><a href="{{ route('inventory_color.index') }}"><i class="fa fa-caret-right"></i> Inventory Color</a></li>
			<li><a href="{{ route('inventory_style.index') }}"><i class="fa fa-caret-right"></i> Inventory Style</a></li>
			<li><a href="{{ route('inventorygrouplist.index') }}"><i class="fa fa-caret-right"></i> Inventory Group List</a></li>
                     </ul>
                </li>

		<li class="treeview {{strpos(url()->current(),'barcode-print') !== false ? 'active' : ''}} ">
                    <a href="#">
                        <i class="fa fa-barcode"></i>
                        <span>Barcode Printer</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu  {{strpos(url()->current(),'barcode-print') !== false ? 'menu-open' : ''}} ">
                        <li><a href="{{ route('invoices.barcodePrinter') }}" target="_blank"><i class="fa fa-caret-right"></i> Invoices Checklist</a></li>
                        <li><a href="{{ route('quotes.barcodePrinter') }}" target="_blank"><i class="fa fa-caret-right"></i> Quotes Checklist</a></li>
                        <li><a href="{{ route('inventory.barcodePrinter') }}" target="_blank"><i class="fa fa-caret-right"></i> Inventory </a></li>
			<li><a href="{{ route('inventorygrouplist.barcodePrinter') }}" target="_blank"><i class="fa fa-caret-right"></i> Inventory Group</a></li>
                     </ul>
                </li>

                <li>
                    <a href="{{ route('expenses.index') }}">
                        <i class="fa fa-bank"></i> <span>{{ trans('fi.expenses') }}</span>
                    </a>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-bar-chart-o"></i>
                        <span>{{ trans('fi.reports') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('reports.clientStatement') }}"><i class="fa fa-caret-right"></i> {{ trans('fi.client_statement') }}</a></li>
                        <li><a href="{{ route('reports.eventStatement') }}"><i class="fa fa-caret-right"></i> Event Statement</a></li>
                        <li><a href="{{ route('reports.expenseList') }}"><i class="fa fa-caret-right"></i> {{ trans('fi.expense_list') }}</a></li>
                        <li><a href="{{ route('reports.itemSales') }}"><i class="fa fa-caret-right"></i> {{ trans('fi.item_sales') }}</a></li>
                        <li><a href="{{ route('reports.paymentsCollected') }}"><i class="fa fa-caret-right"></i> {{ trans('fi.payments_collected') }}</a></li>
                        <li><a href="{{ route('reports.profitLoss') }}"><i class="fa fa-caret-right"></i> {{ trans('fi.profit_and_loss') }}</a></li>
                        <li><a href="{{ route('reports.revenueByClient') }}"><i class="fa fa-caret-right"></i> {{ trans('fi.revenue_by_client') }}</a></li>
                        <li><a href="{{ route('reports.taxSummary') }}"><i class="fa fa-caret-right"></i> {{ trans('fi.tax_summary') }}</a></li>

                        @foreach (config('fi.menus.reports') as $report)
                            @if (view()->exists($report))
                                @include($report)
                            @endif
                        @endforeach
                    </ul>
                </li>

                @foreach (config('fi.menus.navigation') as $menu)
                    @if (view()->exists($menu))
                        @include($menu)
                    @endif
                @endforeach
            </ul>

        </section>

    </aside>

    <div class="content-wrapper">
        @yield('content')
    </div>

</div>

<div id="modal-placeholder"></div>

</body>
</html>
