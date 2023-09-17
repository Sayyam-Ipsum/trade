@extends('site.layout.index')

@section('page-title')
    Home
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img src="{{asset('assets/site/img/platform@3x.png')}}" width="100%" height="">
            </div>
            <div class="col-md-6">
                <div class="heroarea-right">
                    <h1>Innovative platform for <br> small investments</h1>
{{--                    <p class="m-0 my-4">Register and get $ 10,000 on a demo <br> account for learning to trade</p>--}}
                    <a href="{{url('login')}}" class="btn btn-lg btn-success btn-register px-5 py-3">Log in</a>
                    <a href="{{url('register')}}" class="btn btn-lg btn-outline-success btn-register px-5 py-3">Registration</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container p-0 py-4 rounded-3 mt-2" style="background: rgba(110,110,110,0.3);" id="about">
        <div class="row">
            <div class="col-md-3 px-5 py-3">
                <img src="{{asset('assets/site/img/dignity-1.png')}}">
                <h5 class="m-0 text-white my-3" style="font-family: bold;">Convenient trading interface</h5>
                <p class="m-0" style="color: gray;">We created the most simple and comfortable interface that does not distract from the main thing - from trading.</p>
            </div>
            <div class="col-md-3 px-5 py-3">
                <img src="{{asset('assets/site/img/dignity-2.png')}}">
                <h5 class="m-0 text-white my-3" style="font-family: bold;">Integrated signals</h5>
                <p class="m-0" style="color: gray;">Approach the strategy thoughtfully - the most precise and innovative signals with an accuracy of 87% will help you create your own effective strategy.</p>
            </div>
            <div class="col-md-3 px-5 py-3">
                <img src="{{asset('assets/site/img/dignity-3.png')}}">
                <h5 class="m-0 text-white my-3" style="font-family: bold;">Trading indicators</h5>
                <p class="m-0" style="color: gray;">We have gathered the most useful trading indicators. Use them to boost your account balance.</p>
            </div>
            <div class="col-md-3 px-5 py-3">
                <img src="{{asset('assets/site/img/dignity-4.png')}}">
                <h5 class="m-0 text-white my-3" style="font-family: bold;">Perfect speed</h5>
                <p class="m-0" style="color: gray;">Our platform runs on the most modern technology and delivers incredible speed.</p>
            </div>
        </div>
{{--        <hr style="background: gray; margin: 0;">--}}
{{--        <div class="text-center mt-4">--}}
{{--            <a class="btn btn-lg btn-success p-4" style="font-family: regular; font-size: 14px;">--}}
{{--                Try playing on a demo account<i class="fas fa-arrow-circle-right ms-2"></i>--}}
{{--            </a>--}}
{{--        </div>--}}
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img src="{{asset('assets/site/img/appendix@3x.png')}}" width="100%" height="">
            </div>
            <div class="col-md-6">
                <div class="heroarea-right">
                    <h1>Mobile application is <br> always ready to hand</h1>
                    <p class="m-0 my-4">Download our user-friendly application for <br>iPhone or Android and start trading!</p>
                    <a href="javascript:void(0);" class="btn btn-lg btn-mobile" title="Coming Soon">
                        <div>
                            <i class="fab fa-google-play" style="font-size: 1.9rem;"></i>
                        </div>
                        <div class="p-2">
                            <p style="color: dimgray; margin: 0; font-size: 14px; text-align: left; font-family: med;">Available on</p>
                            <p class="m-0 mt-1" style="color: lightgrey !important; font-family: bold;">Google Play</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="trading-section">
            <h1 class="text-white">Start trading</h1>
            <h3 style="color: dimgray;">3 steps</h3>
            <div class="row mb-4">
                <div class="col-md-4 text-center my-4">
                    <img src="{{asset('assets/site/img/start-trading-1@3x.png')}}" width="120px" height="120px">
                    <h4 class="mt-4" style="font-family: bold; color: lightgrey;">Sign up</h4>
                    <p style="color: gray;">Open an account for free in just a <br> few minutes</p>
{{--                    <a class="btn mt-4" style="background-color: rgba(110,110,110,0.3); color: #0a58ca; padding: 8px 26px;">Trade on demo <br> account in 1 click</a>--}}
                </div>
                <div class="col-md-4 text-center my-4">
                    <img src="{{asset('assets/site/img/start-trading-3@3x.png')}}" width="120px" height="120px">
                    <h4 class="mt-4" style="font-family: bold; color: lightgrey;">Deposit and trade</h4>
                    <p style="color: gray;">Over 410 instruments and a minimum <br>  deposit of $5 for optimal trading</p>
{{--                    <a class="btn mt-4" style="background-color: rgba(110,110,110,0.3); color: #0a58ca; padding: 8px 26px;">Go to Deposit option</a>--}}
                </div>
                <div class="col-md-4 text-center my-4">
                    <img src="{{asset('assets/site/img/start-trading-2@3x.png')}}" width="120px" height="120px">
                    <h4 class="mt-4" style="font-family: bold; color: lightgrey;">Earn</h4>
                    <p style="color: gray;">Earn smart profits im minimum time</p>
                    {{--                    <a class="btn mt-4" style="background-color: rgba(110,110,110,0.3); color: #0a58ca; padding: 8px 26px;">Start training with demo <br> account</a>--}}
                </div>
            </div>
        </div>
    </div>

    <div class="container py-3" id="faq">
        <div class="trading-section">
            <h1 class="text-white">Frequently asked questions</h1>
        </div>
        <div class="py-3">
            <div class="accordion" id="accordionPanelsStayOpenExample">
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="false"
                                        aria-controls="panelsStayOpen-collapseOne">
                                    <h5 style="font-family: bold;">How to earn?</h5>
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingOne">
                                <div class="accordion-body">
                                    Sign up and start earning. It is exactly the same as real trading, but for free.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                                    <h5 style="font-family: bold;">Can I trade with the phone?</h5>
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
                                <div class="accordion-body">
                                    Our platform runs on the most modern technology and opens in the browser of any computer or mobile phone.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                                    <h5 style="font-family: bold;">How long does it take to withdraw funds?</h5>
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
                                <div class="accordion-body">
                                    On average, the withdrawal procedure takes from one to five days from the date of receipt of the corresponding request of the Client and depends only on the volume of simultaneously processed requests. The company always tries to make payments directly on the day the request is received from the Client.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false" aria-controls="panelsStayOpen-collapseFour">
                                    <h5 style="font-family: bold;">What is the minimum deposit amount?</h5>
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingFour">
                                <div class="accordion-body">
                                    The advantage of the Company’s trading platform is that you don’t have to deposit large amounts to your account. You can start trading by investing a small amount of money. The minimum deposit is 10 US dollars.
                                </div>
                            </div>
                        </div>
                    </div>
{{--                    <div class="col-md-6 mt-3">--}}
{{--                        <div class="accordion-item">--}}
{{--                            <h2 class="accordion-header" id="panelsStayOpen-headingFive">--}}
{{--                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="false" aria-controls="panelsStayOpen-collapseFive">--}}
{{--                                    <h5 style="font-family: bold;">What is a trading platform and why it is needed?</h5>--}}
{{--                                </button>--}}
{{--                            </h2>--}}
{{--                            <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingFive">--}}
{{--                                <div class="accordion-body">--}}
{{--                                    Trading platform - a software complex that allows the Client to conduct trades (operations) using different financial instruments. It has also accesses to various information such as the value of quotations, real-time market positions, actions of the Company, etc.--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6 mt-3">--}}
{{--                        <div class="accordion-item">--}}
{{--                            <h2 class="accordion-header" id="panelsStayOpen-headingSix">--}}
{{--                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseSix" aria-expanded="false" aria-controls="panelsStayOpen-collapseSix">--}}
{{--                                    <h5 style="font-family: bold;">Is there any fee for depositing and withdrawing funds from the account?</h5>--}}
{{--                                </button>--}}
{{--                            </h2>--}}
{{--                            <div id="panelsStayOpen-collapseSix" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingSix">--}}
{{--                                <div class="accordion-body">--}}
{{--                                    No. The company does not charge any fee for either the deposit or for the withdrawal operations.<br>However, it is worth considering that payment systems can charge their fee and use the internal currency conversion rate.--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>
@stop
