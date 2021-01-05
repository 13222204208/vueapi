<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UploadImageController;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $upload = new UploadImageController;
        return $upload->getNewFile($request->file('img'));
    }
}
