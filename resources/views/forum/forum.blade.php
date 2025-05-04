@include('main.header')
@include('main.sidebar')
<script>
    document.title = "StudentWell | Forum";
</script>
<div class="content-area">
    <div class="container">
        <!-- Page Title, Subtitle, and New Post Button -->
        <div class="row mb-4">
            <div class="col d-flex justify-content-between align-items-center mt-4">
                <div>
                    <h1 class="page-title mb-1">Forum</h1>
                </div>
                <a href="{{ route('forum.create') }}" class="btn text-white"
                    style="background-color: var(--secondary-colour);">
                    <i class="fas fa-plus me-1"></i> New Post
                </a>
            </div>
        </div>
        <h3 class="page-subtitle">Discussions</h3>
        <!-- Forum Posts -->
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($posts as $post)
                <div class="col">
                    <div class="forum-card border-0 shadow-sm h-100"
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
                                $liked = session('liked_posts', []);
                                $isLiked = in_array($post->ForumPostID, $liked);
                            @endphp

                            <form method="POST" action="{{ route('forum.like.post', $post->ForumPostID) }}"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $isLiked ? 'liked' : 'unliked' }}">
                                    <i class="like-icon fa fa-thumbs-up"></i> {{ $post->PostLikes }}
                                </button>
                            </form>
                            <div><i class="fa fa-comment"></i> {{ $post->replies->count() }}</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="badge rounded-pill px-3 py-2"
                                style="background-color: var(--secondary-colour); color: white; width: fit-content;">
                                {{ $post->PostCategory }}
                            </span>
                            <a href="{{ route('forum.show', $post->ForumPostID) }}" class="btn btn-sm"
                                style="background-color: var(--secondary-colour); color: white;">
                                View Post
                            </a>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@include('main.footer')
