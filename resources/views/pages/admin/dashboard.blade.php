@include('partials.admin.header')
<div class="container-fluid">

    @include('components.dashboard.totals')
    
    @include('components.dashboard.calls')

    @include('components.dashboard.sentiments')
</div>

@include('partials.admin.footer')

<script src="{{ asset('assets/axocall/js/modules/dashboard.js') }}"></script>