<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function scan() {
        if (!session('data_kuisioner')) return redirect()->route('kuisioner');
        return view('pages.scan.scan');
    }

    // Terima hasil FER dari Face-API.js (client-side) lalu simpan ke session.
    public function submitFER(Request $request) {
        $validated = $request->validate([
            'detected'                  => 'required|boolean',
            'emotions'                  => 'nullable|array',
            'emotions.neutral'          => 'nullable|numeric|min:0|max:1',
            'emotions.happy'            => 'nullable|numeric|min:0|max:1',
            'emotions.sad'              => 'nullable|numeric|min:0|max:1',
            'emotions.angry'            => 'nullable|numeric|min:0|max:1',
            'emotions.fearful'          => 'nullable|numeric|min:0|max:1',
            'emotions.disgusted'        => 'nullable|numeric|min:0|max:1',
            'emotions.surprised'        => 'nullable|numeric|min:0|max:1',
            'dominant_emotion'          => 'nullable|string|max:50',
            'dominant_emotion_score'    => 'nullable|numeric|min:0|max:1',
            'emotion_variance'          => 'nullable|numeric|min:0',
            'negative_emotion_duration' => 'nullable|numeric|min:0',
            'total_frames_analyzed'     => 'nullable|integer|min:0',
        ]);

        session(['fer_result' => $validated]);

        return response()->json([
            'success' => true,
            'message' => 'FER data received',
            'redirect' => route('loading'),
        ]);
    }
}
