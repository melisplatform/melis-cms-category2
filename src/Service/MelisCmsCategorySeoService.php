<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCmsCategory2\Service;

use MelisCommerce\Model\MelisCategory;
use Laminas\Stdlib\ArrayUtils;
use MelisCore\Service\MelisGeneralService;

/**
 *
 * This service handles the category system of MelisCommerce.
 *
 */
class MelisCmsCategorySeoService  extends MelisGeneralService
{  
    /**
     * Deletes a Category Seo in the category_seo  table
     * @param int $categoryId
     */
    public function deleteCategorySeoData($categoryId) 
    {
        $table = $this->getServiceManager()->get('MelisCmsCategory2SeoTable'); 
        
        if($categoryId) { 
            $table->getTableGateway()->delete([
                'category2_id' => $categoryId, 
            ]);
        } 
    }

}