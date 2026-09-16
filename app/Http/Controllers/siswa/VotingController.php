<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kandidat;

class VotingController extends Controller
{
    public function index() 
    {
        $kandidat = Kandidat::all();
        // $path = $request->file('foto')->store('kandidat', 'public');

        return view('siswa.voting.index', compact('kandidat'));
    }
}
