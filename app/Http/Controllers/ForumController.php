<?php

namespace App\Http\Controllers;

use App\Models\ForumPost;
use App\Models\ForumReply;
use App\Models\Like;
use App\Models\ReplyLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    // Display all forum posts
    public function index()
    {
        $posts = ForumPost::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('forum.forum', compact('posts'));
    }

    // Show form to create a new post
    public function create()
    {
        return view('forum.create-post');
    }

    // Store a new post
    public function store(Request $request)
    {
        $validated = $request->validate([
            'PostTitle' => 'required|max:50',
            'PostCategory' => 'required|max:20',
            'Content' => 'required|max:500',
        ]);

        $post = new ForumPost();
        $post->UserID = auth()->user()->id; // Use id from users table
        $post->PostTitle = $validated['PostTitle'];
        $post->PostCategory = $validated['PostCategory'];
        $post->Content = $validated['Content'];
        $post->save();

        return redirect()->route('forum.show', $post->ForumPostID)->with('success', 'Post created successfully!');
    }

    // Show a specific post with its replies
    public function show($id)
    {
        $post = ForumPost::with(['user', 'replies.user'])->findOrFail($id);
        return view('forum.post-details', compact('post'));
    }

    // Add a reply to a post
    public function reply(Request $request, $postId)
    {
        $validated = $request->validate([
            'Content' => 'required|max:500',
        ]);

        $reply = new ForumReply();
        $reply->UserID = auth()->user()->id; // Use id from users table
        $reply->PostID = $postId;
        $reply->Content = $validated['Content'];
        $reply->save();

        return redirect()->back()->with('success', 'Reply added successfully!');
    }

    public function likePost(ForumPost $forum_post) // Change the parameter name to match the route
    {
        $user = Auth::user();

        \Log::info('Attempting to like post with ID (from route model binding): ' . $forum_post->ForumPostID . ' by user ID: ' . $user->id);

        if ($forum_post->isLikedByUser($user)) {
            \Log::info('User already liked this post. Attempting to unlike.');
            $deleted = $forum_post->likes()->where('user_id', $user->id)->delete();
            \Log::info('Unliked status: ' . ($deleted ? 'Success' : 'Failed'));
        } else {
            \Log::info('User has not liked this post. Attempting to like.');
            $like = new Like();
            $like->user_id = $user->id;
            $like->forum_post_id = $forum_post->ForumPostID;
            try {
                $saved = $like->save();
                \Log::info('Like saved successfully: ' . ($saved ? 'Yes' : 'No'));
                if (!$saved) {
                    \Log::error('Error saving like');
                }
            } catch (\Exception $e) {
                \Log::error('Exception during like save: ' . $e->getMessage());
            }
        }

        return back();
    }

    public function likeReply(ForumReply $forum_reply)
    {
        $user = Auth::user();

        \Log::info('Attempting to like reply with ID: ' . $forum_reply->ReplyID . ' by user ID: ' . $user->id);

        if ($forum_reply->isLikedByUser($user)) {
            \Log::info('User already liked this reply. Attempting to unlike.');
            $deleted = $forum_reply->likes()->where('user_id', $user->id)->delete();
            \Log::info('Unliked status: ' . ($deleted ? 'Success' : 'Failed'));
        } else {
            \Log::info('User has not liked this reply. Attempting to like.');
            $like = new ReplyLike();
            $like->user_id = $user->id;
            $like->forum_reply_id = $forum_reply->ReplyID;
            $saved = $like->save();
            \Log::info('Like saved successfully: ' . ($saved ? 'Yes' : 'No'));
            if (!$saved) {
                \Log::error('Error saving reply like');
            }
        }

        return back();
    }
}