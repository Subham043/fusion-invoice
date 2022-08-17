<?php

/**
 * This file is part of Schedule Addon for FusionInvoice.
 * (c) Cytech <cytech@cytech-eng.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Addons\Scheduler;


use Illuminate\Support\ServiceProvider;
use Addons\Scheduler\Models\Setting;
use Config;
use Schema;
use Form;

class AddonServiceProvider extends ServiceProvider
{


    public function boot()
    {
	    require_once __DIR__ . '/vendor/autoload.php';

	    if (Schema::hasTable('schedule_settings')) {
		    foreach (Setting::all() as $setting) {
			    Config::set('schedule_settings.'.$setting->setting_key, $setting->setting_value);
		    }
	    }

	    Form::macro('breadcrumbs', function ($status = false) {
		    $str = '<ol class="breadcrumb">';

		    // Get the breadcrumbs by exploding the current path.
		    $basePath = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/') + 1);
		    $parts = explode('?', isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');
		    $path = $parts[0];

		    if ($basePath != '/') {
			    $path = str_replace($basePath, '', $path);
		    }
		    $crumbs = explode('/', $path);

		    foreach ($crumbs as $key => $val) {
			    if (is_numeric($val)) {
				    unset($crumbs[$key]);
			    }
		    }

		    $crumbs = array_values($crumbs);
		    $modcrumb = null;
		    for ($i = 0; $i < count($crumbs); $i++) {
			    $crumb = trim($crumbs[$i]);
			    if (! $crumb) {
				    continue;
			    }
			    if ($crumb == 'company') {
				    return '';
			    }

			    if (!$modcrumb) {
				    $modcrumb = $crumb;
				    $name     = trans( 'Scheduler::texts.'.$crumb );
			    } elseif ($modcrumb){
				    $name     = trans( 'Scheduler::texts.'.$crumb );
			    } else {
				    $name = trans("Scheduler::texts.$crumb");
			    }

			    if ($i == count($crumbs) - 1) {
				    $str .= "<li class='active'>$name</li>";
			    } elseif ($i <= count($crumbs) - 2 && $i >= 2) {
				    $str .= '<li>'.link_to($modcrumb.'/'.$crumb, $name).'</li>';
			    }else {
				    $str .= '<li>'.link_to($crumb, $name).'</li>';
			    }
		    }

		    if ($status) {
			    $str .= $status;
		    }

		    return $str . '</ol>';
	    });

	    //set view namespace - fi uses addLocation and there are conflicts with
	    // identical view names between addons
	    $this->app->view->addNamespace('Scheduler',base_path('custom/addons') . '/Scheduler/Views');

    }

    public function register()
    {
        //
    }
}
