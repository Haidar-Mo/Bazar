<?php

namespace App\Traits;
use Illuminate\Http\UploadedFile;


/**
 * Files like : images , videos , ...etc
 */
trait HasFiles
{

    public function saveFile(UploadedFile $file, string $folder_name)
    {
        $file_name = time() . '_' . substr(
            str_shuffle(
                'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',
            ),
            0,
            50
        ) . '.' . $file->getClientOriginalExtension();
        $folderPath = public_path($folder_name);
        $file->move($folderPath, $file_name);
        return "$folder_name/$file_name";
    }

    /**
     * Delete file from the public storage
     * @param string $path the path after app/public
     * @return bool
     */
    public static function deleteFile(string $path)
    {
        $filePath = public_path( $path);
        if (file_exists($filePath) && is_file($filePath)) {
            unlink($filePath);
            return true;
        }
        return false;
    }
}
