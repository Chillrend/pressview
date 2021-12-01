<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Header;
use http\Header\Parser;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class TableController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function render(Request $request)
    {
        $client = new Client([
            'base_uri' => 'http://press.pnj.ac.id'
        ]);

        if ($request->has('search')) {
            $url = 'http://press.pnj.ac.id/?rest_route=%2Fwp%2Fv2%2Fposts&_embed=wp%3Aterm,tag&_field=date&_field=title&_field=content&per_page=20&search=' . $request->get('search');
            $response = $client->get($url);
        } elseif ($request->has('page')) {
            $base = 'http://press.pnj.ac.id/?rest_route=%2Fwp%2Fv2%2Fposts&_embed=wp%3Aterm,tag&_field=date&_field=title&_field=content&per_page=20&page=' . $request->get('page');
            $search =  $request->has('search') ? ('&search=' . $request->get('search')) : '';
            $url = $base . $search;
            $response = $client->get(urldecode($url));
        } else {
            $url = 'http://press.pnj.ac.id/?rest_route=%2Fwp%2Fv2%2Fposts&_embed=wp%3Aterm,tag&_field=date&_field=title&_field=content&per_page=20';
            $response = $client->get($url);
        }

        if ($response->getStatusCode() > 199 && $response->getStatusCode() < 300) {
            $responseArray = json_decode($response->getBody());
        } else {
            return response()->json(['message' => 'Not Found!'], 404);
        }

        $meta = [
            'page' => $request->has('page') ? $request->get('page') : 1,
            'URL' => $url,
            'X-WP-Total' => $response->getHeader('X-WP-Total') !== null ? $response->getHeader('X-WP-Total')[0] : 0,
            'X-WP-TotalPages' => $response->getHeader('X-WP-TotalPages') !== null ? $response->getHeader('X-WP-TotalPages')[0] : 0,
        ];


        $data = [];
        for ($i = 0; $i < count($responseArray); $i++) {
            $responseObject = [
                'id' => $responseArray[$i]->id,
                'date' => Carbon::parse($responseArray[$i]->date)->toDateString(),
                'link' => $responseArray[$i]->link,
                'title' => html_entity_decode($responseArray[$i]->title->rendered),
                'content' => self::removeContentAfter(html_entity_decode(strip_tags($responseArray[$i]->content->rendered)), 'Halaman') . ' Halaman',
            ];

            $categories = [];
            for ($j = 0; $j < count($responseArray[$i]->_embedded->{'wp:term'}[0]); $j++) {
                array_push($categories, $responseArray[$i]->_embedded->{'wp:term'}[0][$j]->name);
            }

            $responseObject['categories'] = join(', ', $categories);
            array_push($data, $responseObject);
        }

        $mergedResponse = [
            'meta' => $meta,
            'data' => $data
        ];

        return view('table', $mergedResponse);
    }

    public static function removeContentAfter(string $string, string $after): string
    {
        return false !== ($pos = strpos($string, $after)) ? substr($string, 0, $pos) : $string;
    }
}
