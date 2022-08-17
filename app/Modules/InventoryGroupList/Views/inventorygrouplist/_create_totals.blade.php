<div class="box box-primary">
    <div class="box-body">
        <div class="clearfix"></div>

        <div class="clearfix"></div>
        <span class="pull-left"><strong>{{ trans('fi.total') }}</strong></span><span
            class="pull-right" id="totalPriceDisplay">$0.00</span>

        <div class="clearfix"></div>
	<br/>
	<div class="form-group">
                        <label>Bundled Price</label>
                        <div class="input-group">
				<span class="input-group-addon">$</span>
                            {!! Form::text('common_price', '0.00', ['id' =>
                            'common_price', 'class' => 'form-control input-sm']) !!}
                            
                        </div>
                    </div>

    </div>
</div>