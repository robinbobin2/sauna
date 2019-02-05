<?php

namespace App\Http\Controllers;

use App\Link;
use App\Page;
use App\User;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function edit_page($id, Request $request) {
        $page = Page::findOrFail($id);
		$page->update($request->all());
		$page->save();
		return response()->json($page);
    }
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
		$jsonOutput = array('user' => $user,'page'=>$page,'links'=>$links );
		// $page->links()->save($link);
		// dd($instagram);
		// dd($links);
		return response()->json($jsonOutput);
	}

	public function edit_link($id, Request $request) {

		$link = Link::findOrFail($id);
		$link->update($request->all());
		$link->save();
		return response()->json($link);
	}

	public function remove_link(Request $request) {

		$link = Link::where('id', $request->id)->first();
		$link->destroy();
		return response()->json('deleted');
	}
    //
}
