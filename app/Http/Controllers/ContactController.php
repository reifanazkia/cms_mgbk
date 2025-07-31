<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contact = Contact::first();
        return view('setting.setting', compact('contact'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'notlp' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        // Cek apakah sudah ada data kontak
        $existingContact = Contact::first();

        if ($existingContact) {
            // Update existing contact instead of creating new one
            $existingContact->update([
                'notlp' => $request->notlp ?: $existingContact->notlp,
                'email' => $request->email ?: $existingContact->email,
            ]);
            return back()->with('success', 'Data kontak berhasil diperbarui.');
        }

        // Buat data kontak baru
        Contact::create([
            'notlp' => $request->notlp,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Data kontak berhasil dibuat.');
    }

    public function update(Request $request)
    {
        $contact = Contact::first();

        if (!$contact) {
            // Create new contact if doesn't exist
            $contact = Contact::create([
                'notlp' => null,
                'email' => null,
            ]);
        }

        $type = $request->input('type');

        switch ($type) {
            case 'notlp':
                $notlp = $request->input('notlp');
                if ($notlp === '' || $notlp === null) {
                    // Delete/clear phone number
                    $contact->update(['notlp' => null]);
                    return back()->with('success', 'Nomor telepon berhasil dihapus.');
                } else {
                    // Update phone number
                    $request->validate(['notlp' => 'required|string|max:255']);
                    $contact->update(['notlp' => $notlp]);
                    return back()->with('success', 'Nomor telepon berhasil diperbarui.');
                }
                break;

            case 'email':
                $email = $request->input('email');
                if ($email === '' || $email === null) {
                    // Delete/clear email
                    $contact->update(['email' => null]);
                    return back()->with('success', 'Email berhasil dihapus.');
                } else {
                    // Update email
                    $request->validate(['email' => 'required|email|max:255']);
                    $contact->update(['email' => $email]);
                    return back()->with('success', 'Email berhasil diperbarui.');
                }
                break;

            default:
                return back()->with('error', 'Tipe data tidak valid.');
        }
    }

    public function destroy($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        $contact->delete();

        return back()->with('success', 'Kontak berhasil dihapus.');
    }
}
