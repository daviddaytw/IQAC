<x-layout>
    <x-navbar>
      <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="/">Contests</a>
      </li>
    </x-navbar>
    <main class="container p-4">
      <h1>Create/Edit Contest</h1>
      
      @if( $mode == 'edit' )
      <form action="{{ route('contests.update', ['contest' => $contest->id]) }}" method="POST">
        @method('PUT')
      @else
      <form action="{{ route('contests.store') }}" method="POST">
      @endif
        @csrf
        <div class="mb-3">
            <label for="titleInput" class="form-label">Title</label>
            <input type="text" class="form-control" id="titleInput" name="name" value="{{ $contest->name }}" required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="beginInput" class="form-label">Start Time</label>
            <input type="datetime-local" class="form-control" id="beginInput" name="begin_time" value="{{ $contest->begin_time }}" required>
            @error('begin_time')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="endInput" class="form-label">End Time</label>
            <input type="datetime-local" class="form-control" id="endInput" name="end_time" value="{{ $contest->end_time }}" required>
            @error('end_time')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        @if( $mode == 'edit' )
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="publicCheck" name="is_public" @checked($contest->is_public)>
            <label class="form-check-label" for="publicCheck">Make the contest public.</label>
        </div>
        @endif
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </main>
  </x-layout>