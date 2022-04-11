<?php
namespace App\Tags;

use App\Models\Conversation;

/**
 * Class JustthatTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class JustthatTag extends ThatTag
{
    protected string $tagName = "Justthat";

    /**
     * JustthatTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes)
    {
        parent::__construct($conversation, $attributes);
    }
}
