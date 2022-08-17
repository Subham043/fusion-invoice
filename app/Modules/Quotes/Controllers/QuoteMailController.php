<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Quotes\Controllers;

use FI\Events\QuoteEmailed;
use FI\Events\QuoteEmailing;
use FI\Http\Controllers\Controller;
use FI\Modules\Email\Email;
use FI\Modules\MailQueue\Support\MailQueue;
use FI\Modules\Quotes\Models\Quote;
use FI\Requests\SendEmailRequest;
use FI\Support\Contacts;
use FI\Support\Parser;
use FI\Support\PDF\PDFFactory;
use Addons\Scheduler\Models\Schedule;

class QuoteMailController extends Controller
{
    private $mailQueue;

    public function __construct(MailQueue $mailQueue)
    {
        $this->mailQueue = $mailQueue;
    }

    public function create()
    {
        
        $quote = Quote::find(request('quote_id'));

        $contacts = new Contacts($quote->client);

        $parser = new Parser($quote);
        
        return view('quotes._modal_mail')
            ->with('quoteId', $quote->id)
            ->with('redirectTo', urlencode(request('redirectTo')))
            ->with('subject', $parser->parse('quoteEmailSubject'))
            ->with('body', $parser->parse('quoteEmailBody'))
            ->with('contactDropdownTo', $contacts->contactDropdownTo())
            ->with('contactDropdownCc', $contacts->contactDropdownCc($quote->user_id))
            ->with('contactDropdownBcc', $contacts->contactDropdownBcc());
    }

    public function store(SendEmailRequest $request)
    {
        $quote = Quote::find($request->input('quote_id'));

	//return response()->json(['errors' => $request->input()], 400);

        event(new QuoteEmailing($quote));

        $mail = $this->mailQueue->create($quote, $request->except('quote_id'));
//         $email = new Email();
//         $email->from(json_decode($mail->from)->email, json_decode($mail->from)->name);
// 		$email->to(json_decode($mail->to));
// 		$email->cc(json_decode($mail->cc));
// 		$email->bcc(json_decode($mail->bcc));
// 		$email->subject($mail->subject); 
		
// 		$email->message($mail->body);
		

        // if ($email->send())
        // {
        //     $mail->sent = 1;
        //     $mail->save();
        //     event(new QuoteEmailed($quote));
        // }
        // else
        // {
        //     return response()->json(['errors' => "Oops.. some error occurred. Please try again."], 400);
        // }
        
        if ($this->mailQueue->send($mail->id))
        {
            // save the event color
            $quote = Quote::find($mail->mailable_id);
            if($quote->quote_status_id < 2){
                $event =  Schedule::select('*')
                ->where('quotes_id', $quote->id)
                ->first();
    		    $event->category_id = 2;
    		    $event->save();
            }
            event(new QuoteEmailed($quote));
        }
        else
        {
            return response()->json(['errors' => [[$this->mailQueue->getError()]]], 400);
        }
    }
}