<x-layout>
    <x-navbar>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('contests.show', ['contest' => $contest->id]) }}">Problems</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="{{ route('contests.scoreboard.index', ['contest' => $contest->id ] )}}">Scoreboard</a>
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
        <h1>{{ $contest->name }} Scoreboard</h1>

        <div class="py-2 text-center">
          Time Left:
          <time class="countdown-timer" datetime="{{ $contest->end_time }}">
            {{ $contest->end_time }}
          </time>
        </div>

        <div class="overflow-x-scroll">
          <table class="table table-striped table-bordered table-hover">
              <thead>
                  <tr>
                      <th scope="col">Participant</th>
                      <th scope="col" class="text-center">Total</th>
                      @foreach( $questions as $question)
                        <th scope="col" class="text-center">{{ $question->title }}</th>
                      @endforeach
                  </tr>
              </thead>
              <tbody>
                @foreach( $participants as $participant )
                  <tr>
                    <td>{{ $participant->user->name }}</td>
                    <td class="text-center fw-bolder">{{ $participant->score }}</td>
                    @foreach( $questions as $question )
                      <x-score-cell :score="$question->submissions()->where('user_id', $participant->user->id)->orderBy('score', 'desc')->first()->score ?? null" />
                    @endforeach
                  </tr>
                @endforeach
              </tbody>
          </table>
        </div>
    </main>
</x-layout>
