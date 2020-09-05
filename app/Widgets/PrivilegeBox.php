<?php
/**
 * User: zetao
 * Date: 2019/7/31
 * Time: 15:38
 */

namespace App\Widgets;

class PrivilegeBox
{
    const MARGIN = 'style="margin: 10px 20px;"';

    const INPUT_NAME = 'privileges[]';

    /**
     * @var 要勾选的权限
     */
    private $checkedPrivileges;

    public function generate($privileges, $checkedPrivileges = null)
    {
        $privilegeTree = config('privilegeboxes');

        $this->checkedPrivileges = $checkedPrivileges;

        $this->filter($privilegeTree, $privileges);

        return $this->build($privilegeTree);
    }

    public function filter(&$privilegeTree, &$privileges)
    {
        foreach ($privilegeTree as $key => $item) {

            if (isset($privilegeTree[$key]['children'])) {

                $this->filter($privilegeTree[$key]['children'], $privileges);

                if (count($privilegeTree[$key]['children']) == 0) {
                    unset($privilegeTree[$key]);
                }

            } else {
                foreach ($privilegeTree[$key]['privilege_list'] as $i => $val) {
                    if (!in_array($val['privilege'], $privileges)) {
                        unset($privilegeTree[$key]['privilege_list'][$i]);
                    }
                }

                // 如果privilege_list为空，则unset该项
                if (count($privilegeTree[$key]['privilege_list']) == 0) {
                    unset($privilegeTree[$key]);
                }
            }
        }
    }

    public function build(&$privilegeTree, $level = 1)
    {
        $treeText = '';

        // 遍历到子节点
        foreach ($privilegeTree as $item) {

            $margin =  $level == 1 ? '' : self::MARGIN;

            if (isset($item['children'])) {

                $subtext = $this->build($item['children'], $level + 1);

                $treeText .= $this->fieldsetNode('', $item['name'], $margin, $subtext);

            } else {
                $leafNode = $this->leafNode($item['privilege_list'], self::INPUT_NAME);
                $treeText .= $this->fieldsetNode('', $item['name'], $margin, $leafNode);
            }
        }

        return $treeText;
    }

    private function leafNode($nodes, $name)
    {
        $template = '<div style="margin: 10px 30px;">%s</div>';

        $input = '<input type="checkbox" name="%s" title="%s" value="%s" lay-skin="primary" %s>';

        $text = '';

        foreach ($nodes as $node) {
            if ($this->checkedPrivileges != null
                && in_array($node['privilege'], $this->checkedPrivileges)) {
                $text .= sprintf($input, $name, $node['name'], $node['privilege'], 'checked=true');
            } else {
                $text .= sprintf($input, $name, $node['name'], $node['privilege'], '');
            }
        }

        return sprintf($template, $text);
    }

    private function fieldsetNode($name, $title, $style, $subtext)
    {
        $template = <<<EOF
<fieldset class="adm-legend"%s>
    <legend class="adm-legend-title">
        <input type="checkbox" name="%s" title="%s" lay-skin="primary">
    </legend>
    %s
</fieldset>
EOF;
        return sprintf($template, $style, $name, $title, $subtext);
    }
}