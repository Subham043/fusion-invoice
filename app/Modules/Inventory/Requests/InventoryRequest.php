<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Inventory\Requests;

use FI\Support\NumberFormatter;
use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'name'  => trans('fi.name'),
            'price' => trans('fi.price'),
	    'purchase-price' => 'Purchase Price',
            'total' => trans('fi.total'),
        ];
    }

    public function prepareForValidation()
    {
        $request = $this->all();

        $request['price'] = NumberFormatter::unformat($request['price']);

	$request['purchase-price'] = NumberFormatter::unformat($request['purchase-price']);

        $this->replace($request);
    }

    public function rules()
    {
        return [
            'name'  => 'required',
            'price' => 'required|numeric',
	    'purchase-price' => 'numeric',
            'total' => 'required|numeric',
        ];
    }
}