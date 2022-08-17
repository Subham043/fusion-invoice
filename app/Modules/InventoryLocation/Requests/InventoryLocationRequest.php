<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\InventoryLocation\Requests;

use FI\Support\NumberFormatter;
use Illuminate\Foundation\Http\FormRequest;

class InventoryLocationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'name'  => trans('fi.name')
        ];
    }

    public function prepareForValidation()
    {
        $request = $this->all();

        $this->replace($request);
    }

    public function rules()
    {
        return [
            'name'  => 'required'
        ];
    }
}