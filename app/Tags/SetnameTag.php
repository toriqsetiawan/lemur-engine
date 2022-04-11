<?php
namespace App\Tags;

use App\Models\Conversation;

/**
 * Class SetnameTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class SetnameTag extends SetTag
{
    protected string $tagName = "Setname";

    /**
     * SetnameTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes)
    {
        $attributes['name']='name';

        parent::__construct($conversation, $attributes);
    }
}
