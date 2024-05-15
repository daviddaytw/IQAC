<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->check()) {
            $judgeRoles = Participant::where('user_id', auth()->id())->where('role', 'JUDGE')->get();
            $myContests = Contest::whereIn('id', $judgeRoles->map(function($item) {
                return $item->contest_id;
            }))->get();
        }
        return view('contest.index', [
            'contests' => Contest::where('is_public', true)->orderBy('begin_time', 'desc')->get(),
            'my_contests' => $myContests ?? null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contest.edit', [
            'contest' => new Contest(),
            'mode' => 'create',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'begin_time' => 'required',
            'end_time' => 'required',
        ]);
        
        $contest = Contest::create([
            'name' => $request->name,
            'begin_time' => $request->begin_time,
            'end_time' => $request->end_time,
        ]);

        Participant::create([
            'contest_id' => $contest->id,
            'user_id' => auth()->id(),
            'role' => 'JUDGE'
        ]);

        return redirect(route('contests.show', [ 'contest' => $contest->id ]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Contest $contest)
    {
        $is_judge = $contest->participants()->where('user_id', auth()->id())->first()?->role == 'JUDGE' ?? false;
        if( !$is_judge && Carbon::parse($contest->begin_time)->gt(Carbon::now())) {
            return view('contest.waiting', [
                'contest' => $contest,
            ]);
        } else {
            return view('contest.show', [
                'contest' => $contest,
                'questions' => $contest->questions,
                'is_judge' => $is_judge,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contest $contest)
    {
        return view('contest.edit', [
            'contest' => $contest,
            'mode' => 'edit',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contest $contest)
    {
        $request->validate([
            'name' => 'required',
            'begin_time' => 'required',
            'end_time' => 'required',
        ]);

        $contest->update([
            'name' => $request->name,
            'begin_time' => $request->begin_time,
            'end_time' => $request->end_time,
            'is_public' => $request->is_public == 'on',
        ]);

        return redirect(route('contests.show', [
            'contest' => $contest->id,
        ]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contest $contest)
    {
        $contest->delete();

        return redirect(route('contest.index'));
    }
}
