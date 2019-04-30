<?php

namespace App\Http\Controllers;

use App\Link;
use App\Page;
use App\User;
use App\Stat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function edit_page($id, Request $request) {
        $page = Page::findOrFail($id);
        $input = $request->all();
        if ($file = $request->file('avatar')) {
            // dd($file);
            $name = time() . $file->getClientOriginalName();

            $file->move('avatars', $name);
            $input->avatar = '/avatars/'.$name;
        }
		$page->update($input);
		return response()->json($page);
    }
    public function edit_theme(Request $request) {
        $page = Page::findOrFail($request->id);
        $page->update(['template_id' => $request->theme]);

		return response()->json($page);
    }
	public function add_link(Request $request) {
		$user = $request->user();
        $page = Page::where('user_id', $user->id)->first();
        $image = '';
        if($file = $request->file('image')) {
            $name = time() . $file->getClientOriginalName();

            $file->move('link_images', $name);
            $image = '/link_images/'.$name;
        }
		$link = Link::create([
			'page_id'=>$page->id,
			'link_address'=>$request->link_address,
			'title'=>$request->title,
			'description'=>$request->description,
			'image'=>$image,
		]);
		// $page->links()->save($link);
		return response()->json($link);
	}

	public function get_links($instagram) {

		$user = User::where('instagram_name', $instagram)->first();
        $page = Page::where('user_id', $user->id)->first();
        if($user->account_type == 1) {
            $links = Link::where('page_id', $page->id)->take(4)->get();
        } else {
            $links = Link::where('page_id', $page->id)->get();

        }
		$jsonOutput = array('user' => $user,'page'=>$page,'links'=>$links );
		// $page->links()->save($link);
		// dd($instagram);
		// dd($links);
		return response()->json($jsonOutput);
	}

	public function edit_link($id, Request $request) {

		$link = Link::findOrFail($id);
		$input = $request->all();
		if($file = $request->file('image')) {
            $name = time() . $file->getClientOriginalName();

            $file->move('link_images', $name);
            $image = '/link_images/'.$name;
            $input->file = $image;
        }
		$link->update($input);
		return response()->json($link);
	}

	public function remove_link(Request $request) {
        $user = Auth::user();
        $page = Page::where('user_id', $user->id)->first();
        $link = Link::where(function ($query) use ($page, $request) {
            $query->where('id', $request->id)
            ->where('page_id', $page->id);
        })
        ->delete();

		return response()->json('deleted');
    }

    public function avatar(Request $request) {
        $user = Auth::user();
        if ($file = $request->file('avatar')) {
            // dd($file);
            $name = time() . $file->getClientOriginalName();

            $file->move('avatars', $name);
            $user->avatar = '/avatars/'.$name;
            $user->save();
            return response()->json($user);
        } else {
            return response()->json('no avatar');
        }
    }

    public function add_stat(Request $request) {
        $stat = Stat::where(function ($query) use ($request) {
            $query->where('page_id', $request->page_id)
            ->where('date', date('d.m.Y'));
        })
        ->first();
        if ($stat == null) {
            $stat = Stat::create(['page_id'=>$request->page_id, 'count'=>1, 'date'=>date('d.m.Y')]);
        } else {
            $stat->count = $stat->count+1;
            $stat->save();
        }

        return response()->json($stat);
    }
    public function retrieve_stat(Request $request) {
        $stat = Stat::where('page_id', $request->page_id)->get();

        return response()->json($stat);
	}
    //
}
