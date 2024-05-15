<x-layout>
    <x-navbar />
    <div class="container p-4">
        <h1>Create/Edit Question</h1>
        
        @if( $mode == 'edit' )
        <form action="{{ route('questions.update', ['question' => $question->id]) }}" method="POST">
        @method('PUT')
        @else
        <form action="{{ route('questions.store') }}" method="POST">
        @endif
            @csrf
            <input type="hidden" name="contest_id" value="{{ $contest_id }}" />
            <div class="mb-3">
                <label for="titleInput" class="form-label">Title</label>
                <input type="text" class="form-control" id="titleInput" name="title" value="{{ $question->title }}" required />
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="contentTextarea" class="form-label">Content</label>
                <textarea class="form-control" id="contentTextarea" name="content" rows="3" required>{{ $question->content }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="answerInput" class="form-label">Answer (Optional)</label>
                <textarea class="form-control" id="answerInput" name="answer">{{ $question->answer }}</textarea>
                @error('answer')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</x-layout>