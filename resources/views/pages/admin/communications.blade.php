@include('partials.admin.header')
<div class="container-fluid mt-3">

    <ul class="nav nav-pills mb-3 justify-content-between">
        <li class="nav-item w-50">
            <a href="#call-logs" class="nav-link active text-center" data-toggle="tab" aria-expanded="false">
                <i class="fa fa-phone"></i>
                Call Logs
            </a>   
        </li>
        <li class="nav-item w-50">
            <a href="#sms-logs" class="nav-link text-center" data-toggle="tab" aria-expanded="false">
                <i class="fa fa-sms"></i>
                SMS Logs
            </a>
        </li>
    </ul>
    <div class="tab-content br-n pn">
        <div id="call-logs" class="tab-pane active">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover zero-configuration verticle-middle" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Type</th>
                                            <th scope="col">From</th>
                                            <th scope="col">To</th>
                                            <th scope="col">Date & Time</th>
                                            <th scope="col">Duration</th>
                                            <th scope="col">Summary</th>
                                            <th scope="col">Sentiment</th>
                                            <th scope="col">Keywords Hit</th>
                                            <th scope="col">Booked</th>
                                            <th scope="col">Action</th>
                                            <th scope="col">Follow Up</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($communications as $communication)
                                            @php
                                                $svg_class = $communication->type == 'inbound' ? 'text-primary' : 'text-success';
                                            @endphp
                                            <tr>
                                                <td class="{{ $svg_class }} svg-icon">{!! config('twilio.svg.' . $communication->type) !!} </td>
                                                <td>{{ $communication->from_formatted }}</td>
                                                <td>{{ $communication->to_formatted }}</td>
                                                <td>{{ date('m/d/y h:i A', strtotime($communication->date_time)) }}</td>
                                                <td>{{ formatHelper()->formatDuration($communication->duration) }}</td>
                                                <td>{{ $communication->summary }}</td>
                                                <td>
                                                    <i class="{{ $communication->sentiment ? config('twilio.sentiment.' . $communication->sentiment) : '' }}"></i>
                                                </td>
                                                <td>{{ $communication->keywords }}</td>
                                                <td>
                                                    {!! 
                                                        $communication->is_booked ? '<i class="text-success fa-regular fa-check"></i>' : '<i class="text-danger fa-regular fa-xmark"></i>' 
                                                    !!}
                                                </td>
                                                <td>
                                                    <a 
                                                        href="javascript:void(0)" 
                                                        class="text-info svg-icon"
                                                        data-trigger="recording"
                                                        data-recording-url="{{ $communication->recording_url_axocall ? asset($communication->recording_url_axocall) : '' }}"
                                                    >
                                                        <i class="fa-regular fa-circle-play"></i>
                                                    </a>&nbsp;
                                                    
                                                    
                                                </td>
                                                <td>
                                                    @if($communication->is_archived == 'no' || $communication->is_archived == null)
                                                        <a href="javascript:void(0)" class="text-secondary svg-icon" data-trigger="archive" data-id="{{ $communication->id }}" data-type="communication">
                                                            <i class="fa-regular fa-flag"></i>
                                                        </a>
                                                    @else
                                                        <a href="javascript:void(0)" class="text-success svg-icon">
                                                            <i class="fa-regular fa-check-circle"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                                
                                            </tr>
                                        @endforeach
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div id="sms-logs" class="tab-pane">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover zero-configuration verticle-middle" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Date & Time</th>
                                            <th scope="col">From</th>
                                            <th scope="col">To</th>
                                            <th scope="col">Message</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Actions</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($messages as $message)
                                            <tr>
                                                <td>{{ $message->date_sent }}</td>
                                                <td>{{ $message->from_number }}</td>
                                                <td>{{ $message->to_number }}</td>
                                                <td>{{ Str::limit($message->message_body, 50) }}</td>
                                                <td>{{ ucfirst($message->type) }}</td>
                                                <td>{{ ucfirst($message->status) }}</td>
                                                <td>
                                                    <a href="javascript:void(0)" 
                                                       class="text-warning svg-icon view-message"
                                                       data-message="{{ $message->message_body }}">
                                                        <i class="fa-regular fa-note-sticky"></i>
                                                    </a>&nbsp;
                                                    <a href="javascript:void(0)" class="text-secondary svg-icon" data-trigger="archive" data-id="{{ $message->id }}" data-type="message">
                                                        <i class="fa-regular fa-flag"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
<x-audio-modal />
<x-message-modal />

@include('partials.admin.footer')

<script src="{{ asset('assets/axocall/js/modules/communications.js') }}"></script>