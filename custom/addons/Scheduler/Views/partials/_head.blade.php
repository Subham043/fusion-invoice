<!-- Bootstrap Core CSS -->
{{-- line below causing conflict with layouts.master --}}
{{--{!! Html::style('assets/addons/Scheduler/assets/css/bootstrap.min.css') !!}--}}
{!! Html::style('assets/addons/Scheduler/Assets/jquery-ui-dist/jquery-ui.min.css') !!}
{!! Html::style('assets/addons/Scheduler/Assets/css/custom.css') !!}
{!! Html::style('assets/addons/Scheduler/Assets/pnotify/dist/pnotify.css') !!}
{!! Html::style('assets/addons/Scheduler/Assets/datatables.net-dt/css/jquery.dataTables.css') !!}
{{--make sure to use from the BUILD directory...--}}
{!! HTML::style('assets/addons/Scheduler/Assets/jquery-datetimepicker/build/jquery.datetimepicker.min.css') !!}
<!-- Custom CSS -->
{!! Html::style('assets/addons/Scheduler/Assets/css/sb-admin-2.min.css') !!}
<!-- Fonts -->
{!! Html::style('assets/addons/Scheduler/Assets/font-awesome/css/font-awesome.min.css') !!}
{{--SCRIPTS--}}
<!-- Bootstrap Core JavaScript -->
{{-- line below causing conflict with layouts.master which is using 3.3.4   BELOW is 3.3.7--}}
{{--<script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>--}}
{!! Html::script('assets/addons/Scheduler/Assets/jquery-ui-dist/jquery-ui.min.js') !!}
{{--make sure to use from the BUILD directory...--}}
{!! Html::script('assets/addons/Scheduler/Assets/jquery-datetimepicker/build/jquery.datetimepicker.full.min.js') !!}
{{-- line below causing conflict with layouts.master which is using 2.2.4   BELOW is 3.2.1--}}
{{--{!! Html::script('assets/addons/Scheduler/Assets/jquery/dist/jquery.min.js') !!}--}}
{!! Html::script('assets/addons/Scheduler/Assets/pnotify/dist/pnotify.js') !!}
{!! Html::script('assets/addons/Scheduler/Assets/angular/angular.min.js') !!}
{!! Html::script('assets/addons/Scheduler/Assets/datatables.net/js/jquery.dataTables.js') !!}
{!! Html::script('assets/addons/Scheduler/Assets/datatables.net-bs/js/dataTables.bootstrap.js') !!}

{{--_foot--}}
@include('Scheduler::partials._js_datatables')
@include('Scheduler::partials._alerts')