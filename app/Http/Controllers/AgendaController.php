<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\AgendaSpeaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;

class AgendaController extends Controller
{

    public function index()
    {
        $agendas = Agenda::with('speakers')->latest()->get();
        $speakers = AgendaSpeaker::all();
        return view('agenda.index', compact('agendas', 'speakers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'start_datetime' => 'required|date',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'in:Soldout,Open',
            'speaker_ids' => 'nullable|array',
            'speaker_ids.*' => 'nullable|integer|exists:agenda_speakers,id',
        ]);

        $data = $request->only([
            'title', 'description', 'start_datetime', 'end_datetime',
            'event_organizer', 'location', 'register_link', 'youtube_link',
            'type', 'status'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('agenda', 'public');
        }

        $agenda = Agenda::create($data);

        if ($request->has('speaker_ids')) {
            $agenda->speakers()->sync($request->speaker_ids);
        }

        return redirect()->route('agenda.index')->with('success', 'Agenda created successfully.');
    }

    public function show($id)
    {
        $agenda = Agenda::with('speakers')->findOrFail($id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($agenda);
        }

        return view('agenda.show', compact('agenda'));
    }

    public function update(Request $request, $id)
    {
        $agenda = Agenda::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'start_datetime' => 'required|date',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'in:Soldout,Open',
            'speaker_ids' => 'nullable|array',
            'speaker_ids.*' => 'nullable|integer|exists:agenda_speakers,id',
        ]);

        $data = $request->only([
            'title', 'description', 'start_datetime', 'end_datetime',
            'event_organizer', 'location', 'register_link', 'youtube_link',
            'type', 'status'
        ]);

        if ($request->hasFile('image')) {
            if ($agenda->image && Storage::disk('public')->exists($agenda->image)) {
                Storage::disk('public')->delete($agenda->image);
            }
            $data['image'] = $request->file('image')->store('agenda', 'public');
        }

        $agenda->update($data);
        $agenda->speakers()->sync($request->speaker_ids ?? []);

        return redirect()->route('agenda.index')->with('success', 'Agenda updated successfully.');
    }

    public function destroy($id)
    {
        $agenda = Agenda::findOrFail($id);

        if ($agenda->image) {
            Storage::disk('public')->delete($agenda->image);
        }

        $agenda->speakers()->detach();
        $agenda->delete();

        return response()->json(['message' => 'Agenda deleted successfully.']);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $ids = $request->input('ids');

        try {
            $agendas = Agenda::whereIn('id', $ids)->get();

            foreach ($agendas as $agenda) {
                if ($agenda->image && Storage::disk('public')->exists($agenda->image)) {
                    Storage::disk('public')->delete($agenda->image);
                }
                $agenda->speakers()->detach();
                $agenda->delete();
            }

            return back()->with('success', 'Agenda terpilih berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus Agenda: ' . $e->getMessage());
        }
    }
}
