<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiSliderController extends Controller
{

    public function index()
    {
        $slider = Slider::latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'List of Slider',
            'data' => $slider
        ]);
    }

    public function showHomeSlider(): JsonResponse
    {
        $sliders = Slider::where('display_on_home', true)->latest()->get();
        return response()->json([
            'status' => true,
            'data' => $sliders,
        ]);
    }
}
