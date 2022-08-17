<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\InventoryGroupList\Requests;

use Illuminate\Foundation\Http\FormRequest;
use FI\Support\NumberFormatter;

class InventoryGroupListStoreRequest extends FormRequest
{

	public function authorize()
    {
        return true;
    }

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

	$request['custom_price']    = NumberFormatter::unformat($request['custom_price']);
        $this->replace($request);
    }

    public function rules()
    {
        return [
            'summary'           => 'max:255',
            'name'      => 'required',
            //'items.*.name'      => 'required_with:items.*.price,items.*.quantity',
            'items.*.name'      => 'required',
            'items.*.quantity'  => 'required_with:items.*.price,items.*.name|numeric',
            'items.*.price'     => 'required_with:items.*.name,items.*.quantity|numeric',
        ];
    }}