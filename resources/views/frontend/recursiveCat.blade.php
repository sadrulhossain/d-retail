@if(!is_array($leftCategory))
<li class="category-item "  style="{{($catId==$id)?'color:red;':''}}">
    <a href="{!! route('category.products.show', $catId) !!}" class="cate-link">{!!$categoryList[$catId]??''!!}</a>
</li>
@else
<li class="category-item has-child-cate {{(($catId==$id) || (!empty($parentIdArr[$catId])))?'open':''}}">
    <a href="{!! route('category.products.show', $catId) !!}" class="cate-link" style="{{($catId==$id)?'color:red;':''}}">{!!$categoryList[$catId]??''!!}</a>
    <span class="toggle-control">+</span>
    <ul class="sub-cate">
        @foreach($leftCategory as $cId=>$left)
        @include('frontend.recursiveCat', [
        'catId' => $cId,
        'id' => $id,
        'leftCategory' => $left,
        'categoryList' => $categoryList,
       'parentIdArr' => $parentIdArr,
        ])
        @endforeach
    </ul>
</li>
@endif