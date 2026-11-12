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
                // `text` comes from MelisCmsCategory2Service::getCategoryTreeview(), which already
                // HTML-escapes it: escaping it again showed "l&#039;été" on the front (Mantis #11491).
                // Decode first, then escape exactly once — safe whatever the input.
                $plain = html_entity_decode((string) $categoryText, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                echo '<span>' . (int) $category['cat2_id'] . ' - ' . htmlspecialchars($plain, ENT_QUOTES, 'UTF-8') . '</span>';
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