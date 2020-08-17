<?php

namespace MelisCmsCategory2\View\Helper;

use Laminas\ServiceManager\ServiceManager;
use Laminas\View\Helper\AbstractHelper;

class RenderRecTreeHelper extends AbstractHelper
{
    public $serviceManager;

    /**
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
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
                // check if the category is linked to a site
                echo "<li>";
                echo "<span>$category[cat2_id] - $categoryText</span>";
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