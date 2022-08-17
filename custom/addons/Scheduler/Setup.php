<?php

/**
 * This file is part of Scheduler Addon for FusionInvoice.
 * (c) Cytech <cytech@cytech-eng.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Addons\Scheduler;

use Illuminate\Support\Facades\Schema;

class Setup
{
    public $properties = [
        // This is the name of the module.
        'name'            => 'Scheduler',

        // The name of the author.
        'author_name'     => 'Dave Albright',

        // The URL to the author.
        'author_url'      => 'https://www.cytech-eng.com',

        // The viewfolder.viewname to the navigation menu file, if exists.
        // This file must have a .blade.php extension.
        'navigation_menu' => 'schedule._navigation',

        // The viewfolder.viewname to the navigation report file, if exists.
        // This file must have a .blade.php extension.
        'navigation_reports' => 'schedule._reports',

        // The viewfolder.viewname to the system menu view file, if exists.
        // This file must have a .blade.php extension.
        'system_menu'        => 'schedule._system'
    ];

    public function install()
    {

    }

    public function uninstall()
    {
        

    }

}