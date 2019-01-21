<?php

namespace App\Http\Controllers;

use App\Link;
use App\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
	public function add_link(Request $request) {
		$user = $request->user();
		$page = Page::where('user_id', $user->id);
		$link = Link::create($request->all());
		$page->links()->save($link);
		return response()->json($link);
	}
    //
}
