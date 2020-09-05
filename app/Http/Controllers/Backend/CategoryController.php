<?php

namespace App\Http\Controllers\Backend;

use App\Models\Backend\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Backend\SpecificationAttr;
use App\Services\Flash;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('specificationAttrs')->paginate(10);
        return view('category.index', compact('categories'));
    }

    public function create()
    {
        return view('category.create');
    }

    public function store(CategoryRequest $request)
    {
        $category = new Category();

        if (!$request->performCreate($category)) {
            Flash::failed('操作失败!');
            return redirect()->back()->withInput($request->all());
        }

        Flash::success('操作成功!');
        return redirect()->route('category.create');
    }

    public function edit($id)
    {
        // 查询类目信息
        $category = Category::findOrFail($id);

        // 查询对应类目的销售属性信息
        $specAttrs = SpecificationAttr::allByCategory($category->id);

        return view('category.edit', compact('category', 'specAttrs'));
    }

    public function update(CategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);

        if (!$request->performUpdate($category)) {
            Flash::failed('操作失败!');
            return redirect()->back()->withInput($request->all());
        }

        Flash::success('操作成功!');
        return redirect()->route('category.edit', ['id' => $category->id]);
    }
}
