# Changelog

## v6.0.6 - 2026-10-05
### Security
* Page-editor: internationalize the React UI (was hardcoded French)
### Changed
* I18n: fix wrong-language values and mismatched keys in interface translations
* Page-editor: category_start/categoryIdNews use a TREE category picker
* Page-editor: full-React config for Display-categories + category filter tab on the news plugins

## v6.0.5 - 2026-09-25
### Security
* **security:** declare a grantable tool key on the legacy controller (audit item 7.0)

## v6.0.4 - 2026-09-23
### Security
* **security:** parameterised SQL for date filters, ORDER BY and hand-quoted values (audit item 11.0)

## v6.0.3 - 2026-08-20
### Security
* Gate mutating/data actions in legacy tool controllers (CWE-862)
### Fixed
* **category2-react:** code icon on the New toggle
### Docs
* **melisai:** React back-office AI documentation for MelisCmsCategory2

## v6.0.1 - 2026-08-10
### Added
* **composer:** add docs link and authors block, swap zf2 keyword for laminas, bump php constraint to ^8.3|^8.5
### Changed
* Bump postcss from 8.5.15 to 8.5.26 in /ui-react

## v6.0.0 - 2026-08-10
### Security
* **security:** add SECURITY.md (private vulnerability reporting policy)
* Fix audit findings
* **rights:** put the Categories rights key back on the left-menu path
* **category:** advanced rights (tree create/order/delete + edition properties/media) + persistent brick
### Added
* **category2-react:** unified error handling + inline field errors
* **webservices:** expose 10 category read services (+ MelisCmsCategoryService alias, include, i18n)
* D&D re-parenting (drop a category inside another)
* **category2:** migration full-React de l outil Categories
* **react:** category2 React brick (iframe) + fix save notification color
* Add MelisAI module documentation for AI consumption
### Fixed
* **security:** harden legacy file/dir creation & output escaping
* **cms-category-react:** keep original uploaded file/image name instead of a random one (ticket 0010862, match legacy)
* **react:** brick route fallback -> /melis-cms/category-v2
### Dependencies & build
* **composer:** bump melis-core/melis-cms constraint to ^6.0
* Local WIP snapshot before reconcile (20260806-114605)
* **ui-react:** commit pending category UI files (already in parent local6-2)
* **brick:** rebuild brick ui-react + sync vite/package-lock; remove stale test/phpunit.xml
### Docs
* **meliscmscategory2:** rewrite as two-part doc (functional guide + technical reference with examples)

## v5.3.5 - 2025-07-15
### Changed
* 101 updates
* Update js

## v5.3.3 - 2025-04-08
### Added
* Add category in news listing
### Changed
* Move category to filter tab

## v5.3.2 - 2025-03-10
### Added
* Added the throbber.gif of jstree plugin
### Dependencies & build
* Rebuilt the asset bundle

## v5.3.1 - 2024-09-26
### Fixed
* Fix release issue 7114

## v5.3.0 - 2024-09-25
### Fixed
* Fix issue 7004
* Fix issue 7006
* Fix issue 6979
### Changed
* Jstree, category.tool.js and category.plugin.select.js edits
* Bs5 tab
* Update jQuery 3.7.1 migration
* JQuery 3.7.1 migration
* Update on jQuery migration

## v5.2.0 - 2024-06-06
* Maintenance release.

## v5.1.1 - 2024-04-08
### Added
* Added toolbar mode on tinymce options
### Fixed
* Fix issue 4906
* Fix issue 3568
* Fix issue on 6102
### Changed
* Tinymce type tool full toolbar buttons
* Edit on renamed tinymce toolbar item
* Update on tinymce 6
* Update on tinymce 6.7.0
### Dependencies & build
* Rebuilt the asset bundle

## v5.1.0 - 2024-02-13
### Changed
* Listener dynamic property
* Interop -> Psr
* Deprecated null on str_replace

## v5.0.0 - 2022-06-22
### Changed
* Changed deprecated ArraySerializable to ArraySerializableHydrator and updated other functions affected by php 8
