<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FileUploadHelper
{
    /**
     * Upload a file to a specific directory.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory
     * @return string|null
     */
    public static function singleUpload($file, $directory)
    {
        // Ensure the file is an instance of UploadedFile
        if ($file && $file->isValid()) {
            // Get the file extension and a unique name for the file
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

            // Save file to the designated directory (currently using default disk 'local')
            $path = $file->storeAs($directory, $fileName, 'public'); // 'public' will store the file in storage/app/public

            // If you're using a custom disk (like S3), you can change 'public' to the disk you configure in config/filesystems.php
            return $path;
        }

        return null; // Return null if the file is not valid
    }

    /**
     * Upload multiple files to a specific directory.
     *
     * @param \Illuminate\Http\UploadedFile|array $files
     * @param string $directory
     * @return array|null
     */
    public static function multiUpload($files, $directory)
    {
        $filePaths = [];

        // Check if $files is an array or a single file
        $files = is_array($files) ? $files : [$files];

        foreach ($files as $file) {
            // Ensure the file is an instance of UploadedFile and is valid
            if ($file && $file->isValid()) {
                // Generate a unique name for each file
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

                // Store the file and get the path
                $path = $file->storeAs($directory, $fileName, 'public'); // Store using the 'public' disk

                // Append the file path to the filePaths array
                $filePaths[] = $path;
            }
        }

        // Return the array of file paths, or null if no valid files were uploaded
        return count($filePaths) > 0 ? $filePaths : null;
    }



    /**
     * Delete a file from the storage.
     *
     * @param string $filePath
     * @return bool
     */
    public static function delete($filePath)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->delete($filePath);
        }

        return false;
    }
}
