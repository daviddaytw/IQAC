<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Submission;
use Illuminate\Http\Request;

class ScoreboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Contest $contest)
    {
        return view('scoreboard.index', [
            'contest' => $contest,
            'questions' => $contest->questions,
            'participants' => $contest->participants()->where('role', 'PLAYER')->orderBy('score', 'desc')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Contest $contest)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Contest $contest)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Contest $contest, Submission $submission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contest $contest, Submission $submission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contest $contest, Submission $submission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contest $contest, Submission $submission)
    {
        //
    }
}
