<h2 class="section_title">Comments</h2>
{{-- This file is specifically utilised for iterating over task comments where they are necesasry, this specific file
is to be returned or included whenever ($task) exists with the relationship ->task_comments associated to it. all
comments will be universalised around the system regardless where we are showing them, in which this is the go to file
for just that. --}}
@foreach ($taskComments as $taskComment)
    <div class="comment_item uk-flex" data-task_comment_id="{{ $taskComment->id }}">
        <div class="uk-width-expand">
            {{-- The name of the individual that has written this particular comment, along with the date that the
            comment was specifically created. --}}
            <h3 style="margin-bottom: 10px;">
                {!! UserPrinter::userBadge($taskComment->user) !!}
                {{ $taskComment->user->getFullName() }}
                <span class="small">
                    {{ $taskComment->created_at->format(\App\Helpers\DateTime\DateTimeHelper::FORMAT_Daynth_M_Y) }}
                </span>
            </h3>
            {{-- The overall wrapper for the comment itself, this will be including options for the editing, replying
            as well as the deleting of the comment, depending on the context of what relevance the user has to the
            comment. --}}
            <div class="task_comment_wrapper">
                <div class="task_comment_content box">
                    {!! $taskComment->content !!}
                </div>
                <div class="comment_options">
                    @if ($taskComment->user->id === Auth::id())
                        <a class="edit_comment"><i class="fa fa-edit"></i> Edit</a>
                        <a class="delete_comment"><i class="fa fa-trash"></i> Delete</a>
                    @else
                        <a class="reply_comment"><i class="fa fa-reply"></i> Reply</a>
                        @if ($task->taskReporterUser->id === Auth::id())
                            <a class="delete_comment"><i class="fa fa-trash"></i> Delete</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach
{{-- This particular section of the code is specifically for being able to make new task comments, this is the segment
that will give the user the functionality in order for writing a comment, and then choosing to either save the comment
or cancel the comment and then continuing with the functionality as such. --}}
<div class="uk-flex new_comment"
     data-make_task_comment_url="{{ route('projects.tasks.comment.create') }}">
    <div class="uk-width-expand">
        <div class="box_wrapper comment">
            <span class="placeholder">Write a comment...</span>
        </div>
        <div class="comment_options uk-margin-small-top uk-hidden">
            <a class="save save_comment">Save</a>
            <a class="cancel cancel_comment">Cancel</a>
        </div>
    </div>
</div>

@if ($total_pages > 1)
    <div class="pagination uk-flex">
        <div class="uk-width-expand">
            @if($page > 1)
                <a class="paginate_previous">Previous</a>
            @endif
        </div>
        <div class="uk-width-auto">
            {{ $page }} / {{ $total_pages }}
        </div>
        <div class="uk-width-expand uk-text-right">
            @if ($page < $total_pages)
                <a class="paginate_next">Next</a>
            @endif
        </div>
    </div>
@endif