<x-layout>
  <x-navbar>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('contests.show', ['contest' => $contest->id]) }}">Problems</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('contests.scoreboard.index', ['contest' => $contest->id ] )}}">Scoreboard</a>
    </li>
    @auth
    @if($contest->participants()->where('user_id', auth()->id())->exists())
    <li class="nav-item">
      <a class="nav-link" href="{{ route('contests.submissions.index', ['contest' => $contest->id ] )}}">Submissions</a>
    </li>
    @endif
    @endauth
  </x-navbar>
  <main class="container p-5">
    <div class="p-4 bg-secondary-subtle rounded">
        <h1>{{ $question->title }}</h1>
        <p>{{ $question->content }}</p>
    </div>


    @if( $is_author )
      <div class="p-2">
        <a class="btn btn-primary" href="{{ route('questions.edit', ['question' => $question->id]) }}" role="button">
          Edit
        </a>
      </div>
    @else
      @if( \Carbon\Carbon::parse($question->contest->end_time)->gte(\Carbon\Carbon::now()) )
      <form class="p-2" action="{{ route("contests.submissions.store", ['contest' => $contest->id ]) }}" method="POST">
        @csrf
        <input type="hidden" name="question_id" value="{{ $question->id }}" />
        <div class="mb-3">
          <label for="answerArea" class="form-label">Your Answer</label>
          <textarea class="form-control" id="answerArea" name="answer" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
      @endif
    @endif
  </main>
</x-layout>