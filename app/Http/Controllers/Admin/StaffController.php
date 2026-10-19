<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Display a listing of staff (Kapster & MUA).
     */
    public function index(Request $request)
    {
        $query = Staff::query()->orderBy('role')->orderBy('name');

        if ($request->filled('role') && in_array($request->role, ['kapster', 'mua'])) {
            $query->where('role', $request->role);
        }

        $staffs = $query->paginate(15)->withQueryString();

        return view('admin.staffs.index', compact('staffs'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        return view('admin.staffs.create');
    }

    /**
     * Store a newly created staff in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'role'      => 'required|in:kapster,mua',
            'phone'     => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Staff::create($validated);

        return redirect()->route('admin.staffs.index')
            ->with('success', 'Staf berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the staff member.
     */
    public function edit(Staff $staff)
    {
        return view('admin.staffs.edit', compact('staff'));
    }

    /**
     * Update the staff member in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'role'      => 'required|in:kapster,mua',
            'phone'     => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $staff->update($validated);

        return redirect()->route('admin.staffs.index')
            ->with('success', 'Data staf berhasil diperbarui.');
    }

    /**
     * Remove the staff member from storage.
     */
    public function destroy(Staff $staff)
    {
        $staff->delete();

        return redirect()->route('admin.staffs.index')
            ->with('success', 'Staf berhasil dihapus.');
    }
}

