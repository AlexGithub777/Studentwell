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
    /**
     * Display the forum page with list of the forum posts.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
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


    /**
     * Display the form to create a new forum post.
     * 
     * @return \Illuminate\View\View
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to create a post.');
        }

        // Return the view for creating a new post
        return view('forum.create-post');
    }

    // Store a new post
    public function store(Request $request)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to create a post.');
        }

        // Validate the request data
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

        // Create a new forum post
        $post = new ForumPost();
        $post->UserID = auth()->user()->id;
        $post->PostTitle = $validated['PostTitle'];
        $post->PostCategory = $validated['PostCategory'];
        $post->Content = $validated['Content'];
        $post->save();

        // Redirect to the newly created post with a success message
        return redirect()->route('forum.show', $post->ForumPostID)->with('success', 'Post created successfully!');
    }

    /**
     * Display the forum post of a specific forum post.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Find the post by ID and eager load the user and replies with their users
        $post = ForumPost::with(['user', 'replies.user'])->findOrFail($id);

        // Check if the post exists
        return view('forum.post-details', compact('post'));
    }

    /**
     * Delete a forum post.
     * 
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to delete a post.');
        }
        // Find the post by ID
        $post = ForumPost::findOrFail($id);

        // Only allow the author to delete the post
        if (auth()->id() != $post->UserID) {
            return redirect()->route('forum.index')->with('error', 'You do not have permission to delete this forum post.');
        }

        // Delete the post and its replies
        $post->delete();

        // Optionally, delete all replies associated with the post
        return redirect()->route('forum.index')->with('success', 'Post deleted successfully.');
    }

    /**
     * Add a reply to a forum post.
     * 
     * @param Request $request
     * @param int $postId
     */
    public function reply(Request $request, $postId)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to reply to a post.');
        }

        // Find the post by ID and check if it exists
        if (!ForumPost::find($postId)) {
            return redirect()->back()->with('error', 'Post not found.');
        }

        // Validate the reply content
        $validated = $request->validate([
            'Content' => ['required', 'string', 'max:500'],
        ], [
            'Content.required' => 'Reply content is required.',
            'Content.string' => 'Reply content must be text.',
            'Content.max' => 'Reply content cannot exceed 500 characters.',
        ]);

        // Create a new reply
        $reply = new ForumReply();
        $reply->UserID = auth()->user()->id;
        $reply->PostID = $postId;
        $reply->Content = $validated['Content'];
        $reply->save();

        // Redirect back to the post with a success message
        return redirect()->back()->with('success', 'Reply added successfully!');
    }

    /**
     * Delete a reply from a forum post.
     * 
     * @param int $replyId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteReply($replyId)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to delete a reply.');
        }

        // Find the reply by ID
        $reply = ForumReply::findOrFail($replyId);

        // Only allow the author to delete the reply
        if (auth()->id() != $reply->UserID) {
            return redirect()->back()->with('error', 'You do not have permission to delete this reply.');
        }

        // Delete the reply
        $reply->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Reply deleted successfully.');
    }
    /**
     * Toggle the like status for a forum post.
     *
     * If the authenticated user has already liked the post, their like will be removed.
     * Otherwise, a new like will be added.
     *
     * @param ForumPost $forum_post The forum post being liked or unliked (route model binding).
     * @return \Illuminate\Http\RedirectResponse Redirects back to the previous page.
     */

    public function likePost(ForumPost $forum_post)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to like a post.');
        }

        $user = Auth::user();

        // If the post is already liked by the user, remove the like
        if ($forum_post->isLikedByUser($user)) {
            $forum_post->likes()->where('user_id', $user->id)->delete();
        } else {
            // Otherwise, create a new like record
            $like = new Like();
            $like->user_id = $user->id;
            $like->forum_post_id = $forum_post->ForumPostID;
            $like->save();
        }

        return back();
    }

    /**
     * Toggle the like status for a forum reply.
     *
     * If the authenticated user has already liked the reply, their like will be removed.
     * Otherwise, a new like will be added.
     *
     * @param ForumReply $forum_reply The forum reply being liked or unliked (route model binding).
     * @return \Illuminate\Http\RedirectResponse Redirects back to the previous page.
     */
    public function likeReply(ForumReply $forum_reply)
    {
        $user = Auth::user();

        // If the reply is already liked by the user, remove the like
        if ($forum_reply->isLikedByUser($user)) {
            $forum_reply->likes()->where('user_id', $user->id)->delete();
        } else {
            // Otherwise, create a new like record
            $like = new ReplyLike();
            $like->user_id = $user->id;
            $like->forum_reply_id = $forum_reply->ReplyID;
            $like->save();
        }

        return back();
    }
}
