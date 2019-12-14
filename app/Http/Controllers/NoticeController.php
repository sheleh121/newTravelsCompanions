<?php

namespace App\Http\Controllers;

use App\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function get() {
        $notices = Auth::user()->notices()->where('seen', false)->orderByDesc('updated_at')->get();

        foreach ($notices as $notice) {
            $notice->travel_name = $notice->travel()->pluck('name')->first();
        }
        return response($notices);
    }

    public function seen(Request $request) {
        $notice = Notice::find($request['notice_id']);

        if ($notice->user_id == Auth::user()->id) {
            $notice->seen = true;
            $notice->save();
        }
    }
}
