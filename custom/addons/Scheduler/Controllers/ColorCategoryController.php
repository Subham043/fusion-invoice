<?php

/**
 * This file is part of Scheduler Addon for FusionInvoice.
 * (c) Cytech <cytech@cytech-eng.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Addons\Scheduler\Controllers;

use Addons\Scheduler\Models\Category;
use Addons\Scheduler\Models\Schedule;
use Illuminate\Http\Request;

class ColorCategoryController extends Controller {
	public function index() {
		$categories = Category::select('*')
            ->limit(10)
            ->orderBy('id')
            ->get();

		return view( 'Scheduler::color.index' )->with( 'categories', $categories );
	}


	public function show( $id ) {
		$categories = Category::find( $id );

		return view('Scheduler::schedule.categories.show', compact( 'categories' ) );
	}

	public function edit( $id ) {
		$categories = Category::find( $id );

		return view( 'Scheduler::color.edit', compact( 'categories' ) );
	}

	public function update( Request $request, $id ) {
		$categories             = Category::find( $id );
		$categories->text_color = $request->text_color;
		$categories->bg_color   = $request->bg_color;
		$categories->save();

		return redirect()->route( 'scheduler.colors.index' )->with( 'alertSuccess', 'Successfully Edited colors!' );
	}

}
