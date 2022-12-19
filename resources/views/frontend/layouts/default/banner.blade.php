<!--BANNER-->
<div class="wrap-banner style-twin-default">
     @if(!$advertisementArr->isEmpty())
    @foreach($advertisementArr as $advertisement)
    <div class="banner-item">
        <a href="{!!$advertisement->url!!}" class="link-banner banner-effect-1">
            <figure><img src="{{URL::to('/')}}/public/uploads/content/advertisement/{{$advertisement->img_d_x}}" alt="" width="580" height="190"></figure>
        </a>
    </div>
    @endforeach
    @endif
   
</div>