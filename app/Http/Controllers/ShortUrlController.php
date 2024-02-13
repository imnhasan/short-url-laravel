<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShortUrlRequest;
use App\Models\ShortUrl;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

class ShortUrlController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse
     * This is the main index page where people can make their short url
     */
    public function index()
    {
        $title = 'Sort Url';

        $requestShortUrl = request()->segment(1);

        // This one check is it short url link or application link
        if(request()->segment(1)) {
            $getShorUrl = ShortUrl::query()
                ->select('id', 'original_url', 'click_count')
                ->where('short_url', $requestShortUrl)
                ->first();

            if($getShorUrl){
                $getShorUrl->click_count++;
                $getShorUrl->update();
                return redirect()->away(Crypt::decrypt($getShorUrl->original_url));
            } else {
                return to_route('short_url.index');
            }
        }

        return view('shorturl', compact('title'));
    }

    /**
     * @param ShortUrlRequest $request -------------
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * Here the short url store
     */
    public function store(ShortUrlRequest $request)
    {

        $request->validate([
            'original_url' => 'required|url:http,https',
        ]);

        $requestOriginalUrl = $request->original_url;

        // my old way to make unique id
        //$shortUrl = Str::random(6).Carbon::now()->timestamp;
        $shortUrl = uniqid(); // update

        $data = [
            'original_url' => Crypt::encrypt($requestOriginalUrl),
            'short_url' => $shortUrl,
            'user_id' => auth()->id() ?? null,
            'session_id' => session()->get('_token'),
        ];
        try {
            $shortUrl = ShortUrl::create($data);
            $status = 'success';
            $message = $shortUrl;
        } catch (\Exception $exception) {
            $status = 'error';
            $message = $exception->getMessage();
        }

        return redirect()->route('short_url.index')->with($status, $message);

    }

    /**
     * @param ShortUrlRequest $request -------------
     * @return \Illuminate\Http\RedirectResponse
     * If the user make short url with login mode then he/she can update their short url & original url
     */
    public function update(ShortUrlRequest $request)
    {
        $shortUrl = ShortUrl::query()->find($request->id);
        $shortUrl->original_url = Crypt::encrypt($request->original_url);
        if($request->short_url != $shortUrl->short_url) {
            if($request->validate(['short_url' => 'unique:short_urls,short_url']))
            $shortUrl->short_url = $request->short_url;
        }
        try {
            $shortUrl->update();
            $status = 'success';
            $message = 'Url successfully update.';
        } catch (\Exception $exception) {
            $status = 'error';
            $message = 'Url not update.';
        }

        return to_route('user.dashboard.index')->with($status, $message);
    }

    /**
     * @param ShortUrl $shortUrl --------------
     * @return \Illuminate\Http\RedirectResponse
     * Here we check the data first is it owner data or not then we delete
     * We can also do this by openssl
     */
    public function destroy(ShortUrl $shortUrl)
    {
        if($shortUrl->user_id == \auth()->id()){
            $shortUrl->delete();
            $status = 'success';
            $message = 'Url successfully delete.';
        } else {
            $status = 'error';
            $message = 'Url not delete.';
        }
        return redirect()->route('user.dashboard.index')->with($status, $message);
    }
}
