<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Reports\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Reports\Reports\EventStatementReport;
use FI\Modules\Reports\Requests\EventStatementReportRequest;
use FI\Support\PDF\PDFFactory;
use FI\Support\Statuses\InvoiceStatuses;
use FI\Support\Statuses\QuoteStatuses;

class EventStatementReportController extends Controller
{
    private $report;

    public function __construct(EventStatementReport $report)
    {
        $this->report = $report;
    }

    public function index()
    { 
        return view('reports.options.event_statement')
        ->with('invoiceStatuses', InvoiceStatuses::lists())
        ->with('quoteStatuses', QuoteStatuses::lists());
    }

    public function validateOptions(EventStatementReportRequest $request)
    {

    }

    public function html()
    {
        $results = $this->report->getResults(
            request('type'),
            request('status'),
            request('from_date'),
            request('to_date'),
            request('company_profile_id'));

        return view('reports.output.event_statement')
            ->with('results', $results);
    }

    public function pdf()
    {
        $pdf = PDFFactory::create();
        $pdf->setPaperOrientation('landscape');

        $results = $this->report->getResults(
            request('type'),
            request('status'),
            request('from_date'),
            request('to_date'),
            request('company_profile_id'));

        $html = view('reports.output.event_statement')
            ->with('results', $results)->render();

        $pdf->download($html, 'Event-Statement' . '.pdf');
    }
}