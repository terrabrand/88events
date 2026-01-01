<?php

namespace App\Http\Controllers;

use App\Models\AdPackage;
use App\Models\Package;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function pricing()
    {
        $packages = Package::where('is_active', true)->get();
        $adPackages = AdPackage::active()->get();

        return view('pages.pricing', compact('packages', 'adPackages'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function careers()
    {
        return view('pages.careers');
    }

    public function press()
    {
        return view('pages.press');
    }

    public function security()
    {
        return view('pages.security');
    }

    public function developers()
    {
        return view('pages.developers');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function cookies()
    {
        return view('pages.cookies');
    }
}
