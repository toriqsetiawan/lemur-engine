<?php
namespace App\Tags;

use App\Classes\LemurLog;
use App\Models\Conversation;

/**
 * Class VersionTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class VersionTag extends AimlTag
{
    protected string $tagName = "Version";


    /**
     * VersionTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }



    public function closeTag()
    {

                LemurLog::debug(
                    __FUNCTION__,
                    [
                    'conversation_id'=>$this->conversation->id,
                    'turn_id'=>$this->conversation->currentTurnId(),
                    'tag_id'=>$this->getTagId(),
                    'attributes'=>$this->getAttributes()
                    ]
                );
        $tagContents = config('lemur_version.bot.id');
        $this->buildResponse($tagContents);
    }
}
