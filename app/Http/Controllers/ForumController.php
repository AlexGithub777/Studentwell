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
    public function index(Request $request)
    {
        $query = ForumPost::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('PostTitle', 'like', "%{$search}%")
                ->orWhere('Content', 'like', "%{$search}%");
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(10);

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
            'PostTitle' => ['required', 'string', 'min:5', 'max:50'],
            'PostCategory' => ['required', 'string', 'min:3', 'max:20'],
            'Content' => ['required', 'string', 'min:10', 'max:500'],
        ], [
            'PostTitle.required' => 'Post title is required.',
            'PostTitle.string' => 'Post title must be a string.',
            'PostTitle.min' => 'Post title must be at least 5 characters.',
            'PostTitle.max' => 'Post title cannot exceed 50 characters.',

            'PostCategory.required' => 'Post category is required.',
            'PostCategory.string' => 'Post category must be a string.',
            'PostCategory.min' => 'Post category must be at least 3 characters.',
            'PostCategory.max' => 'Post category cannot exceed 20 characters.',

            'Content.required' => 'Content is required.',
            'Content.string' => 'Content must be text.',
            'Content.min' => 'Content must be at least 10 characters.',
            'Content.max' => 'Content cannot exceed 500 characters.',
        ]);

        $post = new ForumPost();
        $post->UserID = auth()->user()->id;
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

    public function delete($id)
    {
        $post = ForumPost::findOrFail($id);

        // Only allow the author to delete the post
        if (auth()->id() !== $post->UserID) {
            abort(403, 'Unauthorized');
        }

        $post->delete();

        return redirect()->route('forum.index')->with('success', 'Post deleted successfully.');
    }


    // Add a reply to a post
    public function reply(Request $request, $postId)
    {
        $validated = $request->validate([
            'Content' => ['required', 'string', 'max:500'],
        ], [
            'Content.required' => 'Reply content is required.',
            'Content.string' => 'Reply content must be text.',
            'Content.max' => 'Reply content cannot exceed 500 characters.',
        ]);

        $reply = new ForumReply();
        $reply->UserID = auth()->user()->id;
        $reply->PostID = $postId;
        $reply->Content = $validated['Content'];
        $reply->save();

        return redirect()->back()->with('success', 'Reply added successfully!');
    }

    // Delete a reply
    public function deleteReply($replyId)
    {
        $reply = ForumReply::findOrFail($replyId);

        // Only allow the author to delete the reply
        if (auth()->id() !== $reply->UserID) {
            abort(403, 'Unauthorised');
        }

        $reply->delete();

        return redirect()->back()->with('success', 'Reply deleted successfully.');
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
