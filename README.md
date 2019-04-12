# melis-cms-category2

//
## Getting started

These instructions will get you a copy of the project up and running on your machine.

### Prerequisites

The following modules need to be installed to have Melis newsletter module run:
* Melis core
* Melis engine
* Melis CMS

### Installing

Run the composer command:
```
composer require melisplatform/melis-cms-category2
```

### Database    

Database model is accessible via the MySQL Workbench file:  
```
/melis-cms-category2/install/sql/Model
```  
Database will be installed through composer and its hooks.  
In case of problems, SQL files are located here:  
```
/melis-cms-category2/install/sql 
```

## Tools and elements provided

* Category tool
* Category Service
* Category display plugin
 
### Categries tool
Provides the user with the basic actions in managing categories such as:
* Creation
    - user can create a category
* Edition
    - user can manage the category titles, contents, dates of validity, or attach post-specific media(images, files).
* Deletion
    - user can delete a category

### MelisCmsCategory2 service  

```
File: /melis-cms-category2/src/Service/MelisCsmCategoryService.php
```

* This service can be used inside other modules like so:  

```
// Get the category service
$cmsCategorySvc = $this->getServiceLocator()->get("MelisCmsCategory2Service");
 
// Get category tree
$result = $cmsCategorySvc->getCategoryTreeview($fatherId,$langId,$onlyValid, $siteId);
 
// Get the category data 
$categoryData = $cmsCategorySvc->getCategoryById($categoryId,$langId,$onlyValid);
  
```

* Common methods this service is used for are as follows:
    - Category creation/edition: saveCategory(...)
    - Get subscriber details: getSubscriberDetails(...)
    - Category site creation/edition: saveCategorySites(...)
    - Category translation creation/edition: saveCategoryTexts(...)
- For a more detailed information on the methods, please visit the file.

### Category display categories plugin
* Use the plugin in a hardcoded way , controller :
   

    /**
     * Generating category display using MelisCmsCategoryDisplayCategoriesPlugin Plugin
     * (hardcoded)
     */
    $categoryDisplay = $this->MelisCmsCategoryDisplayCategoriesPlugin();
    $categoryDisplayParameters = array(
        'template_path' => array('MelisCmsCategory2/default'),
        // Site id of News
        'site_id' => 1,
        'category_start' => 1,
    );
    // add generated view to children views for displaying it in the contact view
    $this->view->addChild($categoryDisplay->render($categoryDisplayParameters), 'categoryDisplay');
 
* view file :
        
        
     <?php echo $this->categoryDisplay; ?>


## Authors

* **Melis Technology** - [www.melistechnology.com](https://www.melistechnology.com/)

See also the list of [contributors](https://github.com/melisplatform/melis-newsletter/contributors) who participated in this project.

## License

This project is licensed under the Melis Technology premium versions end user license agreement (EULA) - see the [LICENSE.md](LICENSE.md) file for details
