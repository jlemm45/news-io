<?php

/**
 * Get the path to a versioned Elixir file.
 *
 * @param  string $file
 *
 * @return string
 */
function elixirCDN($file)
{
    $cdn = '';

    if(env('CDN_URL', false))
    {
        $cdn = env('CDN_URL');
    }

    return $cdn . elixir($file);
}