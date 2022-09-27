<div class="cp-user-card-header-area">
    <div class="cp-user-title">
        <h4>{{__('Membership Plan Details')}}</h4>
    </div>
</div>
@if(isset($plans[0]))
    <div class="row">
        @foreach($plans as $plan)
            <div class="col-md-12 col-lg-6 mt-4">
                <ul class="user-plan-table">
                    <li class="user-t-img">
                        <img src="{{show_plan_image($plan->id,$plan->image)}}"
                             class="img-fluid cp-user-logo-large" alt="">
                    </li>
                    <li>
                        <h4>{{__('Plan Name  ')}} <span>{{$plan->plan_name}}</span></h4>
                    </li>
                    <li>
                        <h4>{{__('Minimum Amount  ')}}
                            <span>{{number_format($plan->amount,2)}} {{settings('coin_name')}}</span>
                        </h4>
                    </li>
                    <li>
                        <h4>{{__('Minimum Duration  ')}}
                            <span>{{$plan->duration}} {{__(' days')}}</span></h4>
                    </li>
                    <li>
                        <h4>{{__('Bonus Percentage ')}} <span>{{plan_bonus_percentage($plan->bonus_type,$plan->bonus,$plan->amount) }} %</span>
                        </h4>
                    </li>
                </ul>
            </div>
        @endforeach
    </div>
@endif
