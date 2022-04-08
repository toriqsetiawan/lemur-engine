<?php

namespace App\Http\Controllers\API;

use App\Classes\LemurLog;
use App\Classes\LemurStr;
use App\DataTables\BotDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateBotRequest;
use App\Http\Requests\CreateTalkRequest;
use App\Http\Requests\UpdateBotRequest;
use App\Http\Resources\BotMetaResource;
use App\Http\Resources\ChatMetaResource;
use App\Models\Client;
use App\Models\Conversation;
use App\Models\Turn;
use App\Repositories\BotRepository;
use App\Repositories\ConversationRepository;
use App\Services\TalkService;
use Carbon\Carbon;
use Exception;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Response;
use App\Models\Bot;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class TalkAPIController extends AppBaseController
{

    /** @var  ConversationRepository */
    private $conversationRepository;

    public function __construct(ConversationRepository $conversationRepo)
    {
        $this->conversationRepository = $conversationRepo;
    }

    /**
     * Initiate a talk to the bot...
     * @param CreateTalkRequest $request
     * @param TalkService $talkService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function store(CreateTalkRequest $request, TalkService $talkService)
    {

        try {
            $talkService->checkAuthAccess($request);

            $talkService->validateRequest($request);

            if (!empty($request->input('message'))) {


                //return the service and return the response only
                $parts = $talkService->run($request->input(), false);
                $res = $parts['response'];
            } else {
                $res = [];
            }
            return $this->sendResponse($res, 'Conversation turn successful');
        } catch (Exception $e) {
            return $this->extractSendError($e);
        }
    }

    /**
     * Initiate a talk to the bot...
     * @param CreateTalkRequest $request
     * @param TalkService $talkService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     * @deprecated you should now send meta requests to GET /api/meta/{botId} instead of using POST
     */
    public function old_meta(CreateTalkRequest $request, TalkService $talkService)
    {
        $botSlug = $request->input('bot', false);
        return $this->meta($botSlug,  $request,  $talkService);
    }

    /**
     * Initiate a talk to the bot...
     * @param $botSlug
     * @param CreateTalkRequest $request
     * @param TalkService $talkService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function meta($botSlug, CreateTalkRequest $request, TalkService $talkService)
    {
        Log::debug('Meta Post', $request->all());

        try {
            $request->merge([
                'bot' => $botSlug,
            ]);
            $talkService->checkAuthAccess($request);
            if ($botSlug) {
                $metaBotResource = Bot::where('slug', $botSlug)->firstOrFail();
                //add the clientId to the collection so we can return it in the meta resource
                $clientId = $request->input('clientid', null);
                $metaBotResource->clientId = $request->input('clientid', $clientId);
                /**
                 * now we have a bot id and a client id we might be able to find our conversation....
                 */
                $conversationId = null;
                if($clientId !== null){

                    $conversation = Conversation::where('bot_id',$metaBotResource->id)->where('client_id', $clientId)->first();
                    if($conversation !== null){
                        $conversationId = $request->input('conversationId', $conversation->slug);
                    }
                }

                $metaBotResource->conversationId = $conversationId;

                return $this->sendResponse(new ChatMetaResource($metaBotResource), 'Bot Meta retrieved successfully');
            } else {
                throw new UnprocessableEntityHttpException('Missing botId');
            }
        } catch (Exception $e) {
            return $this->extractSendError($e);
        }
    }

}
