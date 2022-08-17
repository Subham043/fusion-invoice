<script type="text/javascript">

    $(function () {

        $("#event_date").datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true, startDate: new Date()});
        $("#invoice_date").datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true, startDate: new Date()});
        $("#due_at").datepicker({format: '{{ config('fi.datepickerFormat') }}', autoclose: true});
        $('textarea').autosize();

        $('#btn-copy-invoice').click(function () {
            $('#modal-placeholder').load('{{ route('invoiceCopy.create') }}', {
                invoice_id: {{ $invoice->id }}
            });
        });

        $('#btn-update-exchange-rate').click(function () {
            updateExchangeRate();
        });
        
        @if (config('fi.mailConfigured'))
        $('#invoice_status_id').change(function () {
            if($(this).val()==2){
                $('#btn-email-invoice').click();
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

        $('.btn-delete-inventory-group-list-item').click(function () {
            if (!confirm('{!! trans('fi.delete_record_warning') !!}')) return false;
            var id = $(this).data('item-id');
            $.post('{{ route('inventoryGroupListItem.delete') }}', {
                id: id
            }).done(function () {
                $('#tr-item-' + id).remove();
                //$('#div-totals').load('{{ route('invoiceEdit.refreshTotals') }}', {
                  //  id: {{ $invoice->id }}
                //});

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

            $.post('{{ route('inventorygrouplist.update', [$invoice->id]) }}', {
                name: $('#name').val(),
                summary: $('#summary').val(),
		custom_price: $('#common_price').val(),
                items: items
            }).done(function () {
                //$('#div-invoice-edit').load('{{ route('invoiceEdit.refreshEdit', [$invoice->id]) }}', function () {
                  //  notify('{{ trans('fi.record_successfully_updated') }}', 'success');
                //});
		notify('{{ trans('fi.record_successfully_updated') }}', 'success');
		//location.reload();
            }).fail(function (response) {
                console.log(response)
                $.each($.parseJSON(response.responseText).errors, function (id, message) {
                    notify(message, 'danger');
                });
                notify($.parseJSON(response.responseText).message, 'danger');
            });


            });
        });

        $('.btn-update-invoice').click(function () {
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

            $.post('{{ route('inventorygrouplist.update', [$invoice->id]) }}', {
                name: $('#name').val(),
                summary: $('#summary').val(),
		custom_price: $('#common_price').val(),
                items: items
            }).done(function () {
                //$('#div-invoice-edit').load('{{ route('invoiceEdit.refreshEdit', [$invoice->id]) }}', function () {
                  //  notify('{{ trans('fi.record_successfully_updated') }}', 'success');
                //});
		notify('{{ trans('fi.record_successfully_updated') }}', 'success');
		location.reload();
            }).fail(function (response) {
                console.log(response)
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
