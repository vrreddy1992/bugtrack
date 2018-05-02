@foreach (session('flash_notification', collect())->toArray() as $message)
    @if ($message['overlay'])
        @include('flash::modal', [
            'modalClass' => 'flash-modal',
            'title'      => $message['title'],
            'body'       => $message['message']
        ])
    @else
        <div ng-show="showFlash" class="alert
                    alert-{{ $message['level'] }}
                    {{ $message['important'] ? 'alert-important' : '' }} slide-down"
                    role="alert" ng-init="showFlash = true"
        >
            <span id="flash_msg">{!! $message['message'] !!}</span>
            @if ($message['important'])
                <a type="button" onClick="hideFlashMessage();"><i class="mdi mdi-close"></i></a>
            @endif


        </div>
    @endif
@endforeach

{{ session()->forget('flash_notification') }}
