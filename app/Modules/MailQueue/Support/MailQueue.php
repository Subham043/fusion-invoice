<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\MailQueue\Support;

use FI\Support\PDF\PDFFactory;
use Illuminate\Support\Facades\Mail;
use FI\Modules\Email\Email;

class MailQueue
{
    protected $error;

    public function create($object, $input)
    {
        return $object->mailQueue()->create([
            'from'       => json_encode(['email' => $object->user->email, 'name' => $object->user->name]),
            'to'         => json_encode($input['to']),
            'cc'         => json_encode(($input['cc']) ?: []),
            'bcc'        => json_encode(($input['bcc']) ?: []),
            'subject'    => $input['subject'],
            'body'       => $input['body'],
            'attach_pdf' => $input['attach_pdf'],
        ]);
    }

    public function send($id)
    {
        $mail = \FI\Modules\MailQueue\Models\MailQueue::find($id);

        if ($this->sendMail(
            $mail->from,
            $mail->to,
            $mail->cc,
            $mail->bcc,
            $mail->subject,
            $mail->body,
            $this->getAttachmentPath($mail)
        )
        )
        {
            $mail->sent = 1;
            $mail->save();

            return true;
        }

        return false;
    }

    private function getAttachmentPath($mail)
    {
        if ($mail->attach_pdf)
        {
            $object = $mail->mailable;
//echo base_path();
            $pdfPath = base_path('storage/' . $object->pdf_filename);

            $pdf = PDFFactory::create();

            $pdf->save($object->html, $pdfPath);

            return $pdfPath;
        }

        return null;
    }

    private function sendMail($from, $to, $cc, $bcc, $subject, $body, $attachmentPath = null)
    {
try
        {
            $htmlTemplate = (view()->exists('email_templates.html')) ? 'email_templates.html' : 'templates.emails.html';

            Mail::send([$htmlTemplate, 'templates.emails.text'], ['body' => $body], function ($message) use ($from, $to, $cc, $bcc, $subject, $attachmentPath)
            {
                $from = json_decode($from, true);
                $to   = json_decode($to, true);
                $cc   = json_decode($cc, true);
                $bcc  = json_decode($bcc, true);
	//	echo $from;
                //$message->from($from['email'], $from['name']);
		$message->from('subham.s@jurysoft.com', $from['name']);
                $message->subject($subject);

                foreach ($to as $toRecipient)
                {
                    $message->to(trim($toRecipient));
                }

                foreach ($cc as $ccRecipient)
                {
                    if ($ccRecipient !== '')
                    {
                        $message->cc(trim($ccRecipient));
                    }
                }

                foreach ($bcc as $bccRecipient)
                {
                    if ($bccRecipient !== '')
                    {
                        $message->bcc(trim($bccRecipient));
                    }
                }

  #              if (config('fi.mailReplyToAddress'))
  #              {
  #                  $message->replyTo(config('fi.mailReplyToAddress'));
  #              }

                if ($attachmentPath)
                {
                    $message->attach($attachmentPath);
                }

		if (str_contains($subject, 'INV')) {
			$message->attach(base_path('storage/Millennium_Contract_2022.docx'));
			$message->attach(base_path('storage/Credit_Card_Authorization_Form_2022.docx'));
		}
            });

//$from = json_decode($from, true);
//$to   = json_decode($to, true);
//$cc   = json_decode($cc, true);
//$bcc  = json_decode($bcc, true);
//print_r(implode(', ', $to));
//print_r($body);
//$email = new Email();
//$email->from($from['email'], $from['name']);
//$email->to($to);
//$email->cc($cc);
//$email->bcc($bcc);
//$email->subject($subject); 
		
//$email->message($body);

//if ($attachmentPath)
//{
//echo $attachmentPath;
//$email->attach($attachmentPath);
//}

//if ($email->send())
//{
        //    if ($attachmentPath and file_exists($attachmentPath))
      //      {
                //unlink($attachmentPath);
    //        }

  //          return true;
//}
if ($attachmentPath and file_exists($attachmentPath))
      {
        unlink($attachmentPath);
    }

return true;
        }
        catch (\Exception $e)
        {
            $this->error = $e->getMessage();

            return false;
        }        
    }

    public function getError()
    {
        return $this->error;
    }
}
