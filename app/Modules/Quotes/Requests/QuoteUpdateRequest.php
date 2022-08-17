<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Quotes\Requests;

use FI\Support\NumberFormatter;

class QuoteUpdateRequest extends QuoteStoreRequest
{
    public function prepareForValidation()
    {
        $request = $this->all();

        if (isset($request['items']))
        {
            foreach ($request['items'] as $key => $item)
            {
                $request['items'][$key]['quantity'] = NumberFormatter::unformat($item['quantity']);
                $request['items'][$key]['price']    = NumberFormatter::unformat($item['price']);
            }
        }

	if (isset($request['group_items']))
        {
            foreach ($request['group_items'] as $key => $item)
            {
                $request['group_items'][$key]['group_quantity'] = NumberFormatter::unformat($item['group_quantity']);
                $request['group_items'][$key]['group_price']    = NumberFormatter::unformat($item['group_price']);
            }
        }

        $this->replace($request);
    }

    public function rules()
    {
        return [
            'summary'          => 'max:255',
            'quote_date'       => 'required',
            'number'           => 'required',
            'quote_status_id'  => 'required',
            'exchange_rate'    => 'required|numeric',
            'template'         => 'required',
            // 'items.*.name'     => 'required_with:items.*.price,items.*.quantity',
            'items.*.name'     => 'required',
            'items.*.quantity' => 'required_with:items.*.price,items.*.name|numeric',
            'items.*.price'    => 'required_with:items.*.name,items.*.quantity|numeric',
	    'group_items.*.group_name'      => 'required',
            'group_items.*.group_quantity'  => 'required_with:groupitems.*.price,items.*.name|numeric',
            'group_items.*.group_price'     => 'required_with:groupitems.*.name,items.*.quantity|numeric',
        ];
    }
}