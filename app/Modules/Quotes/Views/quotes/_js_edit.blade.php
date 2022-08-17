<script type="text/javascript">

	function deleteUnsavedGroupItem(){
		if (!confirm('{!! trans('fi.delete_record_warning') !!}')) return false;
		this.event.target.parentNode.parentNode.parentNode.remove();
	}

    $(function () {

        $("#quote_date").datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true, startDate: new Date()});
        $("#event_date").datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true, startDate: new Date()});
        $("#expires_at").datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true, startDate: new Date()});
        $('textarea').autosize();

        $('#btn-copy-quote').click(function () {
            $('#modal-placeholder').load('{{ route('quoteCopy.create') }}', {
                quote_id: {{ $quote->id }}
            });
        });

        $('#btn-quote-to-invoice').click(function () {
            $('#modal-placeholder').load('{{ route('quoteToInvoice.create') }}', {
                quote_id: {{ $quote->id }},
                client_id: {{ $quote->client_id }}
            });
        });

        $('#btn-update-exchange-rate').click(function () {
            updateExchangeRate();
        });
        
        @if (config('fi.mailConfigured'))
        $('#quote_status_id').change(function () {
            if($(this).val()==2){
                $('#btn-email-quote').click();
            }
        });
        @endif

        $('#currency_code').change(function () {
            updateExchangeRate();
        });

        function updateExchangeRate() {
            $.post('{{ route('currencies.getExchangeRate') }}', {
                currency_code: $('#currency_code').val()
            }, function (data) {
                $('#exchange_rate').val(data);
            });
        }

        $('.btn-delete-quote-item').click(function () {
            if (!confirm('{!! trans('fi.delete_record_warning') !!}')) return false;
            id = $(this).data('item-id');
            $.post('{{ route('quoteItem.delete') }}', {
                id: id
            }).done(function () {
                $('#tr-item-' + id).remove();
                $('#div-totals').load('{{ route('quoteEdit.refreshTotals') }}', {
                    id: {{ $quote->id }}
                });
            });
        });

	$('.btn-delete-quote-group-item').click(function () {
            if (!confirm('{!! trans('fi.delete_record_warning') !!}')) return false;
            var id = $(this).data('item-id');
            $.post('{{ route('quoteGroupItem.delete') }}', {
                id: id
            }).done(function () {
                $('#tr-group-item-' + id).remove();
                $('#div-totals').load('{{ route('quoteEdit.refreshTotals') }}', {
                    id: {{ $quote->id }}
                });
            });
        });

        $('.btn-save-quote').click(function () {
            var items = [];
            var display_order = 1;
	    var group_items = [];
            var group_display_order = 1;
            var custom_fields = {};
            var apply_exchange_rate = $(this).data('apply-exchange-rate');

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

	    $('table tr.item_group').each(function () {
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
                row['display_order'] = group_display_order;
                display_order++;
                group_items.push(row);
            });

            $('.custom-form-field').each(function () {
                var fieldName = $(this).data('quotes-field-name');
                if (fieldName !== undefined) {
                    custom_fields[$(this).data('quotes-field-name')] = $(this).val();
                }
            });

            $.post('{{ route('quotes.update', [$quote->id]) }}', {
                number: $('#number').val(),
                quote_date: $('#quote_date').val(),
                event_date: $('#event_date').val(),
                expires_at: $('#expires_at').val(),
                quote_status_id: $('#quote_status_id').val(),
                items: items,
		group_items: group_items,
                terms: $('#terms').val(),
                footer: $('#footer').val(),
                currency_code: $('#currency_code').val(),
                exchange_rate: $('#exchange_rate').val(),
                custom: custom_fields,
                apply_exchange_rate: apply_exchange_rate,
                template: $('#template').val(),
                summary: $('#summary').val(),
                discount: $('#discount').val()
            }).done(function () {
                $('#div-quote-edit').load('{{ route('quoteEdit.refreshEdit', [$quote->id]) }}', function () {
                    notify('{{ trans('fi.record_successfully_updated') }}', 'success');
                });
            }).fail(function (response) {
                $.each($.parseJSON(response.responseText).errors, function (id, message) {
                    notify(message, 'danger');
                });
                notify($.parseJSON(response.responseText).message, 'danger');
            });
        });

        var fixHelper = function (e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function (index) {
                $(this).width($originals.eq(index).width())
            });
            return $helper;
        };

        $("#item-table tbody").sortable({
            helper: fixHelper
        });

    });

</script>