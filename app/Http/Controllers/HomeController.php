<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Product;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        try {
            $products = Product::orderBy('id')->get();
        } catch (\Throwable $e) {
            $products = collect();
        }
        return view('home', compact('products'));
    }

    public function buy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:500'],
        ]);

        // Normalize phone to digits-only to ensure uniqueness across formats
        $normalizedPhone = preg_replace('/\D+/', '', $data['phone'] ?? '');

        // Reuse existing lead by phone or create a new one
        $lead = Lead::firstOrCreate(
            ['phone' => $normalizedPhone],
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'address' => $data['address'],
            ]
        );

        // Create project with status waiting and no assigned user yet
        Project::create([
            'lead_id' => $lead->id,
            'product_id' => $data['product_id'],
            'user_id' => null,
            'status' => Project::STATUS_WAITING,
        ]);

        return redirect()->route('home')->with('status', 'Terima kasih! Pengajuan Anda telah diterima.');
    }
}
