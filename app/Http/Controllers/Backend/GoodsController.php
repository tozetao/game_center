<?php

namespace App\Http\Controllers\Backend;

use App\Filters\GoodsFilter;
use App\Models\Backend\Category;
use App\Http\Requests\GoodsRequest;
use App\Models\Backend\Goods;
use App\Models\Backend\GoodsImage;
use App\MOdels\Backend\GoodsSpecification;
use App\Services\Flash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class GoodsController extends Controller
{
    // 查询商品列表
    public function index(Request $request, GoodsFilter $filter)
    {
        $goods = Goods::filter($filter)->orderBy('id', 'desc')->paginate(10);

        $this->applyPaginateParams($goods);

        Session::flashInput($request->all());

        return view('goods.index', compact('goods'));
    }

    public function create()
    {
        // 查询商品分类
        $categories = Category::all();

        $imageList = $this->oldImageListHandle();

        return view('goods.create', compact('categories', 'imageList'));
    }


    // 旧的image_list字段的处理
    private function oldImageListHandle()
    {
        if (old('image_list')) {
            return json_decode(old('image_list'), true);
        }

        return null;
    }

    public function store(GoodsRequest $request)
    {
        $goods = new Goods();

        if (!$request->performCreate($goods)) {
            Flash::failed('操作失败!');
            return redirect()->back()->withInput($request->all());
        }

        Flash::success('操作成功!');
        return redirect()->route('goods.create');
    }

    public function edit($goodsId)
    {
        // 查询商品
        $goods = Goods::findOrFail($goodsId);

        // 查询商品分类
        $categories = Category::all();

        // 商品销售属性
        $specAttrs = $this->specAttrHandle($goodsId);

        // 商品图片列表
        $imageList = old('image_list') ? $this->oldImageListHandle()
            : $this->imageListHandle($goodsId, $goods->image);

        return view('goods.edit', compact('goods', 'specAttrs', 'imageList', 'categories'));
    }

    // 销售规格属性处理，用于前台显示。key是销售规格id，值是销售属性值
    private function specAttrHandle($goodsId)
    {
        // 查询销售属性
        $goodsSpecs = GoodsSpecification::allByGoodsId($goodsId);

        $specAttrs = [];

        foreach ($goodsSpecs as $spec) {
            $specAttrs[$spec->spec_id] = $spec->attr_val;
        }

        return json_encode($specAttrs);
    }

    // 返回前端显示的图片列表
    private function imageListHandle($goodsId, $image)
    {
        $goodsImages = GoodsImage::allByGoodsId($goodsId);

        $imageList[] = $image;

        foreach ($goodsImages as $goodsImage) {
            $imageList[] = $goodsImage->path;
        }

        return $imageList;
    }

    public function update(GoodsRequest $request, $goodsId)
    {
        $goods = Goods::findOrFail($goodsId);

        if (!$request->performUpdate($goods)) {
            Flash::failed('操作失败!');
            return redirect()->back()->withInput($request->all());
        }

        Flash::success('操作成功!');
        return redirect()->route('goods.edit', ['goods_id' => $goods->id]);
    }
}
