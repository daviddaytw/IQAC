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
      <a class="nav-link active" href="{{ route('contests.submissions.index', ['contest' => $contest->id ] )}}">Submissions</a>
    </li>
    @endauth
  </x-navbar>
  <main class="container p-4">
      @if( $role == 'JUDGE')
        <h1>Participants Submissions</h1>
      @else
        <h1>My Submissions</h1>
      @endif

      <table class="table table-stripped">
          <thead>
              <tr>
                  <th scope="col">#</th>
                  <th scope="col">Question</th>
                  <th scope="col">Score</th>
                  <th scope="col">Answer</th>
                  <th scope="col">Comment</th>
                  <th scope="col">Submitted Time</th>
                  @if( $role == 'JUDGE')
                    <th scope="col">Actions</th>
                  @endif
              </tr>
          </thead>
          <tbody>
            @foreach ($submissions as $submission)
              <tr>
                  <th scope="row">{{ $submission->id }}</th>
                  <td> {{ $submission->question->title }}</td>
                  <x-score-cell :score="$submission->score" />
                  <td>{{ $submission->answer }}</td>
                  <td>{{ $submission->comment }}</td>
                  <td>{{ $submission->created_at }}</td>
                  @if( $role == 'JUDGE')
                    <td><a href="{{ route('contests.submissions.edit', ['contest' => $contest->id, 'submission' => $submission->id]) }}">Judge</a></td>
                  @endif
              </tr>
            @endforeach
          </tbody>
        </table>
  </main>
</x-layout>
