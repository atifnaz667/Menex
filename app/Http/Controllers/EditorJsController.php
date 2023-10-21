<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\DomCrawler\Crawler;

class EditorJsController extends Controller
{
    public function fetchUrlMetadata(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid URL'], 400);
        }

        $url = $request->input('url');
        $client = new Client();

        try {
            $response = $client->get($url);
            $html = (string)$response->getBody();
            $crawler = new Crawler($html);

            $metadata = [
                'title' => $crawler->filter('title')->text(),
                'description' => $crawler->filter('meta[name="description"]')->attr('content'),
                'image' => $crawler->filter('meta[property="og:image"]')->attr('content'),
            ];

            // Return the metadata in JSON format
            return response()->json(['metadata' => $metadata]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error while fetching URL'], 500);
        }
    }
}
