<?php
/**
 * @package dompdf
 * @link    http://dompdf.github.com/
 * @author  Benj Carson <benjcarson@digitaljunkies.ca>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */
namespace Dompdf\FrameReflower;

use Dompdf\FrameDecorator\Block as BlockFrameDecorator;
use Dompdf\FrameDecorator\Table as TableFrameDecorator;
use Dompdf\FrameDecorator\TableRowGroup as TableRowGroupFrameDecorator;

/**
 * Reflows table row groups (e.g. tbody tags)
 *
 * @package dompdf
 */
class TableRowGroup extends AbstractFrameReflower
{

    /**
     * TableRowGroup constructor.
     * @param TableRowGroupFrameDecorator $frame
     */
    function __construct(TableRowGroupFrameDecorator $frame)
    {
        parent::__construct($frame);
    }

    /**
     * @param BlockFrameDecorator|null $block
     */
    function reflow(BlockFrameDecorator $block = null)
    {
        /** @var TableRowGroupFrameDecorator */
        $frame = $this->_frame;

        $page = $frame->get_root();

        $style = $frame->get_style();
        $cb = $frame->get_containing_block();

        foreach ($frame->get_children() as $child) {
            // Bail if the page is full
            if ($page->is_full()) {
                break;
            }

            $child->set_containing_block($cb["x"], $cb["y"], $cb["w"], $cb["h"]);
            $child->reflow();

            // Check if a split has occurred
            $page->check_page_break($child);
        }

        $table = TableFrameDecorator::find_parent_table($frame);
        $cellmap = $table->get_cellmap();

        // Stop reflow if a page break has occurred before the frame, in which
        // case it is not part of its parent table's cell map yet
        if ($page->is_full() && !$cellmap->frame_exists_in_cellmap($frame)) {
            return;
        }

        $style->width = $cellmap->get_frame_width($frame);
        $style->height = $cellmap->get_frame_height($frame);

        $frame->set_position($cellmap->get_frame_position($frame));
    }
}
