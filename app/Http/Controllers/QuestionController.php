<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('question.edit', [
            'question' => new Question(),
            'contest_id' => $request->contest,
            'mode' => 'create',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'contest_id' => 'required',
        ]);

        Question::create([
            'title' => $request->title,
            'content' => $request->content,
            'answer' => $request->answer,
            'contest_id' => $request->contest_id,
            'author_id' => auth()->id(),
        ]);

        return redirect(route('contests.show', ['contest' => $request->contest_id]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        return view('question.show', [
            'contest' => $question->contest,
            'question' => $question,
            'is_author' => $question->author_id == auth()->id(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        return view('question.edit', [
            'question' => $question,
            'contest_id' => $question->contest_id,
            'mode' => 'edit',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $question->update([
            'title' => $request->title,
            'content' => $request->content,
            'answer' => $request->answer,
        ]);

        return redirect(route('questions.show', ['question' => $question->id]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $contest_id = $question->contest_id;
        $question->delete();
        return redirect('contests.show', ['contest' => $contest_id ]);
    }
}
