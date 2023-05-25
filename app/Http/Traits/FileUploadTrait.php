<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait FileUploadTrait
{
    public function uploadFiles($request, $directory, $disk = 'public')
    {
        $pathLast = '';

        if ($request->hasFile('files')) {
            $filename = time() . $request->name . '.' . $request->file('file')->getClientOriginalExtension();
            $path = $request->file('files')->storeAs($directory, $filename, $disk);
            $pathLast = Storage::disk($disk)->url($path);

        }

        return $pathLast;
    }
}
