<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = Project::query()->with(['lead', 'product', 'user'])->latest();

        if ($user && method_exists($user, 'isManager') && $user->isManager()) {
            // Manager: show waiting, in_progress, waiting_for_approval, rejected
            $query->whereIn('status', [
                Project::STATUS_WAITING,
                Project::STATUS_IN_PROGRESS,
                Project::STATUS_WAITING_FOR_APPROVAL,
                Project::STATUS_REJECTED,
            ]);
            $sales = User::query()->where('role', 'sales')->orderBy('name')->get();
        } else {
            // Sales: only assigned to them and relevant statuses
            $query->where('user_id', $user?->id)
                ->whereIn('status', [
                    Project::STATUS_IN_PROGRESS,
                    Project::STATUS_WAITING_FOR_APPROVAL,
                    Project::STATUS_REJECTED,
                ]);
            $sales = collect();
        }

        $projects = $query->get();

        return view('projects.index', compact('projects', 'sales'));
    }

    public function assign(Request $request, Project $project): RedirectResponse
    {
        $user = Auth::user();
        if (!$user || !$user->isManager()) {
            abort(403);
        }

        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $sales = User::findOrFail($validated['user_id']);
        if ($sales->role !== 'sales') {
            return back()->withErrors(['user_id' => 'User terpilih bukan sales.']);
        }

        $project->user_id = $sales->id;
        $project->status = Project::STATUS_IN_PROGRESS;
        $project->save();

        return back()->with('success', 'Project berhasil di-assign ke sales.');
    }

    public function updateStatus(Request $request, Project $project): RedirectResponse
    {
        $user = Auth::user();
        $validated = $request->validate([
            'status' => ['required', 'string'],
        ]);

        $newStatus = $validated['status'];

        if ($user && $user->isManager()) {
            // Manager can approve/reject when waiting_for_approval
            if ($project->status !== Project::STATUS_WAITING_FOR_APPROVAL) {
                return back()->withErrors(['status' => 'Status saat ini tidak dapat diubah oleh manager.']);
            }
            if (!in_array($newStatus, [Project::STATUS_APPROVED, Project::STATUS_REJECTED], true)) {
                return back()->withErrors(['status' => 'Perubahan status tidak valid untuk manager.']);
            }
            $project->status = $newStatus;
            $project->save();
            return back()->with('success', 'Status project diperbarui.');
        }

        // Sales logic: can move from in_progress -> waiting_for_approval, only if assigned to them
        if (!$user || !$user->isSales()) {
            abort(403);
        }
        if ($project->user_id !== $user->id) {
            abort(403);
        }
        if ($project->status !== Project::STATUS_IN_PROGRESS || $newStatus !== Project::STATUS_WAITING_FOR_APPROVAL) {
            return back()->withErrors(['status' => 'Perubahan status tidak diizinkan.']);
        }

        $project->status = Project::STATUS_WAITING_FOR_APPROVAL;
        $project->save();

        return back()->with('success', 'Status project diperbarui menjadi menunggu persetujuan.');
    }
}
