<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $query = Project::query()
            ->with(['lead', 'product', 'user'])
            ->where('status', Project::STATUS_APPROVED)
            ->latest();

        if ($user && method_exists($user, 'isSales') && $user->isSales()) {
            $query->where('user_id', $user->id);
        }

        $projects = $query->get();

        return view('customers.index', compact('projects'));
    }
}
