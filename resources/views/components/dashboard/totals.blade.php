<div class="dashboard-cards-row mb-4">
    <div class="dashboard-card-col">
        <div class="card gradient-1">
            <div class="card-body">
                <h3 class="card-title text-white">Total Calls</h3>
                <div class="d-inline-block">
                    <h2 class="text-white">{{ $dashboard_data['total_communications'] }}</h2>
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
                    <h2 class="text-white">{{ $dashboard_data['total_appointments_booked'] }}</h2>
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
                    <h2 class="text-white">{{ $dashboard_data['total_extensions'] }}</h2>
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
                    <h2 class="text-white">{{ $dashboard_data['total_messages'] }}</h2>
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
                    <h2 class="text-white">{{ $dashboard_data['total_follow_ups'] }}</h2>
                    <p class="text-white mb-0"></p>
                </div>
                <span class="float-right display-5 opacity-5"><i class="fa-regular fa-flag"></i></span>
            </div>
        </div>
    </div>
</div>