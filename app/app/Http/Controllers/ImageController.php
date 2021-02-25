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
        $file = $request->file('image');
        $path = $file->store('temp');
        return new Response($path);
    }

    public function update(Request $request)
    {
        $tempId = $request->input('tempImage');
        $tempPath = Storage::disk('local')->path($tempId);
        $pathParts = pathinfo($tempPath);
        $extension = $pathParts['extension'];
        $uniteFileName = uniqid('recipe-').'.'.$extension;

        $newPath = '/public/recipe-images/'.$uniteFileName;
        Storage::disk('local')->move($tempId, $newPath);
        return new Response($uniteFileName);
    }
}
