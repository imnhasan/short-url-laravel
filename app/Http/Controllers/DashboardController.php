<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     * Here show the user's short links with click count
     * Find the Url in #dashboard.php
     */
    public function index()
    {
        $title = 'Dashboard';
        $shortUrls = ShortUrl::query()->where('user_id', auth()->id())->paginate(5);
        $user = auth()->user();

        return view('dashboard.index',  compact('title', 'shortUrls', 'user'));
    }
}
