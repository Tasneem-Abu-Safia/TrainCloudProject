<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait FileUploadTrait
{
    public function uploadFiles($request, $directory, $disk = 'public')
    {
        $paths = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filename = time() . $request->name . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($directory, $filename, $disk);
                $paths[] = Storage::disk($disk)->url($path);
            }
        }

        return json_encode($paths);
    }
}
