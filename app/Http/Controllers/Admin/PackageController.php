<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::latest()->get();
        return view('admin.packages.index', compact('packages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sms_limit' => 'required|integer|min:0',
            'email_limit' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        Package::create($validated);

        return back()->with('success', 'Package created successfully.');
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sms_limit' => 'required|integer|min:0',
            'email_limit' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $package->update($validated);

        return back()->with('success', 'Package updated successfully.');
    }

    public function destroy(Package $package)
    {
        if ($package->subscriptions()->count() > 0) {
            return back()->with('error', 'Cannot delete package with active subscriptions.');
        }

        $package->delete();
        return back()->with('success', 'Package deleted successfully.');
    }
}
