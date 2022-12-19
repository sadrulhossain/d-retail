<!--MAIN SLIDE-->
<div class="wrap-main-slide">
    <div class="slide-carousel owl-carousel style-nav-1" data-items="1" data-loop="1" data-nav="true" data-dots="false">
        @if(!$bannerArr->isEmpty())
        @foreach($bannerArr as $banner)
        <div class="item-slide">
            <img src="{{URL::to('/')}}/public/uploads/content/banner/{{$banner->img_d_x}}" alt="" class="img-slide">
            <div class="slide-info {!!$banner->position??''!!}">
                <h2 class="f-title">{!!$banner->title??''!!}</h2>
                <span class="f-subtitle">{!!$banner->subtitle??''!!}</span>
                <p class="sale-info">{!!$banner->price_info??''!!}<b class="price">{!!$banner->price??''!!}</b></p>
                <a href="{!!$banner->url??''!!}" class="btn-link">Shop Now</a>
            </div>
        </div>
        @endforeach
        @endif

    </div>
</div>