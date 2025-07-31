<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Sosmed;

class ProfileSettingController extends Controller
{
   public function index()
    {
        $companyProfiles = Alamat::all();
        $contact = Contact::first();


        // Ambil semua akun sosial dan kelompokkan berdasarkan platform
        $socialAccounts = Sosmed::all()->keyBy('nama');

        return view('setting.setting', compact('companyProfiles', 'contact', 'socialAccounts'));
    }
}
