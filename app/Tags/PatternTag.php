<?php
namespace App\Tags;

use App\Models\Conversation;

/**
 * Class PatternTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class PatternTag extends AimlTag
{
    protected string $tagName = "Pattern";


    /**
     * PatternTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes

    //this has been intentionally left empty
    //there are no pattern tests
    //why?
    //because the pattern tag is NOT parsed at run time
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }
}
