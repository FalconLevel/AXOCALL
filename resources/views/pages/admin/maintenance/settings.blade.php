@include('partials.admin.header')
<div class="container-fluid">
    
    @include('pages.admin.maintenance.settings.tags')   

    @include('pages.admin.maintenance.settings.extenstions')

    @include('pages.admin.maintenance.settings.keywords')

    @include('pages.admin.maintenance.settings.email_summaries')
</div>
@include('partials.admin.footer')

<script src="{{ asset('assets/axocall/js/modules/settings.js') }}"></script>