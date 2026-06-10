---
title: MelisCmsCategory2 module
package: melisplatform/melis-cms-category2
doc_type: module-documentation
audience: [users, developers, ai]
language: en
module_version: unversioned
last_reviewed: 2026-06-08
maintainer: Melis Technology
keywords: [category, categories, catalog, tree, taxonomy, multilingual, multisite, cms, melis, back-office, plugin, media]
screenshots_dir: ./images
---

# MelisCmsCategory2 — Functional & Technical Documentation (for AI)

> **What this is.** MelisCmsCategory2 is the **category / catalog system** of the Melis
> platform: a back-office tool to build a **tree of categories** (multilingual, multi-site, with
> attached media), a **content block** to display categories on the site, and a reusable
> **category picker** that other modules (e.g. News, Commerce) embed to classify their records.
>
> **Two parts:** **[Part A — Functional Guide](#part-a--functional-guide)** (users) ·
> **[Part B — Technical Reference](#part-b--technical-reference)** (developers/AI, with examples).
> Consumed by the **MelisAI** MCP; the **[Screenshot index](#screenshot-index)** maps filenames.
> Reviewed 2026-06-08.

---
---

# PART A — Functional Guide

## A1. What MelisCmsCategory2 lets you do

- **Organise content into a tree** of **catalogs** (top-level) and **categories** (children) —
  like a folder hierarchy for classifying things.
- **Name each category in several languages** (with a description).
- **Choose which sites** a category belongs to (multi-site).
- **Attach media** (images/files) to a category.
- **Set validity dates and a status** so a category shows only when it should.
- **Classify other content** — News articles, products, etc., can be filed under these
  categories (the same picker is reused across modules).

## A2. The Category tool (back-office)

**Where:** back-office left menu → **CMS** tools → **Category** manager (`fa-th-list` icon).

### The category tree

Categories are shown as an **accordion of catalogs**, each panel holding that catalog's
**tree** of categories. A **search** box and a **site filter** help you find things; the header
offers **Add catalog** (a new top-level) and **Add category** (a child).

![Category tool — accordion of catalogs](./images/meliscmscategory2-tool-categories-accordionsystem.png)
*The Category tool's main view — catalogs as an accordion, each with its category tree.*

![Category tool — the tree](./images/meliscmscategory2-tool-categories-tab-treelist.png)
*The category tree of a catalog — expandable, multi-level.*

Each node has its own **actions** (add a child, edit, delete) and nodes can be **drag-reordered**
or **re-parented** to reshape the tree.

![Category tool — node actions](./images/meliscmscategory2-tool-categories-tab-tree-actions.png)
*The actions available on a tree node.*

### Creating / editing a category

The same editor is used to create and to edit. Its **Properties** tab gathers the per-language
**name and description**, the **status**, the **validity dates** (start/end) and the **sites**
the category belongs to.

![Creating a category — Properties](./images/meliscmscategory2-tool-categories-new-tab-properties.png)
![Editing a category — Properties](./images/meliscmscategory2-tool-categories-edit-tab-properties.png)
*Creating and editing a category — name/description per language, status, validity, sites.*

A **Media** tab lets you attach images/files to the category.

![Category editor — Media tab](./images/meliscmscategory2-tool-categories-new-tab-media.png)
*The Media tab — attach, browse, upload and remove images/files.*

## A3. Showing categories on the site — the Display Categories block

From the **page editor**, drop the **Display Categories** block onto a page. Its **Properties**
settings let you pick the **rendering template**, the **source site**, and the **starting
category** to display from (with a search picker).

![Display Categories — settings](./images/meliscmscategory2-page-plugin-categories-config-properties.png)
![Display Categories — pick the start category](./images/meliscmscategory2-page-plugin-categories-config-properties-search-category.png)
*Display Categories settings — template, site, and the category node to start from.*

## A4. Used by other modules

The category **picker** is reused elsewhere: with **MelisCmsNews** you file articles under
categories; with a commerce/product module the category editor gains **Products** and **SEO**
tabs to assign products. These appear only when those modules are installed.

## A5. Common tasks — "How do I…?"

- **Create a catalog/category** → Category tool → **Add catalog** (top) or **Add category**
  (child) → fill name (per language), sites, status.
- **Reorganise the tree** → drag nodes to reorder or re-parent.
- **Add an image to a category** → open it → **Media** tab → upload.
- **Show categories on a page** → page editor → drop **Display Categories** → pick the start node.
- **File a news article under a category** → in the News editor (needs the category feature).

---
---

# PART B — Technical Reference

## B1. Metadata & dependencies

| Item | Value |
|---|---|
| Package | `melisplatform/melis-cms-category2` · category `cms` · namespace `MelisCmsCategory2\` · dbdeploy |
| Requires | `melis-core`, `melis-cms` (`^5.2`) (README also lists `melis-engine` for front rendering) |
| Tree UI | **jsTree** (`public/assets/jstree/`) |

> **License note:** `composer.json` declares OSL-3.0 while the README references the Melis
> premium EULA — flag to a human if licensing matters.

## B2. Data model

| Table | Role | PK |
|---|---|---|
| `melis_cms_category2` | Category node: parent `cat2_father_cat_id`, order, status, reference, validity dates, audit | `cat2_id` |
| `melis_cms_category2_trans` | Per-language name + description | `catt2_id` |
| `melis_cms_category2_sites` | Category ↔ site links | `cats2_id` |
| `melis_cms_category2_media` | Media (`catm2_type`, `catm2_path`, `catm2_cat_id`) | `catm2_id` |

Gateways: `MelisCmsCategory2Table`, `…TransTable`, `…SitesTable`, `…MediaTable`. A seed root
(`cat2_id=1`, "My catalog"/"Mon catalogue") is inserted on install.

## B3. Services (with examples)

```php
$cat = $this->getServiceManager()->get('MelisCmsCategory2Service');

$tree = $cat->getCategoryTreeview($fatherId, $langId, $onlyValid, $siteId); // the tree
$node = $cat->getCategoryById($categoryId, $langId, $onlyValid);            // full category
$name = $cat->getCategoryNameById($categoryId, $langId);
$site = $cat->getCategoriesPerSite($siteId, $langId);

$id   = $cat->saveCategory(/* node data */);                 // create/update a node
$cat->saveCategoryTexts($categoryId, $catLangId, $postData, $id);   // translation
$cat->saveCategorySites($categoryId, $siteId, $id, $toDelete);      // site link
$cat->reOrderCategories($parentId, $currentOrder);                 // persist ordering
```

Key methods: `getCategoryTreeview`, `getCategoryById`, `getCategoryListByIdRecursive`,
`getCategoryNameById`, `getCategoryTranslationById`, `getFirstLevelCategoriesPerSite`,
`getCategoriesPerSite`, `getCategoryMediaById`, `saveCategory`, `saveCategoryTexts`,
`saveCategorySites`, `reOrderCategories`, `validateDates`. Media service
`MelisCmsCategory2MediaService`: `uploadFile`, `deleteFile`, `getMediaFilesByCategoryId`,
`getFilesInDir`, `removeCategoryDir`, … View helper `renderTreeRec` recursively renders the tree.

## B4. The reusable picker & the front plugin

- **Picker**: `MelisCmsCategorySelect` form element (`src/Form/Factory/MelisCmsCategorySelectFactory.php`)
  + select modal (`MelisCmsCategorySelectController`: `renderCategorySelectModalAction`,
  `…ContentAction`) — embedded by News/Commerce.
- **Front plugin**: `MelisCmsCategoryDisplayCategoriesPlugin` (extends `MelisTemplatingPlugin`),
  template `MelisCmsCategory2/default` (`view/.../plugins/default.phtml`), config
  `config/plugins/MelisCmsCategoryDisplayCategoriesPlugin.config.php` (one Properties tab:
  `template_path`, `site_id`, `category_start`). Can also be used hardcoded:

```php
$display = $this->MelisCmsCategoryDisplayCategoriesPlugin();
echo $display->render(['template_path' => ['MelisCmsCategory2/default'], 'site_id' => 1, 'category_start' => 1]);
```

## B5. Controllers, listener, cross-module

- Controllers: `MelisCmsCategoryListController` (tree + filters + add catalog/category),
  `MelisCmsCategoryController` (editor: translations/status/validity/sites/products/seo, save,
  delete, `getCategoryTreeViewAction`), `MelisCmsCategoryMediaController` (media browse/upload/
  delete), `MelisCmsCategorySelectController` (the picker modal), `Plugin/` (the display plugin).
- Listener: `MelisCmsCategory2FlashMessengerListener` (back-office).
- **Consumers**: News (`melis_cms_news_category` links articles to categories), commerce
  (Products/SEO tabs on the category editor).

## B6. Quick code map

```
melis-cms-category2/
├── config/   module.config.php · app.interface.php · app.forms.php · plugins/ (display + form)
├── src/   Controller/ (List, Category, Media, Select, Plugin/) · Service/ (Category, Media)
│        · Entity/MelisCategory · Model/ + Model/Tables/ · Listener/ · Form/Factory/ · View/Helper/RenderRecTree
├── view/ · public/ (jstree, JS/CSS) · language/ · install/ (SQL + seed)
└── etc/   MarketPlace + MelisAI/doc (this doc)
```

---

## Screenshot index

| Image file | Content |
|---|---|
| `meliscmscategory2-tool-categories-accordionsystem.png` | Category tool — catalogs as an accordion |
| `meliscmscategory2-tool-categories-tab-treelist.png` | Category tool — the jsTree hierarchy |
| `meliscmscategory2-tool-categories-tab-tree-actions.png` | Category tool — per-node actions |
| `meliscmscategory2-tool-categories-new-tab-properties.png` | Creating a category — Properties tab |
| `meliscmscategory2-tool-categories-edit-tab-properties.png` | Editing a category — Properties tab |
| `meliscmscategory2-tool-categories-new-tab-media.png` | Category editor — Media tab |
| `meliscmscategory2-page-plugin-categories-config-properties.png` | Display Categories — settings |
| `meliscmscategory2-page-plugin-categories-config-properties-search-category.png` | Display Categories — start-category picker |

---

*Document for AI consumption (MelisAI MCP) — `melisplatform/melis-cms-category2`. Part A =
functional; Part B = technical with examples. Last reviewed 2026-06-08.*
