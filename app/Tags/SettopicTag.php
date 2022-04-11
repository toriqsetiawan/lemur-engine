<?php
namespace App\Tags;

use App\Models\Conversation;

/**
 * Class SettopicTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class SettopicTag extends SetTag
{
    protected string $tagName = "Settopic";

    /**
     * SettopicTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes)
    {
        $attributes['name']='topic';

        parent::__construct($conversation, $attributes);
    }
}
