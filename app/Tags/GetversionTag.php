<?php
namespace App\Tags;

use App\Models\Conversation;

/**
 * Class GetversionTag
 * @package App\Tags
 */
class GetversionTag extends VersionTag
{
    protected string $tagName = "Getversion";

    /**
     * GetversionTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes)
    {
        parent::__construct($conversation, $attributes);
    }
}
