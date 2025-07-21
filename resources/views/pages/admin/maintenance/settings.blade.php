@include('partials.admin.header')
<div class="container-fluid">
    
    @include('pages.admin.maintenance.settings.tags')   

    @include('pages.admin.maintenance.settings.extensions')

    @include('pages.admin.maintenance.settings.keywords')

    @include('pages.admin.maintenance.settings.email_summaries')

    @include('pages.admin.maintenance.settings.smart_callback')

    @include('pages.admin.maintenance.settings.system_numbers')

    @include('pages.admin.maintenance.settings.audio_prompts')
</div>
@include('partials.admin.footer')

<script src="{{ asset('assets/axocall/js/modules/settings.js') }}"></script>