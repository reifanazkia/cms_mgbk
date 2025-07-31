<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider; // Pastikan model Slider sudah ada
use App\Models\Agenda;
use App\Models\Career;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $sliders = Slider::where('display_on_home', 1)->get();

        $agendas = Agenda::whereBetween('start_datetime', [
            Carbon::today(),
            Carbon::today()->addDays(3)
        ])
            ->orderBy('start_datetime', 'asc')
            ->take(5)
            ->get();
            
        $totalLoker = Career::count();

        return view('dashboard', compact('sliders', 'agendas', 'totalLoker'));
    }
}
