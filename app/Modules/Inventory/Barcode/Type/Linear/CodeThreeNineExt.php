<?php
/**
 * CodeThreeNineExt.php
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2010-2016 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 *
 * This file is part of tc-lib-barcode software library.
 */

namespace FI\Modules\Inventory\Barcode\Type\Linear;

use \FI\Modules\Inventory\Barcode\Exception as BarcodeException;

/**
 * Com\Tecnick\Barcode\CodeThreeNineExt
 *
 * CodeThreeNineExt Barcode type class
 * CODE 39 EXTENDED
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2010-2016 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 */
class CodeThreeNineExt extends \FI\Modules\Inventory\Barcode\Type\Linear\CodeThreeNineExtCheck
{
    /**
     * Barcode format
     *
     * @var string
     */
    protected $format = 'C39E';

    /**
     * Format code
     */
    protected function formatCode()
    {
        $this->extcode = '*'.$this->getExtendCode(strtoupper($this->code)).'*';
    }
}
