<?php

namespace App\Http\Traits;

trait FileManager
{
    /**
     * Validates the file from the request & persists it into storage
     * @param String $requestAttributeName from request
     * @param String $folder
     * @param String $disk
     * @return String $path
     */
    public function upload($requestAttributeName = null, $folder = 'uploads', $disk = 'public')
    {
        $path = null;

        // Check if the file exists in the request and is valid
        if (request()->hasFile($requestAttributeName) && request()->file($requestAttributeName)->isValid()) {
            $file = request()->file($requestAttributeName);

            // Define the folder path in the public directory
            $destinationPath = public_path($folder);

            // Ensure the folder exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Generate a unique file name
            $filename = time() . '_' . $file->getClientOriginalName();

            // Move the file to the public directory
            $file->move($destinationPath, $filename);

            // Set the path to return the publicly accessible URL
            $path = "$folder/$filename";
        }
        return $path;
    }

    public function uploadFile($file, $folder = '', $disk = 'public')
    {
        $path = null;
        if ($file && $file->isValid()) {
            $path = $file->store($folder, $disk);
        }
        return $path;
    }

    /**
     * Validates the file from the request & persists it into storage then unlink old one
     * @param String $requestAttributeName from request
     * @param String $folder
     * @param String $oldPath
     * @return String $path
     */
    public function updateFile($requestAttributeName = null, $folder = '', $oldPath)
    {
        $path = null;
        if (request()->hasFile($requestAttributeName) && request()->file($requestAttributeName)->isValid()) {
            $path = $this->upload($requestAttributeName, $folder);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
        return $path;
    }

    /**
     * Delete the file from the path
     * @param String $oldPath
     */

    public function deleteFile($oldPath)
    {
        if (file_exists($oldPath)) {
            unlink($oldPath);
        }
    }
}
