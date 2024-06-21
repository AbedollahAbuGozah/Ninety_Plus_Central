<?php

namespace App\Http\Controllers\Api\V1;

use App\Facades\NinetyPlusCentralFacade;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\CourseStudent;
use App\services\CommentService;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class InvoiceController extends BaseController
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $invoices = CourseStudent::whereNotNull('properties-> ')
            ->pluck('properties')->toArray();

        return $this->success(($invoices), 'message.index.success', 201);

    }

    public function store(CommentRequest $request, $commentableType, $commentableId)
    {
        if (!($commentable_model = NinetyPlusCentralFacade::getCommentableModel($commentableType))) {
            throw new RouteNotFoundException('route not found');
        }


        $validatedData = $request->safe()->all();;
        $commentable = $commentable_model::findOrFail($commentableId);
        $comment = $commentable->addComment($validatedData);

        return $this->success(CommentResource::make($comment), 'message.store.success', 201);
    }

    public function show(Comment $comment)
    {
        return $this->success(CommentResource::make($comment), 'message.show.success', 201);
    }

    public function update(CommentRequest $request, Comment $comment)
    {
        $validatedData = $request->safe()->all();
        $this->commentService->update($validatedData, $comment);
        return $this->success(CommentResource::make($comment), 'message.update.success', 201);
    }

    public function destroy(Comment $comment)
    {
        $this->commentService->delete($comment);
        return $this->success([], 'message.delete.success', 201);
    }
}
