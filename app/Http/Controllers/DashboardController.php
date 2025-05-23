<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $consultasPorDia = Consulta::totalPorDia();

        return Inertia::render('Dashboard', [
            'consultasPorDia' => [
                'labels' => $consultasPorDia->pluck('dia'),
                'valores' => $consultasPorDia->pluck('total'),
            ],
        ]);
    }
}
