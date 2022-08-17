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

use Carbon\Carbon;
use FI\Events\InvoiceCreated;
use FI\Events\InvoiceCreating;
use FI\Events\InvoiceDeleted;
use FI\Support\CurrencyFormatter;
use FI\Support\DateFormatter;
use FI\Support\FileNames;
use FI\Support\HTML;
use FI\Support\NumberFormatter;
use FI\Support\Statuses\InvoiceStatuses;
use FI\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use File;


class InventoryGroupList extends Model
{
    use Sortable;

    protected $guarded = ['id'];

    protected $sortable = [
        'name',
        'total',
    ];

	protected $table = 'inventory_group_list';

	public static function boot()
    {
        parent::boot();
	self::created(function($model){
            // ... code here
		//$url = preg_replace("(^https?://)", "", route('inventorygrouplist.edit',$model->id));
		$url = "GRP-".$model->id;

		$barcode = new \FI\Modules\Inventory\Barcode\Barcode();
		$bobj = $barcode->getBarcodeObj('C128', $url, 0, -30, 'black', array(0, 0, 0, 0));
  		Storage::put('public/barcode/inventory-group-list-'.$model->id.'-barcode.png', $bobj->getPngData());
		File::move(storage_path('app/public/barcode/inventory-group-list-'.$model->id.'-barcode.png'), public_path('assets/barcode/inventory-group-list-'.$model->id.'-barcode.png'));
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */


    public function items()
    {
        return $this->hasMany('FI\Modules\InventoryGroupList\Models\InventoryGroupListItem')
            ->orderBy('display_order');
    }

    // This and items() are the exact same. This is added to appease the IDE gods
    // and the fact that Laravel has a protected items property.
    public function inventoryGroupListItems()
    {
        return $this->hasMany('FI\Modules\InventoryGroupList\Models\InventoryGroupListItem')
            ->orderBy('display_order');
    }


    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Gathers a summary of both invoice and item taxes to be displayed on invoice.
     *
     * @return array
     */
    public function getSummarizedTaxesAttribute()
    {
        $taxes = [];

        foreach ($this->items as $item)
        {
            if ($item->taxRate)
            {
                $key = $item->taxRate->name;

                if (!isset($taxes[$key]))
                {
                    $taxes[$key]              = new \stdClass();
                    $taxes[$key]->name        = $item->taxRate->name;
                    $taxes[$key]->percent     = $item->taxRate->formatted_percent;
                    $taxes[$key]->total       = $item->amount->tax_1;
                    $taxes[$key]->raw_percent = $item->taxRate->percent;
                }
                else
                {
                    $taxes[$key]->total += $item->amount->tax_1;
                }
            }

            if ($item->taxRate2)
            {
                $key = $item->taxRate2->name;

                if (!isset($taxes[$key]))
                {
                    $taxes[$key]              = new \stdClass();
                    $taxes[$key]->name        = $item->taxRate2->name;
                    $taxes[$key]->percent     = $item->taxRate2->formatted_percent;
                    $taxes[$key]->total       = $item->amount->tax_2;
                    $taxes[$key]->raw_percent = $item->taxRate2->percent;
                }
                else
                {
                    $taxes[$key]->total += $item->amount->tax_2;
                }
            }
        }

        foreach ($taxes as $key => $tax)
        {
            $taxes[$key]->total = CurrencyFormatter::format($tax->total, $this->currency);
        }

        return $taxes;
    }


    public function scopeKeywords($query, $keywords = null)
    {
        if ($keywords)
        {
            $keywords = strtolower($keywords);

            $query->where(DB::raw('lower(number)'), 'like', '%' . $keywords . '%');
            if(preg_match("/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/", $keywords) === 1) {
                $query->orWhere('invoices.invoice_date', 'like', '%' . DateFormatter::unformat($keywords) . '%');
                $query->orWhere('invoices.due_at', 'like', '%' . DateFormatter::unformat($keywords) . '%');
                $query->orWhere('invoices.event_date', 'like', '%' . DateFormatter::unformat($keywords) . '%');
            }
                
                $query->orWhere('summary', 'like', '%' . $keywords . '%');
                $query->orWhereIn('client_id', function ($query) use ($keywords)
                {
                    $query->select('id')->from('clients')->where(DB::raw("CONCAT_WS('^',LOWER(name),LOWER(unique_name))"), 'like', '%' . $keywords . '%');
                });
        }

        return $query;
    }

	public static function getList()
    {
        return self::orderBy('name')->pluck('name', 'name')->all();
    }

}
