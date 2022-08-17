<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\InventoryGroupList\Models;

use FI\Events\InvoiceItemSaving;
use FI\Events\InvoiceModified;
use FI\Support\CurrencyFormatter;
use FI\Support\NumberFormatter;
use Illuminate\Database\Eloquent\Model;
use FI\Modules\Inventory\Models\Inventory;
use Illuminate\Support\Facades\DB;

class InventoryGroupListItem extends Model
{
    protected $guarded = ['id', 'item_id'];
	protected $table = 'inventory_group_list_items';

    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */    

    public function inventorygrouplist()
    {
        return $this->belongsTo('FI\Modules\InventoryGroupList\Models\InventoryGroupList');
    }
    
    public function inventory()
    {
        return $this->belongsTo('FI\Modules\Inventory\Models\Inventory', 'inventory_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFormattedQuantityAttribute()
    {
        //return NumberFormatter::format($this->attributes['quantity'],null,0.0);
        return NumberFormatter::format2($this->attributes['quantity'],null,0.0);
    }

    public function getFormattedNumericPriceAttribute()
    {
        return NumberFormatter::format($this->attributes['price']);
    }

    public function getFormattedPriceAttribute()
    {
        return CurrencyFormatter::format($this->attributes['price'], $this->invoice->currency);
    }

    public function getFormattedDescriptionAttribute()
    {
        return nl2br($this->attributes['description']);
    }

 }
