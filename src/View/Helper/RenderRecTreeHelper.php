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
    public function __invoke($categoryData)
    {
        return $this->constructTree($categoryData);
    }

    /**
     *
     * Use to construct tree data to tree ul
     *
     * @param $categoryData
     * @return null
     */
    private function constructTree($categoryData,$ctr = 0)
    {
        if (! empty($categoryData) && is_array($categoryData)) {
            foreach ($categoryData as $idx => $category) {
                $categoryText = $category['text'] ?? null;

                    $status = $category['cat2_status'];
                    $class = "text-danger";
                    if ($status) {
                        $class = "text-success";
                    }
                    $statusIndicator = "<i class='fa fa-circle $class'></i>";
                    // check if the category is linked to a site
                    $classLinkedToSite = null;
                    if (isset($category['linked_to_site_bool']) && $category['linked_to_site_bool']) {
                        $classLinkedToSite = 'text-green';
                        $ctr ++ ;
                        $categoryData['number_of_linked_to_site'] = $ctr;
                    }
                    echo "<li>";
                    echo "<span class='$classLinkedToSite'>$statusIndicator $category[cat2_id] - $categoryText</span>";


                if (! empty($category['children'])){
                    echo "<ul>";
                    echo $this->constructTree($category['children'],$ctr);
                    echo "</ul>";
                } else {
                    if (! empty($categoryText)) {
                        echo "</li>";
                    }
                }
            }
        }
        return null;
    }


}