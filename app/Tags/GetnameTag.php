<?php
namespace App\Tags;

use App\Models\Conversation;

/**
 * Class GetnameTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class GetnameTag extends GetTag
{
    protected string $tagName = "Getname";

    /**
     * GetnameTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes)
    {
        $attributes['name']='name';

        parent::__construct($conversation, $attributes);
    }
}
