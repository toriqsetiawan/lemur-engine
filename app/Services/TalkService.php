<?php


namespace App\Services;

use App\Classes\AimlMatcher;
use App\Classes\LemurLog;
use App\Classes\LemurStr;
use App\Factories\ConversationFactory;
use App\Factories\TurnFactory;
use App\Models\Bot;
use App\Models\BotAllowedSite;
use App\Models\BotKey;
use App\Models\BotWordSpellingGroup;
use App\Models\Client;
use App\Models\Conversation;
use App\Models\ConversationSource;
use App\Models\Turn;
use App\Classes\AimlParser;
use App\Models\Normalization;
use App\Models\Wildcard;
use App\Models\WordSpelling;
use Carbon\Carbon;
use http\Exception\InvalidArgumentException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use LengthException;
use Spatie\Permission\Exceptions\UnauthorizedException;

class TalkService
{

    protected $bot;
    protected $client;
    protected $conversation;
    protected $currentTurn;
    protected $config;
    protected $aimlParser;
    protected $aimlMatcher;


    /**
     * TalkService constructor.
     * @param $config
     * @param AimlMatcher $aimlMatcher
     * @param AimlParser $aimlParser
     */
    public function __construct($config, AimlMatcher $aimlMatcher, AimlParser $aimlParser)
    {
        $this->config = $config;
        $this->aimlParser = $aimlParser;
        $this->aimlMatcher = $aimlMatcher;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function checkAuthAccess($request)
    {
        $botKey = $request->header('x_bot_key');
        $bot= $request->input('bot');

        $botFound = Bot::where('slug', $bot)->whereIn('status', ['A','T'])->first();
        if(!$botFound){
            throw new ModelNotFoundException('This bot does not exist: '.$bot);
        }

        if (!$botFound->is_public) {
            $botKeyFound = BotKey::where('value', $botKey)->where('bot_id', $botFound->id)->first();
            if(!$botKeyFound){
                throw new AuthorizationException('You are not authorised to talk to this bot: '.$bot);
            }
        }

        $botAllowedSites = BotAllowedSite::where('bot_id', $botFound->id);

        //there are no allowed sites specified which means all sites are allowed
        if($botAllowedSites->count()<=0){
            return true;
        }else{

            $origin = $request->headers->get('Origin');

            $botAllowedSitesArr = $botAllowedSites->pluck('website_url','website_url');
            if (in_array($origin, $botAllowedSitesArr)) {
                return true;
            }
            foreach ($botAllowedSitesArr as $pattern) {
                if (preg_match($pattern, $origin)) {
                    return true;
                }
            }

            //return no access....
            throw new AuthorizationException('This website is not authorised to talk to this bot');


        }

        return true;
    }




    public function validateRequest($request)
    {

        //is the input within the limit
        if(!empty($request->input('message') &&
                strlen($request->input('message')) > config('lemur.max_user_char_input'))){
            throw new LengthException("Client message is over the allowed length");
        }

    }


    public function checkOwnerAccess($request)
    {
        $bot= $request->input('bot');

        $bot = Bot::where('slug', $bot)->firstOrFail();

        //this is a non public bot and the user is not the owner
        if (!$bot->is_public && $bot->user_id!==Auth::user()->id) {
            throw new UnauthorizedException('You cannot talk to this bot');
        } elseif ($bot->is_public && $bot->status=='H' && $bot->user_id!==Auth::user()->id) {
            //this is a public bot but set to hidden and the user is not the owner
            throw new UnauthorizedException('You cannot talk to this bot');
        }
        return true;
    }

    public function run($input, $debug = false)
    {
        $startTime = Carbon::now();

        $originalInput = $input;
        $message = LemurStr::removeSentenceEnders($input['message']);
        $sentences = LemurStr::splitIntoSentences($message);
        $sources = 'human';
        if (count($sentences) > 1) {
            $sources = 'multiple';
        }

        $this->initConversation($input);
        $allResponses = [];
        $responseArr=[];
        $parentTurnId = null;

        foreach ($sentences as $index => $sentence) {
            $input['message'] = $sentence;
            $turnId = $this->initTurn($input, $sources, $parentTurnId);
            //if this is not set then set it
            if (empty($parentTurnId)) {
                $parentTurnId = $turnId;
            }
            $this->talk($sentence);
            $responseArr[] = $this->getOutput();
            //this bit is just for debugging so we can build the entire journey when there is more than 1 sentence
            $allResponses['debugArr'][] = $this->responseOutput($this->getConversation())['debugArr'];
        }


        if (count($sentences)>1) {
            $debug = $allResponses['debugArr'];
            $this->initFromSentences($originalInput, $responseArr, $parentTurnId);
        } else {
            $debug = $allResponses['debugArr'][0];
            //blank this...
            $allResponses = null;
        }

        $conversation = $this->getConversation();
        $res = $this->responseOutput($conversation)['res'];

        $res['features']= $conversation->getFeatures();
        $res['metadata']['version'] = config('lemur_version.bot.id');
        $res['metadata']['date'] = $startTime;
        $res['metadata']['duration'] = Carbon::now()->diffInMilliseconds($startTime).'ms';

        if (!$debug) {
            return ['response'=>$res];
        }

        return ['response'=>$res, 'debug'=>$debug,'allResponses'=>$allResponses];
    }

    /**
     * initialise all the parts of the conversation for the service
     * this is called when a person talks to the bot
     *
     * @param $input
     * @return
     */
    public function initConversation($input)
    {

        if ($input['message']=='start a new conversation') {
            $forceNew=true;
        } else {
            $forceNew=false;
        }

        if (!isset($input['html'])) {
            $showHtml=1;
        } else {
            $showHtml=$input['html'];
        }

        //get the bot....
        $this->bot = Bot::where('slug', $input['bot'])->firstOrFail();
        //get the client....
        $this->client = Client::getClientBySlugOrCreate($this->bot, $input['client']);
        //get the conversation....
        $this->conversation = ConversationFactory::getConversationByBotClientOrCreate(
            $this->bot,
            $this->client,
            $showHtml,
            $forceNew
        );

        if(!empty($input['startingTopic']) && $this->conversation->isFirstTurn()){
            $this->conversation->setGlobalProperty('topic', $input['startingTopic']);
        }

        if($this->conversation->isFirstTurn()){
            ConversationSource::createConversationSource($this->conversation->id);
        }

        LemurLog::debug(
            'conversation started',
            [
                'conversation_id'=>$this->conversation->id,
                'input'=>$input,
                'isFirstTurn' => $this->conversation->isFirstTurn(),
                'request' => request()->all(),
                'headers' => request()->headers->all()
            ]
        );
    }

    /**
     * initialise all the parts of the conversation for the service
     * this is called when a person talks to the bot
     *
     * @param $input
     * @param string $source
     * @param int $parentTurnId
     * @return
     */
    public function initTurn($input, $source = 'human', $parentTurnId = null)
    {

        $turn = TurnFactory::createTurn($this->conversation, $input, $source, $parentTurnId);

        $this->conversation->refresh();
        $this->conversation->push();


        $this->aimlMatcher->setConversationParts($this->conversation);
        $this->aimlParser->setConversation($this->conversation);
        $this->aimlParser->resetResponse();

        LemurLog::debug(
            'turn started',
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$turn->id,
            ]
        );

        return $turn->id;
    }




