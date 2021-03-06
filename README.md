# melis-cms-category2

This module brings a full category system to use in your websites and to administrate on the platform with fully multi lingual and multi sites functionalities

## Getting started

These instructions will get you a copy of the project up and running on your machine.

### Prerequisites

The following modules need to be installed to have Melis cms category2 module run:
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
 
### Categories tool
Provides the user with the basic actions in managing categories such as:
* Creation
    - user can create a category
* Edition
    - user can manage the category titles, contents, dates of validity, or attach media(images, files).
* Deletion
    - user can delete a category

### MelisCmsCategory2 Services  

MelisCmsCategory2 provides two services to be used in other modules.
Find them in the folder : /melis-cms-category2/src/Service
```
File: /melis-cms-category2/src/Service/MelisCsmCategoryService.php
```

* This service can be used inside other modules like so:  

```
// Get the category service
$cmsCategorySvc = $this->getServiceManager()->get("MelisCmsCategory2Service");
 
// Get category tree
$result = $cmsCategorySvc->getCategoryTreeview($fatherId,$langId,$onlyValid, $siteId);
 
// Get the category data 
$categoryData = $cmsCategorySvc->getCategoryById($categoryId,$langId,$onlyValid);
  
```

* Common methods this service is used for are as follows:
    - Category creation/edition: saveCategory(...)
    - Category site creation/edition: saveCategorySites(...)
    - Category translation creation/edition: saveCategoryTexts(...)
- For a more detailed information on the methods, please visit the file.

### Category display categories plugin
* Use the plugin in a hardcoded way , in your controller :
   
```
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
```
* In your view file :
    
````
<?php echo $this->categoryDisplay; ?>
````

## Authors

* **Melis Technology** - [www.melistechnology.com](https://www.melistechnology.com/)

See also the list of [contributors](https://github.com/melisplatform/melis-newsletter/contributors) who participated in this project.

## License

This project is licensed under the Melis Technology premium versions end user license agreement (EULA) - see the [LICENSE.md](LICENSE.md) file for details
