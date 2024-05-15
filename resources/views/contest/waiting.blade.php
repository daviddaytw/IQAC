<x-layout>
    <x-navbar>
      <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="/">Contests</a>
      </li>
    </x-navbar>
    <main class="container p-4 text-center">
      <h1>{{ $contest->name }}</h1>
      <h2>is about to start at</h2>
      <time class="countdown-timer fs-1" datetime="{{ $contest->begin_time }}">
        {{ $contest->begin_time }}
      </time>
    </main>
  </x-layout>