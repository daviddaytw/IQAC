<x-layout>
    <x-navbar>
      <li class="nav-item">
        <a class="nav-link active" href="{{ route('contests.show', ['contest' => $contest->id]) }}">Problems</a>
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
      <main class="container p-4">
        <div class="d-flex">
          <h1 class="flex-grow-1">{{ $contest->name }}</h1>
          @if( $is_judge )
          <div>
            <span class="text-secondary p-3">Contest is {{ $contest->is_public ? 'Public' : 'Private' }}</span>
            <a class="btn btn-secondary" href="{{ route('contests.edit', ['contest' => $contest->id]) }}" role="button">Edit</a>
          </div>
          @endif
        </div>
        <p class="text-secondary">Contest Time: {{ $contest->begin_time }} ~ {{ $contest->end_time }}</p>

        <div class="p-4">
          <div class="py-2 text-center">
            Time Left:
            <time class="countdown-timer" datetime="{{ $contest->end_time }}">
              {{ $contest->end_time }}
            </time>
          </div>

          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">Question</th>
                <th scope="col">Score</th>
                <th scope="col">Submissions</th>
              </tr>
            </thead>
            <tbody>
                @foreach( $questions as $question )
                    <tr>
                        <td>
                            <a href="{{ route('questions.show', ['contest' => $contest->id, 'question' => $question->id ]) }}">
                                {{ $question->title }}
                            </a>
                        </td>
                        @auth
                          <x-score-cell :score="$question->submissions()->where('user_id', auth()->user()->id)->orderBy('score', 'desc')->first()->score ?? null" />
                        @endauth
                        @guest
                          <td>N/A</td>
                        @endguest
                        <td>{{ $question->submissions->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
          </table>

          @if( $is_judge )
            <a class="btn btn-primary" href="{{ route('questions.create', ['contest' => $contest->id]) }}" role="button">Add Question</a>
          @endif
        </div>
      </main>
  </x-layout>