<div class="btn-group dropdown">
    <button type="button" class="btn notification-btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="notify-value hm-notify-number">@if(isset($notifications) && ($notifications ->count() > 0)) {{ $notifications->count() }} @else 0 @endif</span>
        <img src="{{ asset('assets/img/icons/notification.png') }}" class="img-fluid" alt="">
    </button>
    @if(!empty($notifications))
        <div class="dropdown-menu notification-list dropdown-menu-right">
            <div class="text-center p-2 border-bottom nt-title">{{__('New Notifications')}}</div>
            <ul class="scrollbar-inner">
                @foreach($notifications as $item)
                    <li>
                        <a href="javascript:void(0);" data-toggle="modal" data-id="{{$item->id}}" data-target="#notificationShow" class="dropdown-item viewNotice">
                            <span class="small d-block">{{ date('d M y', strtotime($item->created_at)) }}</span>
                            {{ $item->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
