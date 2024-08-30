<div class="row align-items-center justify-content-between">
    <div id="showFlashMessageResponse">
        <div class="alert alert-danger error d-none">
            <p class="errorMessageDelete"></p>
        </div>
    </div>
    <div class="col-lg-7 col-md-7">
        <h4 class="mb-1">View All Comments</h4>
        <p class="showComments ">
            <mark class="text-muted p-1 px-2 rounded"><span class="commentsCount">{{ count($comments) }}</span>
                Comments</mark>
        </p>
    </div>
    <div class="col-lg-3 col-md-3">
        <button class="btn btn-default btn-secondary float-md-end mt-md-0 mt-2" id="createComment">Comment</button>
    </div>
</div>

<div class="commentListing list d-none">
    <div class="row align-items-center mb-4 border-bottom pb-3 commentsAdd">
        @forelse($comments as $key => $comment)
            <div class="mt-4 comment-box parentComment{{ $comment->id }}">
                <div class="comment-image text-center mt-2">
                    <img class="rounded-circle checklist-image" style="object-fit: cover"
                        title="{{ $comment->createdBy->name }}"
                        src="{{ $comment->createdBy->avatar
                            ? asset(\App\Models\User::AVATAR_UPLOAD_PATH . $comment->createdBy->avatar)
                            : asset('assets/images/img.png') }}"
                        alt="profile">
                </div>
                <div class="comment-content rounded w-100">
                    <h5 class="mb-1">{{ $comment->createdBy->name }}</h5>
                    <p class="comment-date text-muted">{{ $comment->created_at->diffForHumans() }}</p>
                    <p class="comment">
                        <span>
                            @forelse($comment->mentionedMember as $key => $commentMentionedMember)
                                <a href="#"> {{ '@' . $commentMentionedMember->user->name }}</a>
                            @empty
                            @endforelse
                        </span>
                        {{ $comment->description }}
                    </p>

                    <div class="comment-reply position-relative commentReply{{ $comment->id }}">

                        <div class="row number-reply d-flex align-items-center justify-content-between ">
                            <div class="col-lg-6">
                                <p class="text-muted pt-1" id="showReply" data-id="{{ $comment->id }}">
                                    <span class="replyCount{{ $comment->id }}">{{ count($comment->replies) ?? 0 }}
                                    </span> reply
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <button data-comment="{{ $comment->id }}" data-mention="{{ $comment->created_by }}"
                                    class="replyCreate btn btn-secondary btn-xs float-end">
                                    Reply
                                </button>
                            </div>
                        </div>


                        <div class="reply{{ $comment->id }} d-none" id="cmntReply">
                            @forelse($comment->replies as $key => $reply)
                                <div class="comment-box ps-4 mt-2 singleReply{{ $reply->id }}">
                                    <div class="comment-image text-center mt-2">
                                        <img class="rounded-circle checklist-image" style="object-fit: cover"
                                            title="{{ $reply->createdBy->name }}"
                                            src="{{ $reply->createdBy->avatar
                                                ? asset(\App\Models\User::AVATAR_UPLOAD_PATH . $reply->createdBy->avatar)
                                                : asset('assets/images/img.png') }}"
                                            alt="profile">
                                    </div>
                                    <div class="comment-content rounded w-100 bg-white">
                                        <h5 class="mb-1">{{ $reply->createdBy->name }}</h5>
                                        <p class="comment-date text-muted">{{ $reply->created_at->diffForHumans() }}
                                        </p>
                                        <p class="comment">
                                            <span>
                                                @forelse($reply->mentionedMember as $key => $replyMentionedMember)
                                                    <a href="#">{{ '@' . $replyMentionedMember->user->name }}
                                                    </a>
                                                @empty
                                                @endforelse
                                            </span>
                                            {{ $reply->description }}
                                        </p>
                                        <a class="replyDelete" id="deleteComment" data-title="Reply"
                                            data-id="{{ $reply->id }}" data-comment="{{ $comment->id }}"
                                            href="{{ route('admin.reply.delete', $reply->id) }}">
                                            <i class="link-icon" data-feather="x"></i>
                                        </a>
                                    </div>
                                </div>

                            @empty
                            @endforelse
                        </div>
                    </div>
                    <a class="commentDelete" id="deleteComment" data-title="Comment" data-comment="{{ $comment->id }}"
                        data-id="{{ $comment->id }}" href="{{ route('admin.comment.delete', $comment->id) }}">
                        <i class="link-icon" data-feather="x"></i>
                    </a>
                </div>
            </div>
        @empty

        @endforelse
    </div>
</div>

<div class="commentForm d-none mt-4" id="replyForm">
    <div class="row test">
        <form id="taskCommentForm" action="{{ route('admin.task-comment.store') }}" method="post"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="task_id" readonly required value="{{ $taskDetail->id }}" />
            <input type="hidden" id="commentId" name="comment_id" readonly required value="" />
            <div class="comment-section">
                <div class="row align-items-center rounded border">
                    <div class="col-lg-3 col-md-3 select-comment">
                        <select class="form-select" id="mention" name="mentioned[]" multiple="multiple">
                            @foreach ($taskDetail->assignedMembers as $key => $datum)
                                @if (getAuthUserCode() != $datum->user->id)
                                    <option value="{{ $datum->user->id }}">{{ ucfirst($datum->user->name) }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-7 col-md-7 ps-0">
                        <div class="position-relative">
                            <textarea class="form-control border-0 rounded-0 pe-5 ps-md-0" id="description" name="description" rows="2"
                                cols="50" required placeholder="write a comment">
                            </textarea>
                            <a class="replyicon "><i class="link-icon" data-feather="x">X</i></a>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-3 ps-md-0">
                        <button type="button" class="btn btn-success btn-xs w-100 py-2" id="commentSubmit">
                            <i class="link-icon" data-feather="navigation"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
