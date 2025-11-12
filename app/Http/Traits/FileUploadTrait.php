<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Use Laravel's File facade
use Illuminate\Support\Facades\Log; // Use Laravel's Log facade

trait FileUploadTrait
{
    /**
     * Handles the file upload and moves it to the public directory.
     *
     * @param Request $request The incoming HTTP request.
     * @param string $uploadFolder The destination sub-folder in 'public/'.
     * @param string $fileNamePrefix A prefix for the new file name.
     * @param string|null $existingPhotoPath The path of an old photo to delete.
     * @return array An array with 'path' on success or 'error' on failure.
     */
    protected function handlePhotoUpload(Request $request, $uploadFolder, $fileNamePrefix, $existingPhotoPath = null) 
    {
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $file = $request->file('photo');
            $target_dir = public_path($uploadFolder); // Use Laravel's helper
            
            $file_extension = $file->getClientOriginalExtension();
            $file_mime_type = $file->getMimeType(); 

            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array(strtolower($file_extension), $allowed_extensions)) {
                return ['error' => 'Invalid file extension. Only JPG, JPEG, PNG, GIF allowed.'];
            }

            $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($file_mime_type, $allowed_mime_types)) {
                return ['error' => 'Invalid file content. Only real images are allowed.'];
            }
            
            if ($file->getSize() > 2 * 1024 * 1024) { // 2MB
                 return ['error' => 'File is too large (Max 2MB).'];
            }
            
            $safe_name = strtolower(trim($fileNamePrefix));
            $safe_name = preg_replace('/[^a-z0-9\s-]/', '', $safe_name);
            $safe_name = preg_replace('/[\s-]+/', '_', $safe_name);
            if (empty($safe_name)) $safe_name = 'photo';
            
            $file_name = $safe_name . '_' . time() . '.' . $file_extension;
            
            $public_path = "/" . $uploadFolder . "/" . $file_name;
            $target_file_path = $target_dir . '/' . $file_name;
            
            if (!File::exists($target_dir)) {
                try {
                    File::makeDirectory($target_dir, 0775, true);
                    // No need for chmod, makeDirectory handles it
                } catch (\Exception $e) {
                    Log::error('Failed to create upload directory', ['error' => $e->getMessage()]);
                    return ['error' => 'Failed to create upload directory: ' . $e->getMessage()];
                }
            }

            // Delete old photo if it exists
            if ($existingPhotoPath) {
                $server_existing_path = public_path($existingPhotoPath); 
                if (File::exists($server_existing_path)) {
                    File::delete($server_existing_path);
                }
            }
            
            // Move the new file
            try {
                $file->move($target_dir, $file_name);
                return ['path' => $public_path]; 
            } catch (\Exception $e) {
                 Log::error('Failed to move uploaded file', ['error' => $e->getMessage()]);
                 return ['error' => 'Failed to move file: ' . $e->getMessage()];
            }
        }
        return ['path' => null]; // No file uploaded or invalid
    }
}