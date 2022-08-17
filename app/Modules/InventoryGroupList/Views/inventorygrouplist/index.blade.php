@extends('layouts.master')

@section('javascript')
    @include('inventorygrouplist._js_index')
@stop

@section('content')

    <section class="content-header">
        <h1 class="pull-left">Inventory Group List</h1>

        <div class="pull-right">


          <!--  <a href="javascript:void(0)" class="btn btn-default bulk-actions" id="btn-bulk-delete"><i class="fa fa-trash"></i> {{ trans('fi.delete') }}</a> -->

            
            <a href="{{route('inventorygrouplist.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ trans('fi.new') }}</a>
        </div>

        <div class="clearfix"></div>
    </section>

    <section class="content">

        @include('layouts._alerts')

        <div class="row">

            <div class="col-xs-12">

                <div class="box box-primary">

                    <div class="box-body no-padding">
                        @include('inventorygrouplist._table')
                    </div>

                </div>

                <div class="pull-right">
                    {!! $invoices->appends(request()->except('page'))->render() !!}
                </div>

            </div>

        </div>

    </section>

@stop
