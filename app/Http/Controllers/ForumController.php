<?php

namespace App\Http\Controllers;

use App\Models\ForumPost;
use App\Models\ForumReply;
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
        $post->PostLikes = 0;
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
        $reply->ReplyLikes = 0;
        $reply->save();

        return redirect()->back()->with('success', 'Reply added successfully!');
    }

    // Like or unlike a post
// Like or unlike a post
public function likePost($id)
{
    $post = ForumPost::findOrFail($id);

    // Track liked posts in session
    $liked = session()->get('liked_posts', []);

    // Check if the post is already liked
    if (in_array($id, $liked)) {
        // If already liked, unlike (decrement PostLikes and remove from session)
        $post->PostLikes -= 1;  // Decrement the PostLikes field
        $liked = array_diff($liked, [$id]); // Remove the post from the liked array
    } else {
        // If not liked, like the post (increment PostLikes and add to session)
        $post->PostLikes += 1;  // Increment the PostLikes field
        $liked[] = $id;  // Add the post to the liked array
    }

    // Manually save the PostLikes and explicitly prevent timestamp update
    $post->save(['timestamps' => false]);

    // Update the session
    session(['liked_posts' => $liked]);

    return redirect()->back();
}




    // Like a reply
    public function likeReply($id)
    {
        $reply = ForumReply::findOrFail($id);
        $reply->ReplyLikes += 1;
        $reply->save();

        return redirect()->back();
    }
}