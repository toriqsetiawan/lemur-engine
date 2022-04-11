<?php
namespace App\Tags;

use App\Models\Conversation;

/**
 * Class DefaultTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class DefaultTag extends AimlTag
{
    protected string $tagName = "Default";


    /**
     * DefaultTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {


        parent::__construct($conversation, $attributes);
    }




    public function closeTag()
    {

        $this->buildResponse($this->conversation->bot->default_response);
    }
}
