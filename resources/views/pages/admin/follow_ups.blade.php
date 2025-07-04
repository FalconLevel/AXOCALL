@include('partials.admin.header')       
<div class="container-fluid mt-3">
    <div class="tab-content br-n pn">
        <div id="active" class="tab-pane active">

            <div class="row">
                <div class="col-lg-12">
                    <ul class="nav nav-pills mb-3 justify-content-between">
                        <li class="nav-item w-50">
                            <a href="#active-call-logs" class="nav-link active text-center" data-toggle="tab" aria-expanded="false">
                                <i class="fa fa-phone"></i>
                                Call Logs
                            </a>   
                        </li>
                        <li class="nav-item w-50">
                            <a href="#active-sms-logs" class="nav-link text-center" data-toggle="tab" aria-expanded="false">
                                <i class="fa fa-sms"></i>
                                SMS Logs
                            </a>
                        </li>
                    </ul>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="tab-content br-n pn">
                                <div id="active-call-logs" class="tab-pane active">
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
                                                            $svg_class = $communication['type'] == 'inbound' ? 'text-primary' : 'text-success';
                                                        @endphp
                                                        <tr>
                                                            <td class="{{ $svg_class }} svg-icon">{!! config('twilio.svg.' . $communication['type']) !!} </td>
                                                            <td class="text-bold">
                                                                {{ 
                                                                    $communication['contact_from'] ? 
                                                                        $communication['contact_from']['contact']['first_name'] . ' ' . $communication['contact_from']['contact']['last_name'] : 
                                                                        $communication['from_formatted'] 
                                                                }}
                                                            </td>
                                                            <td>
                                                                {{ 
                                                                    $communication['contact_to'] ? 
                                                                        $communication['contact_to']['contact']['first_name'] . ' ' . $communication['contact_to']['contact']['last_name'] : 
                                                                        $communication['to_formatted'] 
                                                                }}
                                                            </td>
                                                            <td>{{ formatHelper()->formatDate($communication['date_time']) }}</td>
                                                            <td>{{ formatHelper()->formatDuration($communication['duration']) }}</td>
                                                            <td title="{{ $communication['summary'] }}">{{ Str::limit($communication['summary'], 20) }}</td>
                                                            <td>
                                                                <i class="{{ $communication['sentiment'] ? config('twilio.sentiment.' . $communication['sentiment']) : '' }}"></i>
                                                            </td>
                                                            <td class="text-danger">{{ $communication['keywords'] }}</td>
                                                            <td class="text-center">
                                                                {!! 
                                                                    $communication['is_booked'] ? '<i class="text-success fa-regular fa-check"></i>' : '<i class="text-danger fa-regular fa-xmark"></i>' 
                                                                !!}
                                                            </td>
                                                            <td>
                                                                <a 
                                                                    href="javascript:void(0)" 
                                                                    class="text-primary svg-icon btn-show-transcription"
                                                                    title="View Transcription"
                                                                    data-id="{{ $communication['id'] }}"
                                                                    data-transcription="{{ $communication['transcriptions'] }}"

                                                                >
                                                                    <i class="fa-regular fa-file-lines"></i>
                                                                </a>&nbsp;
                                                                
                                                                <a 
                                                                    href="javascript:void(0)" 
                                                                    class="text-info svg-icon"
                                                                    data-trigger="recording"
                                                                    data-recording-url="{{ $communication['recording_url_axocall'] ? asset($communication['recording_url_axocall']) : '' }}"
                                                                >
                                                                    <i class="fa-regular fa-circle-play"></i>
                                                                </a>&nbsp;
                                                                
                                                                <a 
                                                                    href="javascript:void(0)" 
                                                                    class="text-warning svg-icon btn-edit-notes"
                                                                    title="Edit Notes"
                                                                    data-id="{{ $communication['id'] }}"
                                                                    data-notes="{{ htmlspecialchars($communication['notes'] ?? '', ENT_QUOTES) }}"
                                                                >
                                                                    <i class="fa-regular fa-note-sticky"></i>
                                                                </a>

                                                            </td>
                                                            <td class="text-center">
                                                                <a 
                                                                    href="javascript:void(0)" class="text-primary svg-icon" data-trigger="un-follow-up" 
                                                                    data-id="{{ $communication['id'] }}" 
                                                                    data-type="communication"
                                                                    data-category="{{ $communication['category'] }}"
                                                                >
                                                                    <i class="fa-regular fa-flag"></i>
                                                                </a>
                                                                &nbsp;
                                                                <a 
                                                                    href="javascript:void(0)" class="text-success svg-icon" data-trigger="archive" 
                                                                    data-id="{{ $communication['id'] }}" 
                                                                    data-type="communication"
                                                                    data-category="{{ $communication['category'] }}"
                                                                >
                                                                    <i class="fa-regular fa-archive"></i>
                                                                </a>
                                                            </td>
                                                            
                                                        </tr>
                                                    @endforeach
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div id="active-sms-logs" class="tab-pane">
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
                                                        <td>{{ formatHelper()->formatDate($message->date_sent) }}</td>
                                                        <td class="text-bold">
                                                            {{ 
                                                                $message['contact_from'] ? 
                                                                    $message['contact_from']['contact']['first_name'] . ' ' . $message['contact_from']['contact']['last_name'] : 
                                                                    $message['from_number'] 
                                                            }}
                                                        </td>
                                                        <td class="text-bold">
                                                            {{ 
                                                                $message['contact_to'] ? 
                                                                    $message['contact_to']['contact']['first_name'] . ' ' . $message['contact_to']['contact']['last_name'] : 
                                                                    $message['to_number'] 
                                                            }}
                                                        </td>
                                                        <td>{{ Str::limit($message->message_body, 20) }}</td>
                                                        <td>{{ ucfirst($message->type) }}</td>
                                                        <td>{{ ucfirst($message->status) }}</td>
                                                        <td>
                                                            <a href="javascript:void(0)" 
                                                            class="text-warning svg-icon view-message"
                                                            data-message="{{ $message->message_body }}">
                                                                <i class="fa-regular fa-note-sticky"></i>
                                                            </a>&nbsp;

                                                            <a 
                                                                href="javascript:void(0)" class="text-primary svg-icon" data-trigger="un-follow-up" 
                                                                data-id="{{ $message->id }}" 
                                                                data-type="message"
                                                                data-category="{{ $message->category }}"
                                                            >
                                                                <i class="fa-regular fa-flag"></i>
                                                            </a>
                                                            &nbsp;
                                                            <a 
                                                                href="javascript:void(0)" class="text-success svg-icon" data-trigger="archive" 
                                                                data-id="{{ $message->id }}" 
                                                                data-type="message"
                                                                data-category="{{ $message->category }}"
                                                            >
                                                                <i class="fa-regular fa-archive"></i>
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
        <div id="archived" class="tab-pane">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="nav nav-pills mb-3 justify-content-between">
                        <li class="nav-item w-50">
                            <a href="#archived-call-logs" class="nav-link active text-center" data-toggle="tab" aria-expanded="false">
                                <i class="fa fa-phone"></i>
                                Call Logs
                            </a>   
                        </li>
                        <li class="nav-item w-50">
                            <a href="#archived-sms-logs" class="nav-link text-center" data-toggle="tab" aria-expanded="false">
                                <i class="fa fa-sms"></i>
                                SMS Logs
                            </a>
                        </li>
                    </ul>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="tab-content br-n pn">
                                <div id="archived-call-logs" class="tab-pane active">
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
                                                            $svg_class = $communication['type'] == 'inbound' ? 'text-primary' : 'text-success';
                                                        @endphp
                                                        <tr>
                                                            <td class="{{ $svg_class }} svg-icon">{!! config('twilio.svg.' . $communication['type']) !!} </td>
                                                            <td class="text-bold">
                                                                {{ 
                                                                    $communication['contact_from'] ? 
                                                                        $communication['contact_from']['contact']['first_name'] . ' ' . $communication['contact_from']['contact']['last_name'] : 
                                                                        $communication['from_formatted'] 
                                                                }}
                                                            </td>
                                                            <td>
                                                                {{ 
                                                                    $communication['contact_to'] ? 
                                                                        $communication['contact_to']['contact']['first_name'] . ' ' . $communication['contact_to']['contact']['last_name'] : 
                                                                        $communication['to_formatted'] 
                                                                }}
                                                            </td>
                                                            <td>{{ formatHelper()->formatDate($communication['date_time']) }}</td>
                                                            <td>{{ formatHelper()->formatDuration($communication['duration']) }}</td>
                                                            <td title="{{ $communication['summary'] }}">{{ Str::limit($communication['summary'], 20) }}</td>
                                                            <td>
                                                                <i class="{{ $communication['sentiment'] ? config('twilio.sentiment.' . $communication['sentiment']) : '' }}"></i>
                                                            </td>
                                                            <td class="text-danger">{{ $communication['keywords'] }}</td>
                                                            <td class="text-center">
                                                                {!! 
                                                                    $communication['is_booked'] ? '<i class="text-success fa-regular fa-check"></i>' : '<i class="text-danger fa-regular fa-xmark"></i>' 
                                                                !!}
                                                            </td>
                                                            <td>
                                                                <a 
                                                                    href="javascript:void(0)" 
                                                                    class="text-primary svg-icon btn-show-transcription"
                                                                    title="View Transcription"
                                                                    data-id="{{ $communication['id'] }}"
                                                                    data-transcription="{{ $communication['transcriptions'] }}"

                                                                >
                                                                    <i class="fa-regular fa-file-lines"></i>
                                                                </a>&nbsp;
                                                                
                                                                <a 
                                                                    href="javascript:void(0)" 
                                                                    class="text-info svg-icon"
                                                                    data-trigger="recording"
                                                                    data-recording-url="{{ $communication['recording_url_axocall'] ? asset($communication['recording_url_axocall']) : '' }}"
                                                                >
                                                                    <i class="fa-regular fa-circle-play"></i>
                                                                </a>&nbsp;
                                                                
                                                                <a 
                                                                    href="javascript:void(0)" 
                                                                    class="text-warning svg-icon btn-edit-notes"
                                                                    title="Edit Notes"
                                                                    data-id="{{ $communication['id'] }}"
                                                                    data-notes="{{ htmlspecialchars($communication['notes'] ?? '', ENT_QUOTES) }}"
                                                                >
                                                                    <i class="fa-regular fa-note-sticky"></i>
                                                                </a>

                                                            </td>
                                                            <td class="text-center">
                                                                <a 
                                                                    href="javascript:void(0)" class="text-success svg-icon" data-trigger="un-archive" 
                                                                    data-id="{{ $communication['id'] }}" 
                                                                    data-type="communication"
                                                                    data-category="{{ $communication['category'] }}"
                                                                >
                                                                    <i class="fa-regular fa-undo"></i>
                                                                </a>
                                                            </td>
                                                            
                                                        </tr>
                                                    @endforeach
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div id="archived-sms-logs" class="tab-pane">
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
                                                        <td>{{ formatHelper()->formatDate($message->date_sent) }}</td>
                                                        <td class="text-bold">
                                                            {{ 
                                                                $message['contact_from'] ? 
                                                                    $message['contact_from']['contact']['first_name'] . ' ' . $message['contact_from']['contact']['last_name'] : 
                                                                    $message['from_number'] 
                                                            }}
                                                        </td>
                                                        <td class="text-bold">
                                                            {{ 
                                                                $message['contact_to'] ? 
                                                                    $message['contact_to']['contact']['first_name'] . ' ' . $message['contact_to']['contact']['last_name'] : 
                                                                    $message['to_number'] 
                                                            }}
                                                        </td>
                                                        <td>{{ Str::limit($message->message_body, 20) }}</td>
                                                        <td>{{ ucfirst($message->type) }}</td>
                                                        <td>{{ ucfirst($message->status) }}</td>
                                                        <td>
                                                            <a 
                                                                href="javascript:void(0)" class="text-success svg-icon" data-trigger="un-archive" 
                                                                data-id="{{ $message['id'] }}" 
                                                                data-type="message"
                                                                data-category="{{ $message['category'] }}"
                                                            >
                                                                <i class="fa-regular fa-undo"></i>
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
    </div>
</div>
</div>

<x-audio-modal />
<x-message-modal />
<x-transcriptions-modal />
<x-communication-notes-modal />

@include('partials.admin.footer')

<script src="{{ asset('assets/axocall/js/modules/follow-ups.js') }}"></script>