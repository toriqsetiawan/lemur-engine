<?php

namespace App\Factories;

use App\Classes\LemurStr;
use App\Models\Client;
use App\Models\Conversation;
use App\Models\Turn;
use Illuminate\Support\Facades\Log;

class TurnFactory
{
    /**
     * @param $conversation
     * @param $input
     * @param $source
     * @param null $parentTurnId
     * @return Turn
     */
    public static function createTurn($conversation, $input, $source, $parentTurnId = null)
    {
        $turn = new Turn([
            'conversation_id' => $conversation->id,
            'parent_turn_id' => $parentTurnId,
            "input" => $input['message'],
            "source" => $source
        ]);
        $turn->save();

        return $turn;
    }


    /**
     * @param $conversation
     * @param $input
     * @return Turn
     * @param $source
     * @throws \Exception
     */
    public static function createCompleteTurn($conversation, $input, $source, $parentTurnId = null)
    {

        $currentLog = new Turn([
            'conversation_id' => $conversation->id,
            'parent_turn_id' =>$parentTurnId,
            "input" => $input['message'],
            'status' => 'C',
            "source" => $source
        ]);
        $currentLog->save();


        $currentLog = $conversation->currentConversationTurn;

        return $currentLog;
    }
}
