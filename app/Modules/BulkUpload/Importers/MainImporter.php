<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\BulkUpload\Importers;

abstract class MainImporter
{
    protected $obj;

    public function __construct($obj)
    {
        $this->obj = $obj;

    }

    abstract public function importData();

}