    /**
     * initialise all the parts of the conversation for the service
     * this is called when a person talks to the bot
     *
     * @param $input
     * @param $responseArr
     * @param $parentTurnId
     * @throws \Exception
     */
    public function initFromSentences($input, $responseArr, $parentTurnId)
    {

        if (isset($input['html']) && $input['html']) {
            $allowHtml=true;
        } else {
            $allowHtml=false;
        }

        //get the bot....
        $this->bot = Bot::where('slug', $input['bot'])->firstOrFail();
        //get the client....
        $this->client = Client::getClientBySlugOrCreate($this->bot, $input['client']);
        //get the conversation....
        $this->conversation = ConversationFactory::getConversationByBotClientOrCreate(
            $this->bot,
            $this->client,
            $allowHtml
        );

        $this->currentTurn = TurnFactory::createCompleteTurn(
            $this->conversation,
            $input,
            'human',
            $parentTurnId
        );

        $this->conversation->refresh();

        $actualResponse = implode(". ", $responseArr);
        $actualResponse = str_replace("..", ".", $actualResponse);
        $actualResponse = str_replace("!.", "!", $actualResponse);
        $actualResponse = str_replace("?.", "?", $actualResponse);

        $this->conversation->setTurnValue('output', $actualResponse);
        $this->conversation->push();
    }


