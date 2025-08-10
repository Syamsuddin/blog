<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function test(Request $request)
    {
        Log::info('Test route hit', [
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'data' => $request->all(),
            'session' => $request->session()->all()
        ]);
        
        return response()->json(['success' => true, 'data' => $request->all()]);
    }
}
