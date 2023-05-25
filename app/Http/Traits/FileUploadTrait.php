<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait FileUploadTrait
{
    public function uploadFiles($request, $directory, $disk = 'public')
    {
        $paths = [];

        if ($request->hasFile('files')) {
            $filename = time() . $request->name . '.' . $request->file('file')->getClientOriginalExtension();
            $path = $request->file('files')->storeAs($directory, $filename, $disk);
            $paths[] = Storage::disk($disk)->url($path);

        }

        return json_encode($paths);
    }
}
