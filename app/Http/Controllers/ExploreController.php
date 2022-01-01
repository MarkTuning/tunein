<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Factory;

class ExploreController extends Controller
{
    public function index(Factory $auth)
    {
        $search = request()->query('search');

        if ($search) {
            $posts = Post::query()->where([['body', 'like', '%'.$search.'%'], ['comment_on_post', '=', '']])->latest()->get();
        }
        else {
            $posts = Post::query()->where('comment_on_post', '=', null)->orderByDesc('created_at')->get();
        }

        $files = PostController::getPostsFiles($posts);

        $postLikes = PostController::getLikesOfPosts($posts);

        $userLikes = PostController::getUserLikedPosts($postLikes);

        $postBookmarks = PostController::getBookmarksOfPosts($posts);

        $userBookmarks = PostController::getUserBookmarkedPosts($postBookmarks);

        $postComments = PostController::getCommentsOfPosts($posts);
        
        return view('explore.index', [
            'posts' => $posts,
            'user' => $auth->guard()->user(),
            'files' => $files,
            'postLikes' => $postLikes,
            'userLikes' => $userLikes,
            'postBookmarks' => $postBookmarks,
            'userBookmarks' => $userBookmarks,
            'comments' => $postComments,
        ]);
    }
}