<?php

namespace App\Factories;

use App\Classes\AimlMatcher;
use App\Classes\AimlParser;
use App\Classes\LemurLog;
use App\Exceptions\TagNotFoundException;
use App\Models\Conversation;
use App\Services\TalkService;
use App\Tags\AimlTag;
use Error;
use Exception;
use Illuminate\Support\Facades\Log;

class AimlTagFactory
{

    /**
     * @param Conversation $conversation
     * @param string $tagName
     * @param array $attributes
     *
     * @return AimlTag $tag
     * @throws TagNotFoundException
     */
    public static function create(Conversation $conversation, string $tagName = '', array $attributes = [])
    {

        try {

            if (self::isHtmlTag($tagName)) {  //if this is a HTML tag......
                $currentTagClass = "App\\Tags\\HtmlTag";

                LemurLog::info(
                    'Loading HTML tag',
                    [
                        'conversation_id' => $conversation->id,
                        'turn_id' => $conversation->currentTurnId(),
                        'tag_name' => $tagName,
                    ]
                );

                $tagType = self::getHtmlType($tagName);
                $tag = new $currentTagClass($conversation, $attributes);
                $tag->setTagType($tagType);
                $tag->setTagName($tagName);
            } else {
                $tagName = self::cleanTagClassName($tagName);

                if (self::isRecursiveTag($tagName)) {
                    $currentTagClass = "App\\Tags\\" . $tagName . "Tag";

                    LemurLog::info(
                        'Loading recursive tag',
                        [
                            'conversation_id' => $conversation->id,
                            'turn_id' => $conversation->currentTurnId(),
                            'tag_name' => $tagName,
                        ]
                    );

                    $talkService = new TalkService(config('lemur_tag'), new AimlMatcher(), new AimlParser());
                    $tag = new $currentTagClass($talkService, $conversation, $attributes);
                } elseif (self::isRecursiveCustomTag($tagName)) {
                    $currentTagClass = "App\\Tags\\Custom\\" . $tagName . "Tag";

                    LemurLog::info(
                        'Loading recursive custom tag',
                        [
                            'conversation_id' => $conversation->id,
                            'turn_id' => $conversation->currentTurnId(),
                            'tag_name' => $tagName,
                        ]
                    );

                    $talkService = new TalkService(config('customtags.lemur_customtag'), new AimlMatcher(), new AimlParser());
                    $tag = new $currentTagClass($talkService, $conversation, $attributes);
                } else {

                    //check to see if a custom tag exists first...
                    $currentTagClass = "App\\Tags\\Custom\\" . $tagName . "Tag";

                    if(!class_exists($currentTagClass)){
                        $currentTagClass = "App\\Tags\\" . $tagName . "Tag";
                    }


                    LemurLog::info(
                        'Loading tag',
                        [
                            'conversation_id' => $conversation->id,
                            'turn_id' => $conversation->currentTurnId(),
                            'tag_name' => $tagName,
                        ]
                    );

                    $tag = new $currentTagClass($conversation, $attributes);
                }
            }
            return $tag;
        }catch(Exception | Error $e){

            Throw New TagNotFoundException($e->getMessage());
        }
    }

    public static function getHtmlType($tagName)
    {
        return config('lemur_tag.html')[strtolower($tagName)];
    }


    public static function isHtmlTag($tagName): bool
    {

        if (isset(config('lemur_tag.html')[strtolower($tagName)])) {
            return true;
        }
        return false;
    }

    public static function isRecursiveTag($tagName): bool
    {

        if (isset(array_flip(config('lemur_tag.recursive'))[strtolower($tagName)])) {
            return true;
        }
        return false;
    }


    /**
     * If you want to create a recursive custom tag then create a config file called
     * config/customtages/lemur_customtag.php
     * with the following array
     *
     * return [
     *       'recursive' => [
     *           'your_tag_name',
     *           ]
     *       ];
     *
     * @param $tagName
     * @return bool
     */
    public static function isRecursiveCustomTag($tagName): bool
    {

        if (config()->has('customtags.lemur_customtag.recursive')) {
            if (isset(array_flip(config('customtags.lemur_customtag.recursive'))[strtolower($tagName)])) {
                return true;
            }
        }
        return false;
    }

    public static function cleanTagClassName($name)
    {

        $name = preg_replace('/_+|\s+/', ' ', $name);
        $name = ucwords(mb_strtolower($name));
        return str_replace(' ', '', $name);
    }
}
