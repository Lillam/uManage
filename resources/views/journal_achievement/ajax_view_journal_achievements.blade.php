@foreach($journal_achievements as $journal_achievement)
    <div class="uk-flex journal_achievement" data-journal_achievement_id="{{ $journal_achievement->id }}">
        {{-- Journal Achievement Name (Content Editable)... --}}
        <div class="uk-width-expand uk-flex uk-flex-middle journal_achievement_content" contenteditable>{!! $journal_achievement->name !!}</div>
        {{-- Journal Achievement Delete (Button) --}}
        <div class="uk-width-auto">
            <a class="uk-button uk-button-danger delete_journal_achievement"><i class="fa fa-trash"></i></a>
        </div>
    </div>
@endforeach