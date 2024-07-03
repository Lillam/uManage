@extends('template.master')
@section('css')
    {!! ($vs->css)('views/journal_diets/view_journal_diet_dashboard') !!}
@endsection
@section('sidebar')
    @include('library.journal.journals_sidebar')
@endsection
@section('title-block')
    <div class="uk-width-expand">
        <p>Dashboard</p>
    </div>
@endsection
@section('body')
    <div class="journal_diet_items">
        <div uk-grid>
            @foreach ($journalDietItems as $journalDietItem)
                <div class="uk-width-1-4">
                    <div class="journal_diet_item">
                        <h2>{{ $journalDietItem->name }}</h2>
                        <div class="journal_diet_item_calories">
                            <p>{{ $journalDietItem->calories }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
