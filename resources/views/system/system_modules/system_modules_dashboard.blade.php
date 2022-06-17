@extends('template.master')
@section('css')
    {!! ($vs->css)('views/system_modules/view_system_modules_dashboard') !!}
@endsection
@section('body')
    <div class="system-modules-wrapper">
        <div class="system-modules uk-grid uk-grid-collapse">
            @foreach ($system_modules as $system_module)
                <div class="system-module uk-width-1-4@l uk-width-1-3@m uk-width-1-2@s">
                    <div class="system-module-inner">
                        <span class="system-module-icon">
                            <i class="fa fa-cog"></i>
                        </span>
                        <h3>{{ $system_module->name }}</h3>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection