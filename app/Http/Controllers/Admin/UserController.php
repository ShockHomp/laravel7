<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Dotenv\Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return response([
            'id' => $request->user()->id,
            'name' => $request->user()->name,
            'role' => ['superadmin'],
        ]);
    }
}
