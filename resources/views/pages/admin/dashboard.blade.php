@include('partials.admin.header')
<div class="container-fluid">

    @include('components.dashboard.totals')
    
    @include('components.dashboard.sales')

    @include('components.dashboard.sentiments')
</div>
@include('partials.admin.footer')