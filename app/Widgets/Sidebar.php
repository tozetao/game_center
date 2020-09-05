<?php

namespace App\Widgets;

/**
 * 侧边栏菜单的生成部件
 */
class Sidebar
{
    // 生成
    public function generate($privileges)
    {
        if (empty($privileges)) {
            return '';
        }

        $menus = config('sidebar');

        $this->filter($menus, $privileges);
        return $this->build($menus);
    }

    /**
     * 对比用户的权限，对菜单树进行过滤。
     * @param $menus
     * @param $privileges
     */
    private function filter(&$menus, &$privileges)
    {
        foreach ($menus as $key => $menu) {
            if (isset($menu['children'])) {
                // foreatch是键值对的拷贝，因此这里传的是$menus[$key]['children']
                $this->filter($menus[$key]['children'], $privileges);

                if (count($menus[$key]['children']) == 0) {
                    unset($menus[$key]);
                }
            } else {
                $val = isset($menu['privilege']) ? $menu['privilege'] : '';

                if (!in_array($val, $privileges)) {
                    unset($menus[$key]);
                }
            }
        }
    }

    // 构建，最多生成三级子节点
    private function build(&$menus, $level = 1)
    {
        $sidebarText = '';

        foreach ($menus as $key => $menu) {
            if (isset($menu['children'])) {

                $subtext = $this->build($menu['children'], $level + 1);

                if ($level == 1) {
                    $sidebarText .= $this->level1Nodes($menu['name'], $menu['icon'], '', $subtext);
                } else if ($level == 2) {
                    $sidebarText .= $this->level2Nodes($menu['name'], '', $subtext);
                }

            } else {

                if ($level == 1) {
                    $sidebarText .= $this->level1Nodes($menu['name'], $menu['icon'], $menu['route'], '');
                } else if ($level == 2) {
                    $sidebarText .= $this->level2Nodes($menu['name'], $menu['route'], '');
                } else {
                    $sidebarText .= $this->level3Nodes($menu['name'], $menu['route']);
                }
            }
        }

        return $sidebarText;
    }

    //
    private function level1Nodes($name, $icon, $route, $subtext)
    {
$template = <<<EOF
    <li class="layui-nav-item">
        <a %s lay-tips="%s" lay-direction="2">
            <i class="layui-icon %s"></i>
            <cite>%s</cite>
        </a>
        %s
    </li>
EOF;
        $route = $this->buildHref($route);

        if ($subtext != '') {
            $subtext = '<dl class="layui-nav-child">' . $subtext . '</dl>';
        }

        return sprintf($template, $route, $name, $icon, $name, $subtext);
    }

    private function level2Nodes($name, $route, $subtext)
    {
        $template = '<dd><a %s>%s</a>%s</dd>';

        $route = $this->buildHref($route);

        // 有子节点时需要用标签包裹起来
        if ($subtext != '') {
            $subtext = '<dl class="layui-nav-child">' . $subtext . '</dl>';
        }

        return sprintf($template, $route, $name, $subtext);
    }

    private function level3Nodes($name, $route)
    {
        $template = '<dd><a %s>%s</a></dd>';

        $route = $this->buildHref($route);

        return sprintf($template, $route, $name);
    }

    private function buildHref($route)
    {
        if ($route != '') {
            $route = 'lay-href="' . route($route) . '"';
        } else {
            $route = 'href="javascript:;"';
        }
        return $route;
    }
}