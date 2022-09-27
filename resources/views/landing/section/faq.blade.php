<div class="container">
    <div class="faq-wrap">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h2>{{__('Frequently Asked Question')}}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="faq-info choose-info">
                    <div class="accordion" id="accordionDorbar">
                        @if(isset($faqs))
                            @foreach($faqs as $faq)
                                <div class="card">
                                    <div class="card-header" id="heading{{$faq->id}}">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{$faq->id}}" aria-expanded="true" aria-controls="collapse{{$faq->id}}">
                                                {!! clean($faq->question) !!}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapse{{$faq->id}}" class="collapse" aria-labelledby="heading{{$faq->id}}" data-parent="#accordionDorbar">
                                        <div class="card-body">
                                            {!! clean($faq->answer) !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            What cryptocurrencies can I use to purchase?
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionDorbar">
                                    <div class="card-body">
                                        Lorem ipsum dolor sit amet, conubia eu tellus blandit at tincidunt fibus quam, urna bibendum lobortis platea, nec sed quis, vestibulum lortis adipisicing, nunc mattis.
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            How can I participate in the ICO?
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionDorbar">
                                    <div class="card-body">
                                        Lorem ipsum dolor sit amet, conubia eu tellus blandit at tincidunt fibus quam, urna bibendum lobortis platea, nec sed quis, vestibulum lortis adipisicing, nunc mattis.
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingThree">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            How to create LBT wallet?
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionDorbar">
                                    <div class="card-body">
                                        Lorem ipsum dolor sit amet, conubia eu tellus blandit at tincidunt fibus quam, urna bibendum lobortis platea, nec sed quis, vestibulum lortis adipisicing, nunc mattis.
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white_svg svg_white">
    <svg x="0px" y="0px" viewBox="0 0 1920 289" enable-background="new 0 0 1920 289" xml:space="preserve">
        <path fill="#0f4571" d="M959,169C582.541,169,240.253,104.804-14.125,0H0v289h1920V0h12.125C1677.747,104.804,1335.459,169,959,169
            z"></path>
       </svg>
</div>