    /**
     * initialise all the parts of the conversation for the service
     * this is called when tag processes another aiml tag
     *
     * @param $conversation
     * @param $message
     * @param $source
     * @throws \Exception
     */
    public function initFromTag($conversation, $message, $source, $copyVars = true)
    {


        $conversation->debug('info', "this response includes a reparse. Reparse values below");

        //get the bot....
        $this->bot = Bot::find($conversation->bot_id);
        //get the client....
        $this->client = Client::find($conversation->client_id);
        //get the conversation....
        $this->conversation = Conversation::find($conversation->id);

        $this->currentTurn = TurnFactory::createTurn(
            $this->conversation,
            ['message'=>$message],
            $source,
            $conversation->currentParentTurnId()
        );

        if ($copyVars) {
            //as we are still in the same turn carry all the vars over
            $vars = $conversation->getVars();
            foreach ($vars as $name => $value) {
                $this->conversation->setVar($name, $value);
            }
        }


        $this->conversation->refresh();

        $this->aimlMatcher->setConversationParts($this->conversation);
        $this->aimlParser->setConversation($this->conversation);
    }


    public function getOutput()
    {
        return $this->conversation->currentConversationTurn->output;
    }



    public function getConversation()
    {
        $this->conversation->refresh();
        return $this->conversation;
    }


    public function talk($message)
    {

        LemurLog::debug(
            'turn started',
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'sentence'=>$message
            ]
        );

        $message = LemurStr::removeSentenceEnders($message);
        $sentences = LemurStr::splitIntoSentences($message);

        $fullOutput='';

        foreach ($sentences as $index => $sentence) {
            $sentence = LemurStr::removeSentenceEnders($sentence);
            $fullOutput=$this->process($sentence);
            //if we have more than one we are going to shift it
            //liz
        }

        //some internal tag reparses such as the SRAI tag do not return a response.
        //as they are just <think> actions
        //so we only want to set the value to "I don't have a response for that." if this is a human
        if ($fullOutput=='' && $this->conversation->isHumanTurn()) {
            $fullOutput = $this->conversation->bot->default_response;

            //if it still empty then ...
            if ($fullOutput=='') {
                $fullOutput = "I don't have a response for that.";
            }
        }

        $this->conversation->setTurnValue('output', $fullOutput);


