<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use App\Models\ShortUrl;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;

class ApiController extends Controller
{

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     * Here just show the index page of api the user have api key or not
     */
    public function index()
    {
        $userId = auth()->id();
        $apiKey = ApiKey::query()->where('user_id', $userId)->toBase()->first();
        $apiKey = $apiKey->key ?? "You don't have any api key";
        return view('api.index', compact('apiKey'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * Here just generate the api key, If the user already have then update new one
     */
    public function generateKey()
    {
        $generateApiKey = Str::random(30).Carbon::now()->timestamp;

        try {
            $apiKey = ApiKey::query()->firstOrNew(['user_id' =>  auth()->id()]);
            $apiKey->user_id = auth()->id();
            $apiKey->key = $generateApiKey;
            $apiKey->save();
        } catch (\Exception $exception) {

        }

        return redirect()->route('api.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * Here just make a api call for 3rd party with have api key
     */
    public function makeShortUrl(Request $request)
    {
        $validation = $request->validate([
            'original_url' => 'required|url:http,https',
        ]);

        try {
            $apiKey = $request->header('api-Key');
            $check = ApiKey::query()->select('key')->where('key', $apiKey)->toBase()->first();
            if(isset($check)) {
                $requestOriginalUrl = $request->original_url;
                $shortUrl = uniqid();
                $data = [
                    'original_url' => Crypt::encrypt($requestOriginalUrl),
                    'short_url' => $shortUrl,
                    'user_id' => auth()->id() ?? null,
                    'session_id' => session()->get('_token') ?? null,
                ];
                $shortUrl = ShortUrl::create($data);
                $status = 'success';
                $data = [
                    'message' => 'Successfully added.',
                    'original_url' => $request->original_url,
                    'short_url' => $shortUrl->short_url,
                ];
            } else {
                $status = 'error';
                $data = [
                    'message' => 'Api Key Not Found',
                ];
            }
        } catch (\Exception $exception) {
            $status = 'error';
            $data = [
                'message' => 'Something wrong.',
            ];
        }

        return response()->json([$status, $data]);

    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     * It's a demo file for api key
     */
    public function test()
    {
        return view('api.test');
    }
}
