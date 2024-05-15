<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Participant;
use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Contest $contest)
    {
        $role = $contest->participants()->where('user_id', auth()->id())->first()->role;

        $questions = $contest->questions->map(function ($item, $key) {
            return $item->id;
        });

        if( $role == 'JUDGE' ) {
            $submissions = Submission::whereIn('question_id', $questions)->orderBy('id', 'desc')->get();
        } else {
            $submissions = Submission::whereIn('question_id', $questions)->where('user_id', auth()->id())->orderBy('id', 'desc')->get();
        }
        return view('submissions.index', [
            'contest' => $contest,
            'submissions' => $submissions,
            'role' => $role,
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
        $request->validate([
            'question_id' => 'required',
            'answer' => 'required'
        ]);

        Submission::create([
            'user_id' => auth()->id(),
            'question_id' => $request->question_id,
            'answer' => $request->answer,
        ]);

        return redirect(route('contests.submissions.index', [$contest]));
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
        return view('submissions.judge', [
            'contest' => $contest,
            'submission' => $submission,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contest $contest, Submission $submission)
    {
        $request->validate([
            'score' => 'required|min:0|max:100',
        ]);

        $submission->update([
            'score' => $request->score,
            'comment' => $request->comment,
        ]);

        // Update participant total score.
        $total_score = 0;
        foreach( $contest->questions as $question ) {
            $sub = Submission::where('question_id', $question->id)->where('user_id', $submission->user_id)->orderBy('score', 'desc')->first();
            $total_score += $sub->score ?? 0;
        }
        Participant::updateOrCreate(
            [ 'user_id' => $submission->user_id, 'contest_id' => $contest->id ],
            [ 'score' => $total_score ],
        );

        return redirect(route('contests.submissions.index', [$contest]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contest $contest, Submission $submission)
    {
        //
    }
}
