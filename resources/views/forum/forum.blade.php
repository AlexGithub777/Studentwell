@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Forum";
</script>
<div class="content-area">
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success" id="alert-success">
                {{ session('success') }}
            </div>
        @endif
        <!-- Page Title, Subtitle, and New Post Button -->
        <div class="row mb-4">
            <div class="col d-flex justify-content-between align-items-center mt-4">
                <div>
                    <h1 class="page-title mb-1">Forum</h1>
                </div>
                <a href="{{ route('forum.create') }}" class="btn text-white"
                    style="background-color: var(--secondary-colour);">
                    <i class="fas fa-plus me-1 fw-bold"></i> New Post
                </a>
            </div>
        </div>
        <h3 class="page-subtitle">Discussions</h3>
        <!-- Forum Posts -->
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($posts as $post)
                <div class="col">
                    <div class="forum-card h-100"
                        style="background-color: var(--main-colour); color: var(--secondary-colour); padding: 1.5rem; border-radius: 1rem;">

                        <div class="d-flex align-items-center mb-2">
                            <div class="me-2">
                                <i class="fa fa-user-circle fa-2x" style="color: var(--secondary-colour);"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: var(--secondary-colour);">{{ $post->PostTitle }}
                                </h5>
                                <small style="color: var(--secondary-colour);">
                                    {{ $post->user->first_name }} {{ $post->user->last_name }} –
                                    {{ $post->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-start gap-3 mt-2 mb-2 align-items-center"
                            style="color: var(--secondary-colour);">
                            @php
                                $isLiked = auth()->check() ? $post->isLikedByUser(auth()->user()) : false;
                            @endphp

                            <form method="POST" class="m-0 p-0"
                                action="{{ route('forum.like.post', $post->ForumPostID) }}" class="d-inline">
                                @csrf
                                <button type="submit"
                                    class="btn {{ $isLiked ? 'liked btn-sm' : 'unliked ps-2 pe-2 py-1' }}">
                                    <i class="fa fa-thumbs-up"></i> {{ $post->likes()->count() }}
                                </button>
                            </form>
                            <div><i class="fa fa-comment"></i> {{ $post->replies->count() }}</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="badge rounded-pill px-3 py-2"
                                style="background-color: var(--secondary-colour); color: white; width: fit-content;">
                                {{ $post->PostCategory }}
                            </span>
                            <div>
                                <div>
                                    <a href="{{ route('forum.show', $post->ForumPostID) }}" class="btn btn-sm"
                                        style="background-color: var(--secondary-colour); color: white; font-weight: bold;">
                                        View Post
                                    </a>
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
            @endforeach
        </div>

        <div class="mt-4">
            {{ $posts->links('pagination::bootstrap-5') }}
        </div>
    </div>
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
