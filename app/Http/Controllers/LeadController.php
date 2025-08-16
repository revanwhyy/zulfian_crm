<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(): View
    {
        $leads = Lead::orderByDesc('id')->get();
        return view('leads.index', compact('leads'));
    }
}
