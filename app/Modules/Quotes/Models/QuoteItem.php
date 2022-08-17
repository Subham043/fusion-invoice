<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Quotes\Models;

use FI\Events\QuoteItemSaving;
use FI\Events\QuoteModified;
use FI\Support\CurrencyFormatter;
use FI\Support\NumberFormatter;
use Illuminate\Database\Eloquent\Model;
use FI\Modules\Inventory\Models\Inventory;
use Illuminate\Support\Facades\DB;

class QuoteItem extends Model
{
    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($quoteItem)
        {
            $quoteItem->amount()->delete();
        });

        static::deleted(function($quoteItem)
        {
            if ($quoteItem->quote)
            {
                event(new QuoteModified($quoteItem->quote));
            }
        });

        static::saving(function($quoteItem)
        {
            event(new QuoteItemSaving($quoteItem));
        });

        static::saved(function($quoteItem)
        {
            event(new QuoteModified($quoteItem->quote));
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function amount()
    {
        return $this->hasOne('FI\Modules\Quotes\Models\QuoteItemAmount', 'item_id');
    }

    public function quote()
    {
        return $this->belongsTo('FI\Modules\Quotes\Models\Quote');
    }

    public function taxRate()
    {
        return $this->belongsTo('FI\Modules\TaxRates\Models\TaxRate');
    }

    public function taxRate2()
    {
        return $this->belongsTo('FI\Modules\TaxRates\Models\TaxRate', 'tax_rate_2_id');
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
        return NumberFormatter::format($this->attributes['quantity']);
    }

    public function getFormattedNumericPriceAttribute()
    {
        return NumberFormatter::format($this->attributes['price']);
    }

    public function getFormattedPriceAttribute()
    {
        return CurrencyFormatter::format($this->attributes['price'], $this->quote->currency);
    }

    public function getFormattedDescriptionAttribute()
    {
        return nl2br($this->attributes['description']);
    }
    
    public function getReservedQuantity()
    {

        $query = DB::table('invoices')
            ->select('invoice_items.quantity','invoice_items.name', DB::raw("sum(invoice_items.quantity) as sum"))
            ->join('invoice_items', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->where('invoices.event_date',$this->quote->event_date)
            ->where('invoice_items.name',$this->name)
            ->whereIn('invoices.invoice_status_id', array(1,2))
            ->get();
            
        $invoice = $query[0]->sum==null ? 0 : $query[0]->sum;
        
        $query = DB::table('quotes')
            ->select('quote_items.quantity','quote_items.name', 'quotes.id', 'quotes.event_date', DB::raw("sum(quote_items.quantity) as sum"))
            ->join('quote_items', 'quote_items.quote_id', '=', 'quotes.id')
            ->where('quotes.event_date',$this->quote->event_date)
            ->where('quote_items.name',$this->name)
            ->whereIn('quotes.quote_status_id', array(2,3))
            ->get();
            
        $quote = $query[0]->sum==null ? 0 : $query[0]->sum;
        
        return (int)$invoice + (int)$quote;
    }
    
    public function getAllocatedQuantity()
    {
        
        $query = DB::table('invoices')
            ->select('invoice_items.quantity','invoice_items.name', DB::raw("sum(invoice_items.quantity) as sum"))
            ->join('invoice_items', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->where('invoices.event_date',$this->quote->event_date)
            ->where('invoice_items.name',$this->name)
            ->whereIn('invoices.invoice_status_id', array(3,4))
            ->get();

        if($query[0]->sum==null){
            return 0;
        }
        return (int)$query[0]->sum;
    }
    
    public function getAvailableQuantity()
    {
        $inventory = Inventory::where('name',$this->name)->first();

        $query = DB::table('invoices')
            ->select('invoice_items.quantity','invoice_items.name', DB::raw("sum(invoice_items.quantity) as sum"))
            ->join('invoice_items', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->where('invoices.event_date',$this->quote->event_date)
            ->where('invoice_items.name',$this->name)
            ->whereIn('invoices.invoice_status_id', array(3,4))
            ->get();
        if($query[0]->sum==null){
            if(!empty($inventory)){
                return (int)$inventory->total;
            }
            return 0;
        }
        return (int)$inventory->total-(int)$query[0]->sum;
    }
}
