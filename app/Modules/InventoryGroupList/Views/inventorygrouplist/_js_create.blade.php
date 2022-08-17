<script type="text/javascript">

    $(function () {

        $('#create-invoice').modal();

        $('#create-invoice').on('shown.bs.modal', function () {
            $("#create_client_name").focus();
            $('#create_client_name').typeahead('val', clientName);
            $(".tt-dropdown-menu").bind('click', function(){
                $.get('{{ route('clients.ajax.userlookup') }}' + '?query='+$("#create_client_name").val()).done(function (response) {
                    var json = JSON.parse(response)
                    if(json!=null){
                        $('#type').val(json.type)
                    }
                }).fail(function (response) {
                    showErrors($.parseJSON(response.responseText).errors, '#modal-status-placeholder');
                });
            });
            $("#create_client_name").bind('change keydown', function(){
                $.get('{{ route('clients.ajax.userlookup') }}' + '?query='+this.value).done(function (response) {
                    var json = JSON.parse(response)
                    if(json!=null){
                        $('#type').val(json.type)
                    }
                }).fail(function (response) {
                    showErrors($.parseJSON(response.responseText).errors, '#modal-status-placeholder');
                });
            });
        });

        $('#create_invoice_date').datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true, startDate: new Date()});
	$("#create_event_date").datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true, startDate: new Date()});

        $('#invoice-create-confirm').click(function () {

	var items = [];
            var display_order = 1;

            $('table tr.item').each(function () {
                var row = {};
                $(this).find('input,select,textarea').each(function () {
                    if ($(this).attr('name') !== undefined) {
                        if ($(this).is(':checkbox')) {
                            if ($(this).is(':checked')) {
                                row[$(this).attr('name')] = 1;
                            }
                            else {
                                row[$(this).attr('name')] = 0;
                            }
                        }
                        else {
                            row[$(this).attr('name')] = $(this).val();
                        }
                    }
                });
                row['display_order'] = display_order;
                display_order++;
                items.push(row);
            });

      
	$.post('{{ route('inventorygrouplist.create') }}', {
                name: $('#name').val(),
		custom_price: $('#common_price').val(),
                summary: $('#summary').val(),
                items: items
            }).done(function () {
              	notify('{{ trans('fi.record_successfully_updated') }}', 'success');
            }).fail(function (response) {
                console.log(response)
                $.each($.parseJSON(response.responseText).errors, function (id, message) {
                    notify(message, 'danger');
                });
                notify($.parseJSON(response.responseText).message, 'danger');
            });
        });

	

	

    });

</script>
