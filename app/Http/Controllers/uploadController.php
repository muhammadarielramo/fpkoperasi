<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class uploadController extends Controller
{
    public function index(){
        return view('upload');
    }

    public function upload (Request $request) {

        $validated = Validator::make($request->all(), [
            'file'     => ['required', 'image:jpeg,png,jpg,gif,svg|max:2048']
        ]);

        if($validated->fails()) {
            return response()->json([
                'massage' => 'Data tidak valid',
                'data' => $validated->errors()
            ], 500);
        }

        $uploadFolder = 'ktp_uploads';
        $image = $request->file('file');
        $extension = $image->getClientOriginalExtension();
        $fileName = 'ktp-' . now()->format('Y-m-d') . '.' . $extension;
        $image_uploaded_path = $image->storeAs($uploadFolder, $fileName, 'public');

        // simpan foto
        try {
            $uploadedImageResponse = array(
            "image_name" => basename($image_uploaded_path),
            "image_url" => asset('storage/' . $image_uploaded_path),
            "mime" => $image->getClientMimeType());

            return response()->json([
                'message' => 'Foto berhasil diupload',
                'data' => $uploadedImageResponse
            ], 200);
        } catch ( \Exception $e) {
            return response()->json([
                'message' => 'Foto gagal diupload',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