        $this->conversation->push();
    }


    /**
     * @param $sentence
     * @return string
     */
    public function process($sentence)
    {
        $preparedSentence = $sentence;

        $preparedSentence = $this->applyPrePlugins($preparedSentence);

        $preparedSentence = LemurStr::normalizeInput($preparedSentence);

        $this->checkAndSetNormalizations($preparedSentence, $sentence);

        //initially we will check to see if there is 'learnt' response from the same client...
        if ($output =$this->aimlMatcher->matchClientCategory($preparedSentence)) {
            return $output;
        } else {
            $this->conversation->debug('categories.find.sentence', $preparedSentence);
            //if not go to the big db find the match
            $categories = $this->aimlMatcher->match($preparedSentence);
            //update the state with a list of matched category id's
            $this->conversation->debug('categories.matches.all', $categories->pluck('slug'));
            //expand out any tags in the pattern, that, set
            $categories = $this->aimlMatcher->replaceInputTags($categories);

            $categories = $this->aimlMatcher->filter($categories);

            if (!$categories) {
                return '';
            } elseif (is_countable($categories)) {
                $this->conversation->debug('categories.matches.filtered', $categories->pluck('slug'));
                $category = $this->aimlMatcher->score($categories);
                if(!$category){
                    return '';
                }
                $this->conversation->debug('categories.matches.top', [$category->toArray()]);
                $this->aimlParser->setCategory($category);
            } else {
                $category = $categories;
                $this->aimlParser->setCategory($categories);
            }

            $this->aimlParser->extractAllWildcards($category);
            $output = $this->aimlParser->parseTemplate();

             return $output;
        }


        return '';
    }


    /**
     * @param $aiml
     * @return string
     */
    public function parseDirect($aiml)
    {

        return $this->aimlParser->parse($aiml);
    }


    /**
     * @param $preparedSentence
     * @param $sentence
     * @return string
     */
    public function checkAndSetNormalizations($preparedSentence, $sentence)
    {

        //there has been a change so add a record to the db...
        if ($preparedSentence!=mb_strtoupper($sentence)) {
            $languageId = $this->conversation->bot->language_id;

            $result = Normalization::where('normalized_value', $preparedSentence)
                ->where('original_value', $sentence)->where('language_id', $languageId)->first();

            if ($result==null) {
                $normalization = new Normalization();
                $normalization->normalized_value = $preparedSentence;
                $normalization->original_value = $sentence;
                $normalization->language_id = $languageId;
                $normalization->save();
            }
        }
    }

    //

    public function responseOutput($conversation)
    {

        //none of this stuff is displayed in the API
        //only when we are using the admin area...
        //so we only have to bother when we have logged in user

            $res['conversation']['input'] = $conversation->getTurnValue('input', 0);
            $res['conversation']['output'] = $conversation->getTurnValue('output', 0);
            $res['conversation']['id'] = $conversation->slug;
            $res['conversation']['topic'] = $conversation->getGlobalProperty('topic');
            $res['bot']['id'] = $conversation->bot->slug;
            $res['bot']['name'] = $conversation->bot->name;
            $res['bot']['image'] = $conversation->bot->imageUrl;
            $res['client']['id'] = $conversation->client->slug;
            $res['client']['image'] = $conversation->client->imageUrl;
            $res['client']['name'] = $conversation->getGlobalProperty('name');
            $res['features'] = $conversation->getFeatures();


        if (Auth::user()) {
            $debugArr['wildcards']['star'] = Wildcard::where('conversation_id', $conversation->id)
                ->where('type', 'star')->latest('id')->take(10)->pluck('value');
            $debugArr['wildcards']['topicstar'] = Wildcard::where('conversation_id', $conversation->id)
                ->where('type', 'topicstar')->latest('id')->take(10)->pluck('value');
            $debugArr['wildcards']['thatstar'] = Wildcard::where('conversation_id', $conversation->id)
                ->where('type', 'thatstar')->latest('id')->take(10)->pluck('value');


            $debugArr['globals']= $conversation->getGlobalProperties();
            $debugArr['locals']= $conversation->getVars();
            $debugArr['debug']= $conversation->getDebugs();

            if (Auth::user()->hasRole('admin')) {
                $debugArr['admin']= $conversation->getAdminDebugs();
                $debugArr['admin']['turn_id']= $conversation->currentTurnId();
                $debugArr['admin']['parent_turn_id']= $conversation->currentParentTurnId();
            }
        } else {
            $debugArr=[];
        }

        return ['res'=>$res,'debugArr'=>$debugArr];
    }

    public function applyPrePlugins($str){

        return $this->applySpellingCorrections($str);

    }

    public function applySpellingCorrections($str){

        $botWordSpellingGroupIds = BotWordSpellingGroup::where('bot_id', $this->bot->id)->pluck('id','id');

        if(count($botWordSpellingGroupIds)==0){

            return $str;
        }

        $allWords = $words = explode(" ",$str);

        $countWords = count($words);
        for($i=0; $i<=$countWords; $i++){
            $j=$i+1;
            $k=$i+2;
            if(isset($words[$j])){
                $allWords[] = $words[$i].' '.$words[$j];
            }
            if(isset($words[$j]) && isset($words[$k])){
                $allWords[] = $words[$i].' '.$words[$j].' '.$words[$k];
            }
        }

        $wordSpellings = WordSpelling::whereIn('word_spelling_group_id',$botWordSpellingGroupIds)->whereIn('word',$allWords)->pluck( 'replacement','word');

        if(count($wordSpellings)==0){

            return $str;
        }


        foreach($wordSpellings as $replacement => $word){
            $str = preg_replace("~\b$replacement\b~is",$word,$str);
        }



        return $str;
    }
}
