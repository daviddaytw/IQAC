<x-layout>
  <x-navbar>
    <li class="nav-item">
      <a class="nav-link active" aria-current="page" href="/">Contests</a>
    </li>
  </x-navbar>
  <main class="container p-4">
    <h1>Contests</h1>
    <div class="row">
      <div class="col-8">
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Contest</th>
              <th scope="col">Begin</th>
              <th scope="col">Finish</th>
            </tr>
          </thead>
          <tbody>
              @foreach ($contests as $contest)
                <tr>
                  <th scope="row">{{ $contest->id }}</th>
                  <td>
                    <a href="{{ route('contests.show', ['contest' => $contest->id])}}">
                      {{ $contest->name }}
                    </a>
                  </td>
                  <td>{{ $contest->begin_time }}</td>
                  <td>{{ $contest->end_time }}</td>
                </tr>
              @endforeach
          </tbody>
        </table>
      </div>
      <div class="col-4">
        @auth
        <h3>My Contests</h3>
        <a class="btn btn-secondary my-2" href="{{ route('contests.create') }}" role="button">Create Contest</a>

        @foreach($my_contests as $contest)
          <div class="card mb-3">
            <div class="card-body">
              <h4 class="card-title">{{ $contest->name }}</h4>
              <p class="card-text">{{ $contest->begin_time }} - {{ $contest->end_time }}</p>
              <a href="{{ route('contests.show', ['contest' => $contest->id])}}" class="btn btn-primary">Visit</a>
            </div>
          </div>
        @endforeach
        @endauth
      </div>
    </div>
  </main>
</x-layout>