@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | [Post Title] ";
</script>
<div class="container mt-4">
    <!-- Page Title and back link -->
    <div class="mb-3">
        <a href="{{ route('forum.index') }}" class="back-link h2 fw-bold">
            <i class="fa fa-chevron-left me-2 h4 fw-bold"></i> Back to Forum
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
                    <span style="background-color: var(--secondary-colour);" class="badge text-white">
                        {{ $post->PostCategory }}
                    </span>
                </span>
                <div class="post-meta mb-2"> <span>{{ $post->user->first_name }} {{ $post->user->last_name }}</span> -
                    <small>{{ $post->created_at->diffForHumans() }}</small>
                </div>
                <p class="forum-content">{{ $post->Content }}</p>

                <div class="d-flex justify-content-start gap-3 mt-2 mb-2 align-items-center"
                    style="color: var(--secondary-colour);">
                    @php
                        $liked = session('liked_posts', []);
                        $isLiked = in_array($post->ForumPostID, $liked);
                    @endphp

                    <form method="POST" action="{{ route('forum.like.post', $post->ForumPostID) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $isLiked ? 'liked' : 'unliked' }}">
                            <i class="like-icon fa fa-thumbs-up"></i> {{ $post->PostLikes }}
                        </button>
                    </form>
                    <div><i class="fa fa-comment"></i> {{ $post->replies->count() }}</div>
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
                        <div class="reply-meta mb-1"> <span class="reply-author">{{ $reply->user->first_name }}
                                {{ $reply->user->last_name }}</span>
                            <small>{{ $reply->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="forum-content">{{ $reply->Content }}</p>

                        @php
                            $likedReplies = session('liked_replies', []);
                            $replyLiked = in_array($reply->ReplyID, $likedReplies);
                        @endphp

                        <div class="reply-actions">
                            <form method="POST" action="{{ route('forum.like.reply', $reply->ReplyID) }}"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $replyLiked ? 'liked' : 'unliked' }}">
                                    <i class="fa fa-thumbs-up"></i> {{ $reply->ReplyLikes }}
                                </button>
                            </form>
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
    <form method="POST" class="forum-post-card forum-reply-form pb-4"
        action="{{ route('forum.reply', $post->ForumPostID) }}">
        @csrf
        <div class="mb-3">
            <textarea id="forum-reply-textarea" class="form-control @error('Content') is-invalid @enderror" id="Content"
                name="Content" rows="4" placeholder="Write your reply here..." required>{{ old('Content') }}</textarea>
            @error('Content')
                <div class="invalid-feedback" style="color: #dc3545;">{{ $message }}</div>
            @enderror
        </div>

        <div class="text-end"> <button type="submit" class="btn btn-submit-reply me-4">
                Post Reply
            </button>
        </div>
    </form>

</div>
@include('main.footer')
