<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Reports\Reports;

use FI\Modules\Clients\Models\Client;
use FI\Modules\Invoices\Models\Invoice;
use FI\Modules\Quotes\Models\Quote;
use FI\Support\CurrencyFormatter;
use FI\Support\DateFormatter;

class EventStatementReport
{
    public function getResults($type, $status, $fromDate, $toDate, $companyProfileId = null)
    {
        
        $results = [
            'status' => $status,
            'type' => $type,
            'from_date'   => $fromDate,
            'to_date'     => $toDate,
            'subtotal'    => 0,
            'discount'    => 0,
            'tax'         => 0,
            'total'       => 0,
            'paid'        => 0,
            'balance'     => 0,
            'records'     => [],
        ];

        if($type=='invoice'){
            
            $invoices = Invoice::where('invoice_status_id', $status)
            ->with('items', 'client.currency', 'amount.invoice.currency')
            ->where('event_date', '>=', $fromDate)
            ->where('event_date', '<=', $toDate)
            ->orderBy('event_date');

            if ($companyProfileId)
            {
                $invoices->where('company_profile_id', $companyProfileId);
            }
    
            $invoices = $invoices->get();
    
            foreach ($invoices as $invoice)
            {
                $results['records'][] = [
                    'formatted_invoice_date' => DateFormatter::format($invoice->event_date),
                    'number'                 => $invoice->number,
                    'summary'                => $invoice->client->name,
                    'subtotal'               => $invoice->amount->subtotal,
                    'discount'               => $invoice->amount->discount,
                    'tax'                    => $invoice->amount->tax,
                    'total'                  => $invoice->amount->total,
                    'paid'                   => $invoice->amount->paid,
                    'balance'                => $invoice->amount->balance,
                    'formatted_subtotal'     => $invoice->amount->formatted_subtotal,
                    'formatted_discount'     => $invoice->amount->formatted_discount,
                    'formatted_tax'          => $invoice->amount->formatted_tax,
                    'formatted_total'        => $invoice->amount->formatted_total,
                    'formatted_paid'         => $invoice->amount->formatted_paid,
                    'formatted_balance'      => $invoice->amount->formatted_balance,
                ];
    
                $results['subtotal'] += $invoice->amount->subtotal;
                $results['discount'] += $invoice->amount->discount;
                $results['tax']      += $invoice->amount->tax;
                $results['total']    += $invoice->amount->total;
                $results['paid']     += $invoice->amount->paid;
                $results['balance']  += $invoice->amount->balance;
            }
            
            
        }else{
            
            $quotes = Quote::where('quote_status_id', $status)
            ->with('items', 'client.currency', 'amount.quote.currency')
            ->where('event_date', '>=', $fromDate)
            ->where('event_date', '<=', $toDate)
            ->orderBy('event_date');

            if ($companyProfileId)
            {
                $quotes->where('company_profile_id', $companyProfileId);
            }
    
            $quotes = $quotes->get();
    
            foreach ($quotes as $quote)
            {
                $results['records'][] = [
                    'formatted_invoice_date' => DateFormatter::format($quote->event_date),
                    'number'                 => $quote->number,
                    'summary'                => $quote->client->name,
                    'subtotal'               => $quote->amount->subtotal,
                    'discount'               => $quote->amount->discount,
                    'tax'                    => $quote->amount->tax,
                    'total'                  => $quote->amount->total,
                    'paid'                   => $quote->amount->paid,
                    'balance'                => $quote->amount->balance,
                    'formatted_subtotal'     => $quote->amount->formatted_subtotal,
                    'formatted_discount'     => $quote->amount->formatted_discount,
                    'formatted_tax'          => $quote->amount->formatted_tax,
                    'formatted_total'        => $quote->amount->formatted_total,
                    'formatted_paid'         => $quote->amount->formatted_paid,
                    'formatted_balance'      => $quote->amount->formatted_balance,
                ];
    
                $results['subtotal'] += $quote->amount->subtotal;
                $results['discount'] += $quote->amount->discount;
                $results['tax']      += $quote->amount->tax;
                $results['total']    += $quote->amount->total;
                $results['paid']     += $quote->amount->paid;
                $results['balance']  += $quote->amount->balance;
            }
            
            
        }

        


        return $results;
    }
}