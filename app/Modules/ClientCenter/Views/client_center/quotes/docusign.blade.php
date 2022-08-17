<link href="{{ asset('assets/plugins/signature/jquery.signaturepad.css') }}" rel="stylesheet" />
<script src="{{ asset('assets/plugins/signature/json2.min.js') }}"></script>
<script src="{{ asset('assets/plugins/signature/jquery.signaturepad.js') }}"></script>
<script src="{{ asset('assets/plugins/signature/flashcanvas.js') }}"></script>
<style>
#drawForm{
border:1px solid #ccc;
min-height:360px;
width:500px;
margin-bottom:20px;
margin-left: auto;
margin-right: auto;
}

.box-footer{
text-align:center;
}

fieldset{
margin-bottom:10px;
}
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-solid direct-chat direct-chat-warning">
                <div class="box-header" style="position:static">
                    <h3 class="box-title">Contractual Agreement</h3>
                </div>
            <div class="box-body">
                <div class="direct-chat-messages" id="notes-list" style="height:450px;">
                    <p>This contract is between Millennium Events and Floral and <u>{{$quote->client->name}}</u> for an event on <u>{{$quote->formatted_event_date}}</u>. Attached to this contract and made 
part hereof is the invoice(s) that describes the decor to be provided.
Millennium Events and Floral reserves the right to cancel this contract if events such
as fire, natural disaster, tragedy, or other emergency beyond our control prevents 
us from performing our obligation. Our liability is limited to a full refund of all money
paid.</p>
<p><b>Non-Corporate Reservation/Payment Terms:</b> Payments must be in cash, check,
credit card or money order. If paying by credit card a 3.25% convenience fee will be
added at time of processing. A deposit of 50% is required to reserve the date unless
prior arrangements have been made. A final consultation will be scheduled 
approximately 3 weeks before the event, at that time all final changes must be 
made and final payment is due. A credit card is required to be kept on file for all 
customers. The card will not be used without notice and will only be used for 
incidentals and travel, unless requested to be used to pay the balance. Any unpaid 
balances will be charged to the card on file after 30 days from the ending contract 
or change order date unless other arrangements have been communicated in 
writing.</p>

<p><b>Corporate Payment terms:</b> If paying by credit card a 3% convenience fee will 
be added at time of processing. A deposit of 50% of the full price is required for 
reservation of the event date unless credit terms have been established with the 
Company. Final payment of the event is due at the completion of the event unless 
otherwise approved by management or credit terms have been established with the
Company.  Additional purchases will be invoiced separately if ordered after your 
final payment. They must be paid before delivery. Additions will be accepted up to 
one week before the event. Color and style will be matched as closely as possible if 
there is not sufficient time to order similar decor.</p>

<p><b>Additional fees will apply as follows:</b> 
<ul>
<li>A design fee of a minimum of $250 or 5% - whichever is greater will be added
to all events up to $30,000 in decor services. $30,000 and above will be a flat
rate of $1,500.</li>
<li>A fee of $250 will apply to all "Reveal" visits for events under $30,000. 
Events over $30,000 will be offered one complimentary "Reveal".</li>
<li>Florals are quoted at the current market prices and will change as the market
price increases or decreases.</li>
<li>Special construction projects will be charged at time and material plus 20% 
overhead. Lumber is quoted at current market prices and will change as the 
market price increases or decreases.</li>
<li>Change orders made within 3 weeks of the event will be due upon completion
of change order request. Additional labor and delivery expense will be 
charged.</li>
<li>A damage deposit equal to 10% of the total rental will be due with the final 
payment. This deposit may be in the form of a check or credit hold on a credit
card as a pending charge. Upon return of all rentals in good and satisfactory 
condition the check or credit hold will be released within two business days.</li>
</ul>
</p>

<p><b>Millennium Events and Floral minimums policy is a s follows:</b>
<ul>
<li>$2,500 net of taxes, labor and setup/delivery charges will be required for 
events scheduled Monday - Thursday.</li>
<li>$5,000 net of taxes, labor and set up delivery charges will be required for 
events scheduled Friday - Sunday</li>
</ul>
</p>

<p><b>Requirements:</b> You are required to schedule adequate time with your reception 
hall in order for us to decorate your event. We suggest that you have this in writing.
We will provide you with the time needed to complete your set up. Additional labor 
charges may be added to ensure that we can complete your set up on time. We are 
not responsible if the decor is not done on time due to double booking, prior events 
that have been delayed, or if hall is not open on time. In addition, our set up time 
may vary due to other events beyond our control. However, we will honor your 
event and conduct our setup in a timely manner, calling the contact person before 
our arrival.</p>

