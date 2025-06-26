@include('partials.admin.header')
<div class="container-fluid">

    <div class="dashboard-cards-row mb-4">
        <div class="dashboard-card-col">
            <div class="card gradient-1">
                <div class="card-body">
                    <h3 class="card-title text-white">Total Calls</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white">4565</h2>
                        <p class="text-white mb-0"></p>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-phone"></i></span>
                </div>
            </div>
        </div>
        <div class="dashboard-card-col">
            <div class="card gradient-2">
                <div class="card-body">
                    <h3 class="card-title text-white">Appointments Booked</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white">0</h2>
                        <p class="text-white mb-0"></p>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa-regular fa-calendar-check"></i></span>
                </div>
            </div>
        </div>
        <div class="dashboard-card-col">
            <div class="card gradient-3">
                <div class="card-body">
                    <h3 class="card-title text-white">Active Extensions</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white">4565</h2>
                        <p class="text-white mb-0"></p>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
                </div>
            </div>
        </div>
        <div class="dashboard-card-col">
            <div class="card gradient-4">
                <div class="card-body">
                    <h3 class="card-title text-white">Total SMS</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white">99</h2>
                        <p class="text-white mb-0"></p>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-envelope"></i></span>
                </div>
            </div>
        </div>
        <div class="dashboard-card-col">
            <div class="card gradient-5">
                <div class="card-body">
                    <h3 class="card-title text-white">Follow Up Items</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white">123</h2>
                        <p class="text-white mb-0"></p>
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa-regular fa-flag"></i></span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body pb-0 d-flex justify-content-between">
                            <div>
                                <h4 class="mb-1">Product Sales</h4>
                                <p>Total Earnings of the Month</p>
                                <h3 class="m-0">$ 12,555</h3>
                            </div>
                            <div>
                                <ul>
                                    <li class="d-inline-block mr-3"><a class="text-dark" href="#">Day</a></li>
                                    <li class="d-inline-block mr-3"><a class="text-dark" href="#">Week</a></li>
                                    <li class="d-inline-block"><a class="text-dark" href="#">Month</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="chart_widget_2"></canvas>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="w-100 mr-2">
                                    <h6>Pixel 2</h6>
                                    <div class="progress" style="height: 6px">
                                        <div class="progress-bar bg-danger" style="width: 40%"></div>
                                    </div>
                                </div>
                                <div class="ml-2 w-100">
                                    <h6>iPhone X</h6>
                                    <div class="progress" style="height: 6px">
                                        <div class="progress-bar bg-primary" style="width: 80%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('partials.admin.footer')