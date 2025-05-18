@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | {{ $post->PostTitle }}";
</script>
<div class="container mt-4">
    @if (session('success'))
        <div class="alert alert-success" id="alert-success">
            {{ session('success') }}
        </div>
    @endif
    <!-- Page Title and back link -->
    <div class="mb-3">
        <a href="{{ route('forum.index') }}" class="back-link h2 fw-bold">
            <i class="fa fa-chevron-left me-2 h4 fw-bold"></i>Back to Forum
        </a>
    </div>

    <!-- Post Details -->
    <div class="card forum-post-card">
        <div class="card-body d-flex align-items-start">
            <div class="user-avatar">
                <i class="fa fa-user"></i>
            </div>

            <div class="flex-grow-1">
                <span style="display: flex; align-items: center; gap: 0.5rem;">
                    <h3 class="forum-post-title" style="margin: 0;">{{ $post->PostTitle }}</h3>
                    <span style="background-color: var(--secondary-colour);" class="badge rounded-pill text-white">
                        {{ $post->PostCategory }}
                    </span>
                </span>
                <div class="post-meta mb-2"> <span>{{ $post->user->first_name }} {{ $post->user->last_name }}</span> -
                    <small>{{ $post->created_at->diffForHumans() }}</small>
                </div>
                <p class="forum-content">{{ $post->Content }}</p>

                <div class="d-flex justify-content-between align-items-center" style="color: var(--secondary-colour);">
                    @php
                        $isLiked = auth()->check() ? $post->isLikedByUser(auth()->user()) : false;
                    @endphp
                    <div class="d-flex justify-content-start gap-3 align-items-center">
                        <form method="POST" class="m-0 p-0" action="{{ route('forum.like.post', $post->ForumPostID) }}"
                            class="d-inline">
                            @csrf
                            <button type="submit"
                                class="btn {{ $isLiked ? 'liked btn-sm' : 'unliked ps-2 pe-2 py-1' }}">
                                <i class="fa fa-thumbs-up"></i> {{ $post->likes()->count() }}
                            </button>
                        </form>
                        <div><i class="fa fa-comment"></i> {{ $post->replies->count() }}</div>
                    </div>
                    <div>
                        @auth
                            @if (auth()->id() === $post->UserID)
                                <form action="{{ route('forum.delete', $post->ForumPostID) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this post?');"
                                    style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Replies Section -->
    @if ($post->replies->count() > 0)
        <h4 class="mb-3 h4 fw-bold" style="color: var(--secondary-colour);">
            Replies ({{ $post->replies->count() }})
        </h4>

        @foreach ($post->replies as $reply)
            <div class="card forum-post-card">
                <div class="card-body d-flex align-items-start">
                    <div class="user-avatar">
                        <i class="fa fa-user"></i>
                    </div>

                    <div class="flex-grow-1">
                        <div class="reply-meta mb-0">
                            <span class="fs-5 fw-bold">{{ $reply->user->first_name }}
                                {{ $reply->user->last_name }}</span>
                            <small class="reply-timestamp">{{ $reply->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="forum-content">{{ $reply->Content }}</p>
                        @php
                            $replyLiked = auth()->check() ? $reply->isLikedByUser(auth()->user()) : false;
                        @endphp
                        <div class="d-flex justify-content-between align-items-center">
                            <form method="POST" action="{{ route('forum.like.reply', $reply->ReplyID) }}"
                                class="d-inline">
                                @csrf
                                <button type="submit"
                                    class="btn {{ $replyLiked ? 'liked btn-sm' : 'unliked ps-2 pe-2 py-1' }}">
                                    <i class="fa fa-thumbs-up"></i> {{ $reply->likes()->count() }}
                                </button>
                            </form>
                            <!-- Delete Reply Button -->
                            @auth
                                @if (auth()->id() === $reply->UserID)
                                    <form action="{{ route('forum.delete.reply', $reply->ReplyID) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this reply?');"
                                        style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            @endauth

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    <!-- Add Reply Form -->
    <h4 class="mb-3 h5 fw-bold" style="color: var(--secondary-colour);">
        Add Your Reply
    </h4>
    <form method="POST" class="forum-post-card forum-reply-form pb-3"
        action="{{ route('forum.reply', $post->ForumPostID) }}">
        @csrf
        <div class="mb-3">
            <textarea id="forum-reply-textarea" class="form-control @error('Content') is-invalid @enderror" id="Content"
                name="Content" rows="4" placeholder="Write your reply here..." required>{{ old('Content') }}</textarea>
            @error('Content')
                <div class="invalid-feedback" style="color: #dc3545;">{{ $message }}</div>
            @enderror
        </div>

        <div class="text-end"> <button type="submit" class="btn btn-submit-reply me-3">
                Post Reply
            </button>
        </div>
    </form>

</div>
<script>
    setTimeout(() => {
        const alert = document.getElementById('alert-success');
        if (alert) {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500); // fully remove after fade out
        }
    }, 10000); // 10000 ms = 10 seconds
</script>
@include('main.footer')
