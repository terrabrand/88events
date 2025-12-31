<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdPackage;
use Illuminate\Http\Request;

class AdPackageController extends Controller
{
    public function index()
    {
        $packages = AdPackage::all();
        return view('admin.ad-packages.index', compact('packages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        AdPackage::create($validated);
        return redirect()->back()->with('success', 'Ad Package created successfully.');
    }

    public function update(Request $request, AdPackage $adPackage)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $adPackage->update($validated);
        return redirect()->back()->with('success', 'Ad Package updated successfully.');
    }

    public function destroy(AdPackage $adPackage)
    {
        $adPackage->delete();
        return redirect()->back()->with('success', 'Ad Package deleted successfully.');
    }
}
