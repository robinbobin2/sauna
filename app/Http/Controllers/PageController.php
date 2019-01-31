<?php

namespace App\Http\Controllers;

use App\Link;
use App\Page;
use App\User;
use Illuminate\Http\Request;

class PageController extends Controller
{
	public function add_link(Request $request) {
		$user = $request->user();
		$page = Page::where('user_id', $user->id)->first();
		$link = Link::create([
			'page_id'=>$page->id,
			'link_address'=>$request->link_address,
			'title'=>$request->title,
			'description'=>$request->description,
			'image'=>'',
		]);
		// $page->links()->save($link);
		return response()->json($link);
	}

	public function get_links($instagram) {
		$user = User::where('instagram_name', $instagram)->first();
		$page = Page::where('user_id', $user->id)->first();
		$links = Link::where('page_id', $page->id)->get();
		// $page->links()->save($link);
		// dd($instagram);
		// dd($links);
		return response()->json($links);
	}
    //
}
