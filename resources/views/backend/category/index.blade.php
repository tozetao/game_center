@extends('layouts.backend')

@section('title', '商品类目列表')

@section('content')
    <input type="hidden" id="dependent" value="category">

    <div class="layui-card">

        <div class="layui-card-body">
            <div style="text-align: right;">
                <a class="layui-btn" lay-href="{{ route('category.create') }}">新建类目</a>
            </div>

            <table class="layui-table">
                <thead>
                <tr>
                    <th width="50">类目ID</th>
                    <th width="100">类目名</th>
                    <th width="100">规格名</th>
                    <th width="350">规格属性值</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $category)
                    @foreach($category->specificationAttrs as $specificationAttr)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $specificationAttr->spec_name }}</td>
                        <td>{{ $specificationAttr->translateAttrValues() }}</td>
                        <td>
                            <a class="layui-btn layui-btn-normal layui-btn-xs"
                               lay-href="{{ route('category.edit', ['id' => $category->id]) }}">
                                <i class="layui-icon layui-icon-edit"></i>编辑
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                @endforeach
            </table>
            {{ $categories->links() }}
        </div>
    </div>
@endsection