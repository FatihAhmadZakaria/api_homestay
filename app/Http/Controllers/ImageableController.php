<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Imageable;
use Illuminate\Support\Facades\Log;

class ImageableController extends Controller
{
    public function store(Request $request)
{
    Log::info('Memulai proses upload gambar.');

    $file = $request->file('image');
    if (!$file) {
        return response()->json(['error' => 'Image file is required.'], 400);
    }

    $originalName = $file->getClientOriginalName();
    Log::info("Nama asli file: $originalName");

    $filePath = 'images/' . $originalName;
    Storage::putFileAs('public/images', $file, $originalName);
    Log::info("File berhasil disimpan di path: $filePath");

    $imageable = Imageable::create([
        'img_path' => $filePath,
        'imageable_type' => $request->imageable_type,
        'imageable_id' => $request->imageable_id,
    ]);
    Log::info("Data berhasil disimpan ke database: ", $imageable->toArray());

    return response()->json(['message' => 'Image uploaded successfully.']);
}
}

