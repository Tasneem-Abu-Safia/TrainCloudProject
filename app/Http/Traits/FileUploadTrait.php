<?php

namespace App\Http\Traits;

use Google\Cloud\Storage\StorageClient;
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


    public function uploadFilesFireBase($request)
    {
        $firebaseCredentialsPath = storage_path('app/' . env('FIREBASE_CREDENTIALS_PATH'));
        $storage = new StorageClient([
            'projectId' => 'trainmanagement-b559e',
            'keyFilePath' => $firebaseCredentialsPath,
        ]);

        $bucket = $storage->bucket('trainmanagement-b559e.appspot.com');

        // Store CV Files
        if ($request->hasFile('file')) {
            $cvFile = $request->file('file');
            $cvPath = 'CVs/' . $request->name . '.' . time() + rand(1, 10000000) . '.' . $cvFile->getClientOriginalName();
            $bucket->upload(
                file_get_contents($cvFile),
                [
                    'name' => $cvPath,
                ]
            );
            return $cvPath;

        }
        return '';
    }

    function getUploadedFireBase($filePath)
    {
        if (!empty($filePath)) {
            $firebaseCredentialsPath = storage_path('app/' . env('FIREBASE_CREDENTIALS_PATH'));
            $storage = new StorageClient([
                'projectId' => 'trainmanagement-b559e',
                'keyFilePath' => $firebaseCredentialsPath,
            ]);

            $bucket = $storage->bucket('trainmanagement-b559e.appspot.com');


            // Generate the signed URL for the file
            $object = $bucket->object($filePath);
            $url = $object->signedUrl(now()->addHour());

            return $url;
        }

        return null;
    }
}
