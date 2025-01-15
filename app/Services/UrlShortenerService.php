<?php

namespace App\Services;

use App\Contracts\UrlShortenerContract;
use App\Models\Links;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UrlShortenerService implements UrlShortenerContract
{
    /**
     * @throws Exception
     */
    public function encode(string $url): string
    {
        //Check that the incoming URL doesn't already exist
        $checkLinks = Links::where('original', $url)->first();

        if ($checkLinks) {
            return $checkLinks->encoded;
        }

        //Create the database entry to increment the ID
        $links = Links::create([
            'original' => $url,
            'encoded' => '',
        ]);

        //Generate the base62 string using the unique database ID & save the encoded link
        $encoded = $this->generateBase62($links->id);
        $links->encoded = config('services.urlShortener.url') . $encoded;
        $links->save();

        Cache::set(config('cache.prefix') . $encoded, $links, config('services.urlShortener.ttl'));

        return $links->encoded;
    }

    /**
     * @throws Exception
     */
    public function decode(string $url): string
    {
        try {
            //Check the cache first as its faster
            $cacheKey = config('cache.prefix') . $url;
            $results = Cache::get($cacheKey);

            if ($results) {
                return $results->original;
            }

            //Fall back to checking the slower database store
            $results = Links::where('encoded', $url)->firstOrFail();

            //Set the cache again to speed up subsequent calls
            Cache::set($cacheKey, $results);

            return $results->original;
        } catch (Exception $e) {
            //Log the actual error message
            Log::error($e->getMessage());

            //Throw a user-friendly message
            throw new Exception('URL Not Found - Please Try Again');
        }
    }

    private function generateBase62(int $id): string
    {
        $hash = '';
        $hashDigits = [];
        //Get an array containing the characters we will be using
        $base62Chars = preg_split('//','0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
        //Pad the ID to ensure its long enough to generate a nice code
        $dividend = str_pad($id, 10, 100000000, STR_PAD_LEFT);

        //Work out the remainder and divided amount while the divided value is greater than 0
        while ($dividend > 0) {
            $remainder = floor($dividend % 62);
            $dividend = floor($dividend / 62);
            array_unshift($hashDigits, $remainder);
        }

        //Loop through the hashDigits array to create the code based on base62 chars
        foreach ($hashDigits as $v) {
            $hash .= $base62Chars[$v];
        }

        return $hash;
    }
}
