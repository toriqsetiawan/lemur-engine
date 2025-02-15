<?php

namespace App\Http\Controllers;

use App\DataTables\ConversationSourceDataTable;
use App\Http\Requests\CreateConversationSourceRequest;
use App\Http\Requests\UpdateConversationSourceRequest;
use App\Repositories\ConversationSourceRepository;
use Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Response;
use App\Models\ConversationSource;

class ConversationSourceController extends AppBaseController
{
    private ConversationSourceRepository $conversationSourceRepository;

    //to help with data testing and form settings
    public string $link = 'conversationSources';
    public string $htmlTag = 'conversation-sources';
    public string $title = 'Conversation Sources';
    public string $resourceFolder = 'conversation_sources';

    public function __construct(ConversationSourceRepository $conversationSourceRepo)
    {
        $this->conversationSourceRepository = $conversationSourceRepo;
    }

    /**
     * Display a listing of the ConversationSource.
     *
     * @param ConversationSourceDataTable $conversationSourceDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(ConversationSourceDataTable $conversationSourceDataTable)
    {
        $this->authorize('viewAny', ConversationSource::class);
        $conversationSourceDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $conversationSourceDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new ConversationSource.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        return view('conversation_sources.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }


    /**
     * Directly saving new items is not allowed
     *
     */
    public function store()
    {
        //we do not store items directly
        abort(403, 'Unauthorized action.');
    }

    /**
     * Display the specified ConversationSource.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $conversationSource = $this->conversationSourceRepository->getBySlug($slug);

        $this->authorize('view', $conversationSource);

        if (empty($conversationSource)) {
            Flash::error('Conversation Source not found');

            return redirect(route('conversationSources.index'));
        }

        return view('conversation_sources.show')->with(
            ['conversationSource'=>$conversationSource, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Generic message page - telling user this action is not allowed
     *
     */
    public function edit()
    {
        //show error message page
        return view('conversation_sources.edit')->with(
            [ 'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Directly updating new items is not allowed
     *
     */
    public function update()
    {
        //we do not store items directly
        abort(403, 'Unauthorized action.');
    }

    /**
     * Remove the specified ConversationSource from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $conversationSource = $this->conversationSourceRepository->getBySlug($slug);

        $this->authorize('delete', $conversationSource);

        if (empty($conversationSource)) {
            Flash::error('Conversation Source not found');

            return redirect(route('conversationSources.index'));
        }

        $this->conversationSourceRepository->delete($conversationSource->id);

        Flash::success('Conversation Source deleted successfully.');

        return redirect()->back();
    }
}
