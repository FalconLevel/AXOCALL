@include('partials.admin.header')
<div class="container-fluid">

    @include('components.dashboard.totals')
    
    @include('components.dashboard.calls')
    
    <div class="row" >
        <div class="col-lg-6">
            @include('components.dashboard.sentiments')
        </div>
        <div class="col-lg-6">
            @include('components.dashboard.keywords')
        </div>
    </div>

</div>

@include('partials.admin.footer')

<script src="{{ asset('assets/axocall/js/modules/dashboard.js') }}"></script>