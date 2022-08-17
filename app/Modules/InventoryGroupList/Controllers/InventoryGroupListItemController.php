<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\InventoryGroupList\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\InventoryGroupList\Models\InventoryGroupListItem;

class InventoryGroupListItemController extends Controller
{
    public function delete()
    {
        InventoryGroupListItem::destroy(request('id'));
    }
}