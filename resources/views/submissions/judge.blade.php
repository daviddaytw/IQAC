<x-layout>
    <x-navbar>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('contests.show', ['contest' => $contest->id]) }}">Problems</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('contests.scoreboard.index', ['contest' => $contest->id ] )}}">Scoreboard</a>
      </li>
      @auth
      <li class="nav-item">
        <a class="nav-link" href="{{ route('contests.submissions.index', ['contest' => $contest->id ] )}}">Submissions</a>
      </li>
      @endauth
    </x-navbar>
    <main class="container p-4">
        <h1>Submission #{{ $submission->id }}</h1>

        <div class="p-4 bg-secondary-subtle rounded">
            <h2>Question</h2>
            <div class="p-3 my-2 bg-light rounded">
                <h3>{{ $submission->question->title }}</h3>
                <p>{{ $submission->question->content }}</p>
            </div>
            <h2>Paritcipant's Answer</h2>
            <div class="p-3 my-2 bg-light rounded">
                <p>{{ $submission->answer }}</p>
            </div>
        </div>
        
        <div class="p-4">
            <form action="{{ route('contests.submissions.update', ['contest' => $contest->id, 'submission' => $submission->id ])}}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="scoreInput" class="form-label">Score</label>
                    <input type="number" class="form-control" id="scoreInput" min="0" max="100" name="score">
                </div>
                <div class="mb-3">
                    <label for="commentArea" class="form-label">Comment</label>
                    <textarea class="form-control" id="commentArea" name="comment"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </main>
  </x-layout>
  