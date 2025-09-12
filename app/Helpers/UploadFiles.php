<?php

namespace App\Helpers;

class UploadFiles
{
    public static function upload($file, string $folder)
    {
            if ($file) {

            $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $folderPath = public_path($folder);
            if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
            }
            $file->move($folderPath, $fileName);
            $baseUrl = url('/');
            $fileUrl = $baseUrl.'/'.$folder.'/'.$fileName;

            return $fileUrl;
            }

            return null;
    }

public static function delete($file,$folder)
{
    if ($file) {

        $filePath = public_path($folder .'/' .basename($file));
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
}
