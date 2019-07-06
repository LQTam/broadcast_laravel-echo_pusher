<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\NewComment;

class CommentController extends Controller
{
    public function index(Post $post){
        return response()->json($post->comments()->with('user')->latest()->get());
    }

    public function store(Post $post, Request $req){
        $comment = $post->comments()->create([
            'body' => $req->body,
            'user_id' => Auth::id(),
        ]);

        $comment = Comment::where('id' , $comment->id)->with('user')->first();

        broadcast(new NewComment($comment))->toOthers();

        return $comment->toJson();
    }
}
