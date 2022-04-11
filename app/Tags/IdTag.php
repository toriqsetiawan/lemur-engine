<?php
namespace App\Tags;

use App\Models\Conversation;

/**
 * Class IdTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class IdTag extends AimlTag
{
    protected string $tagName = "Id";


    /**
     * IdTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }

    public function closeTag()
    {

        $conversationSlug = $this->conversation->slug;
        $this->buildResponse($conversationSlug);
    }
}
