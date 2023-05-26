<?php

namespace App\Repositories;

use App\Events\MessageEvent;
use App\Models\Messages;
use App\Notifications\MessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageRepository
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        $sender_id = auth()->id();

        $msg = Messages::create([
            'sender_id' => $sender_id,
            'receiver_id' => intval($request->receiver_id),
            'message' => $request->message,
        ]);

        event(new MessageEvent($sender_id, intval($request->receiver_id), $request->message));
        $msg->receiver->notify(new MessageNotification($msg));
        return response()->json(['message' => 'your message has been created successfully'], 201);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getConversationMsg(Request $request)
    {
        $user1 = auth()->id();
        $user2 = $request->user_id;

        $conversations = Messages::with('sender', 'receiver')
            ->where('sender_id', $user1)
            ->where('receiver_id', $user2)
            ->orWhere('sender_id', $user2)
            ->where('receiver_id', $user1)
            ->orderBy('created_at', 'asc')
            ->get();

        return $conversations;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getConversations()
    {
        $user = auth()->id();

        $subquery = Messages::query()
            ->select('sender_id', 'receiver_id', DB::raw('MAX(created_at) as latest_created_at'))
            ->where('sender_id' , $user)
            ->groupBy('sender_id', 'receiver_id');

        $conversations = Messages::query()
            ->with('sender','receiver')
            ->joinSub($subquery, 'latest_messages', function ($join) {
                $join->on('messages.sender_id', '=', 'latest_messages.sender_id')
                    ->on('messages.receiver_id', '=', 'latest_messages.receiver_id')
                    ->on('messages.created_at', '=', 'latest_messages.latest_created_at');
            })
//            ->orderByDesc('latest_messages.latest_created_at')
            ->get();

        return $conversations;
    }


}
