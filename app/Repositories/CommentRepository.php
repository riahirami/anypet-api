<?php

namespace App\Repositories;

use App\Models\Ad;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentRepository
{

    public function addComment($user_id, $ad_id, Request $request)
    {
        $ad = Ad::findOrFail($ad_id);
        $comment = new Comment();
        $comment->description = $request->description;
        $comment->user_id = $user_id;
        $comment->ad_id = $ad_id;
        $comment->save();
        return $comment;
    }

    public static function commentExistsOnAd($adId, $commentId)
    {
        $exist = Comment::where('ad_id', $adId)->where('id', $commentId)->exists();
        return $exist;
    }

    public function replyComment($user_id, $ad_id, $parent_id, Request $request)
    {
        $exist = Comment::where('ad_id', $ad_id)->where('id', $parent_id)->exists();
        $ad = Ad::findOrFail($ad_id);

        if ($ad && $parent_id && $exist) {
            $comment = new Comment();
            $comment->description = $request->description;
            $comment->user_id = $user_id;
            $comment->ad_id = $ad_id;
            $comment->parent_id = $parent_id;
            $comment->save();

            return $comment;
        }

        $response = response()->json(['message' => trans('comment not found')]);
        return $response->getData()->message;
    }

    public
    function delete($id)
    {

        $comment = Comment::findOrFail($id);
        $user = auth()->id();
        if ($comment->user_id == $user) {
            $comment->delete();
            return $comment;
        } else
            return response()->json(['message' => trans('message.unauthorized')]);
    }

    public function listAdComments($ad_id)
    {
        $comments = Comment::getAllCommentsWithReplies($ad_id);


        return $comments;
    }


}
