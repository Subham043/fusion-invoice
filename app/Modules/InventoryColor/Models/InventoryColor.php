<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\InventoryColor\Models;

use FI\Support\CurrencyFormatter;
use FI\Support\NumberFormatter;
use FI\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryColor extends Model
{
    use Sortable;

    /**
     * Guarded properties
     * @var array
     */
     
    protected $table = 'inventory_color';
    
    protected $guarded = ['id'];

    protected $sortable = ['name'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFormattedPriceAttribute()
    {
        return CurrencyFormatter::format($this->attributes['price']);
    }

    public function getFormattedNumericPriceAttribute()
    {
        return NumberFormatter::format($this->attributes['price']);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeKeywords($query, $keywords)
    {
        if ($keywords)
        {
            $keywords = explode(' ', $keywords);

            foreach ($keywords as $keyword)
            {
                if ($keyword)
                {
                    $keyword = strtolower($keyword);

                    $query->where(DB::raw("CONCAT_WS('^',LOWER(name))"), 'LIKE', "%$keyword%");
                }
            }
        }

        return $query;
    }
    
    public static function getList()
    {
        return self::orderBy('name')->pluck('name', 'name')->all();
    }
}