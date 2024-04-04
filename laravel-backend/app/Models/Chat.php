<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
        'is_edited',
        'is_deleted',
        'deleted_from_sender',
        'deleted_from_receiver',

    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public static function getMessagesQueryBetweenTwoUsers($request, $sender_id, $receiver_id)
    {
        //select * from messages where ((sender_id=1 and receiver_id = 2) or (receiver_id = 1 and sender_id =2))
        $query = self::with(['sender', 'receiver'])->where(function($q) use($request, $sender_id, $receiver_id) {
            $q->where(function($sub) use ($sender_id, $receiver_id) {
                $sub->where('sender_id', $sender_id)
                    ->where('receiver_id', $receiver_id);
            })
             ->orWhere(function($sub) use ($sender_id, $receiver_id) {
                 $sub->where('receiver_id', $sender_id)
                     ->where('sender_id', $receiver_id);
             });
        });

        return $query;
    }
}
