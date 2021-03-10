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

    public function update(Request $request, string $imageId)
    {
        $request->file('image')->storeAs('public/recipe-images', $imageId);
        return new Response($imageId);
    }

    public function destroy(Request $request, string $imageId): Response
    {
        $storage = Storage::disk('local');
        ;
        if ($storage->exists('/public/recipe-images/'.$imageId)) {
            Storage::disk('local')->delete('/public/recipe-images/'.$imageId);
            return new Response(sprintf('image "%1$s" successfully deleted', $imageId));
        } else {
            return new Response(sprintf('image "%1$s" does not exist', $imageId), 404);
        }
    }
}
