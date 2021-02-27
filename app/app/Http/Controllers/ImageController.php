<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Returns file object of the given image id
     */
    public function show(string $imageId)
    {
        $image = Storage::disk('local')->get('/public/recipe-images/'.$imageId);
        $imagePath = Storage::disk('local')->path('/public/recipe-images/'.$imageId);
        $response = new Response($image, 200);
        $mimeType = mime_content_type($imagePath);
        $response->header('Content-Type', $mimeType);
        return $response;
    }

    public function store(Request $request)
    {
        $fileName = uniqid('recipe-').'.jpg';
        $request->file('image')->storeAs('public/recipe-images', $fileName);
        return new Response($fileName);
    }
}
