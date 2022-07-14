<?php

namespace App\Http\Controllers;

use App\Http\Requests\LinksRequest;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LinkController extends Controller
{
    public function send(LinksRequest $request){
        $url = $request->input('url');
        $result['success'] = false;
        do {
            if (!$url) {
                $result['message'] = 'Не передана ссылка';
                break;
            }
            $code = Str::random(6);
            $link = Link::create([
                'source_link' => $url,
                'link_key' => $code,
            ]);
            $result['shortLink'] = 'http://127.0.0.1:8000/links/'.$code;
        }while (false);
        $result['success'] = true;

        return response()->json($result);
    }
    public function direct(string $prefix)
    {
        $link = Link::where(['link_key' => $prefix])->firstOrFail();
        if($link){
            $link->count++;
            $link->save();
            return redirect()->away($link->source_link);
        }

        throw new NotFoundHttpException('Prefix not found');
    }
}
