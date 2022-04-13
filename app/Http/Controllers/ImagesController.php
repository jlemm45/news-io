<?php

namespace App\Http\Controllers;

use League\Glide\ServerFactory;
use League\Glide\Responses\LaravelResponseFactory;
use Illuminate\Contracts\Filesystem\Filesystem;

class ImagesController extends Controller
{
  public function show(Filesystem $filesystem, $path)
  {
    $server = ServerFactory::create([
      'response' => new LaravelResponseFactory(app('request')),
      'source' => $filesystem->getDriver(),
      'cache' => $filesystem->getDriver(),
      'cache_path_prefix' => '.cache',
      'base_url' => 'img',
    ]);

    return $server->getImageResponse($path, request()->all());
  }
}
