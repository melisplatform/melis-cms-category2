<?php

namespace MelisCmsCategory2\View\Helper;

use Zend\View\Helper\AbstractHelper;

class RenderRecTreeHelper extends AbstractHelper
{
    public $serviceManager;
    public $dataBreadcrumbs;

    public function __construct($sm)
    {
        $this->serviceManager = $sm;
        $this->dataBreadcrumbs = array();
    }

    /**
     * @param $categoryTree
     * @param bool $isChild
     * @param int $padding
     * @return mixed
     */
    public function __invoke($categoryTree)
    {
        return $this->constructTree($categoryTree);
    }

    /**
     *
     * Use to construct tree data to tree ul
     *
     * @param $categoryTree
     * @param bool $isChild
     * @param int $padding
     * @return mixed
     */
    private function constructTree($categoryTree, $isChild = false, $padding = 5)
    {
        if ($isChild) {
            echo "<ul class=' tree child'>";
        } else {
            echo "<ul class=' tree parent'>";
        }
        foreach ($categoryTree as $key => $val) {
            $status = ($val['cat2_status']) ? "<i class='fa fa-circle text-success'></i>" : "<i class='fa fa-circle text-success'></i>" ;
            if (isset($val['children'])) {
                echo '<li class="sub-children"><i class="plus-sign"></i><span data-parent-id=' . $val['cat2_father_cat_id'] . ' data-cat-id=' . $val['cat2_id'] . '>' . $val['cat2_id'] . ' - ' . $val['text'] . '</span>';
                $padding = $padding  + 5;
                $this->constructTree($val['children'],true,$padding);
                echo '</li>';
            } else {
                echo "<li class='no-sub-children'><span data-parent-id=$val[cat2_father_cat_id] data-cat-id=$val[cat2_id]>$val[cat2_id] - $val[text]</span></li>";
            }
        }
        echo "</ul>";
        return $categoryTree;

    }


}