<p><b>Outdoor Decor:</b> If rain, strong wind or other bad weather occurs during or after 
the decor has been set up, we will communicate with you and discuss other options.
If decor needs to be moved to an inside location, taken down and 
reinstalled/designed, a re-design fee will be charged. Outdoor decor may be 
cancelled by our team if we feel necessary and will be charged at full price.</p>

<p><b>Cancellation Policy:</b> For any reason/circumstance all cancellations must be 
made in writing. At any point any cancellations will be charged for any labor 
associated with the event and any product that has been purchased for the event will the responsibility of the client to pay those amounts in full. Cancellations made 
less than 60 days before the event will receive no refund and the client will be 
responsible for any and all remaining balances. If event is cancelled 6 months prior 
to the event you will be entitled to a cash refund. Event cancellations made less 
than 6 months before the event and more than 60  days are not refundable and will 
receive a Company credit that may be applied to future events for a period of 18 
months from the cancellation date. Any designs contained in the proposal are 
copyrighted property of Millennium Events and Floral and may not be reproduced or
used in nay format without express written permission of Millennium Events and 
Floral.</p>

<p><b>Substitutions:</b> We reserve the right to make substitutions in the event that the 
design materials are not of a quality suitable for your event. In this instance, the 
integrity of the proposed color scheme will be maintained and an equivalent or 
greater will be used.</p>

<p><b>Rentals:</b> All rentals must be returned within 2 business days or an additional 
rental fee will be charged, unless other terms have been authorized by 
management.</p>

<p><b>Multi-Year Contracts:</b> A multiyear contract guarantees current pricing for 
length of contract with the exception of florals and construction materials (which 
will be billed at current market pricing).  Any additions or changes will be reflected 
in the final cost.</p>

<p><b>Damage Waiver/Replacement Charge:</b> The cost of damages, loss or 
replacement for any equipment or decorations damaged due to loss, theft, abuse or
misuse will be assessed at completion of event or return of rentals. Any damage or 
loss will result in a supplemental bill and will be deducted from your damage 
deposit. The company will return the balance of the damage deposit within 2 
business days after return of the rentals.</p>

<p><b>Pick Up/Delivery Service:</b> Rates are based on first floor delivery to your door 
Monday-Saturday. Additional charges may be assessed for other than first floor 
delivery, Sunday delivery, excessive time involved in areas that are not easily 
accessible to the delivery vehicle and parking fees. Rentals will be picked up at the 
scheduled time. If your event has finished earlier, you may call us for an earlier pick
up (if a driver is available). Under no circumstances should the rental equipment be 
left alone at the event site unless authorized by Millennium. If you leave the 
equipment or rented decorations unattended, you are responsible for any losses 
incurred. If your event lasts longer than the agreed upon time an additional labor 
fee may be added.</p>

<p><i><code>*Please remember that rentals are props for decoration purposes only. Children 
should NOT be allowed near them unless supervised by an adult. These items are 
not child proof. Millennium Events is not responsible or liable for accidents caused 
by misuse of rentals.</code></i></p>

<p>We understand and agree to the terms and policies<br />
<b>Client:</b> {{$quote->client->name}}<br/>
</p>

<p><i><code>*Please provide your signature in the box given below to approve the quote.</code></i></p>



                </div>
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-sm-12">
			<form method="POST" action="{{route('clientCenter.public.quote.approve', [$quote->url_key])}}" id="drawForm" onsubmit="return confirm('{{ trans('fi.confirm_approve_quote') }}');">
				{{ csrf_field() }}
				<canvas class="pad"></canvas>
				<fieldset>
					<input type="hidden" name="sign" id="sign">
					<input type="reset" value="Clear" class="btn btn-primary" />
					<input type="submit" value="Submit" class="btn btn-primary" />
				</fieldset>
			</form>     
              	    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
$(function() {
var wdth = document.getElementById('drawForm').offsetWidth;
	 var $canvas,
        onResize = function(event) {
          $canvas.attr({
            height: '300px',
            width: '450px'
          });
        };

 $canvas = $('canvas');
      window.addEventListener('orientationchange', onResize, false);
      window.addEventListener('resize', onResize, false);
      onResize();

var instance = $('form').signaturePad({
        drawBezierCurves: true,
        variableStrokeWidth:true,
        drawOnly: true,
        defaultAction: 'drawIt',
        validateFields: false,
        lineWidth: 0,
        output: null,
        sigNav: null,
        name: null,
        typed: null,
        clear: 'input[type=reset]',
        typeIt: null,
        drawIt: null,
        typeItDesc: null,
        drawItDesc: null,
	validateFields :true,
	errorClass :'error',
	errorMessageDraw :'Please sign the document',
	onDrawEnd :function (){
		document.getElementById('sign').value = instance.getSignatureImage();
	}

      });

});
</script>