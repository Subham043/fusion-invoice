@extends('layouts.master')

@section('javascript')

    @include('layouts._datepicker')
    @include('layouts._typeahead')
    @include('inventory._js_inventory_group')
@include('inventorygrouplist._js_create')
    <style>
    .info-td{
        display:flex;
        justify-content:space-between;
        align-items:center;
    }
    
/* Tooltip container */
.tooltip-custom {
  position: relative;
  display: inline-block;
  margin-left:10px;
}

.tooltip-custom i {
    font-size:20px;
}

/* Tooltip text */
.tooltip-custom .tooltiptext {
  visibility: hidden;
  width: 220px;
  background-color: #fff;
  color: #000;
  text-align: center;
  padding: 5px 15px;
  border-radius: 6px;
 
  /* Position the tooltip text - see examples below! */
  position: absolute;
  z-index: 100;
  top: -5px;
  right: 105%;
  border:1px solid #000;
  box-shadow:5px 4px 6px 1px #818181;
}

/* Show the tooltip text when you mouse over the tooltip container */
.tooltip-custom:hover .tooltiptext {
  visibility: visible;
}

.panel-heading{
    display:flex;
    justify-content:space-between;
    align-items:center;
}
</style>

@stop

@section('content')

    <div id="div-invoice-edit">

        @include('inventorygrouplist._create')

    </div>

@stop