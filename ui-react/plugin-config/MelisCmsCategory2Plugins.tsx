// Full-React config TABS for MelisCmsCategory2. Source lives in this module, imported into melis-cms's
// SPA build and registered into the shared tab registry.
//  - Its OWN plugin: "Display categories" (template + start category + site).
//  - CROSS-MODULE: category2 augments the news list plugins with a `categoryIdNews` filter (the legacy
//    config merge adds that field to the news form). We contribute it here as an extra "Catégorie" tab
//    on those plugins — the modular-tab pattern the kit is built for. Its <select> options + prefill come
//    from the shared options endpoint (parses createOptionsForms, which includes category2's merged field
//    while the module is active), so the field is empty/degrades gracefully if category2 is disabled.
import {
  registerPluginTab, type PluginTabContext,
  TemplateField, TextField, RemoteSelectField,
} from '../../../melis-cms/ui-react/src/PluginFormKit'

const COG = 'fa fa-cog', TAG = 'fa fa-tags'

// NOTE: `category_start`/`categoryIdNews` are `MelisCmsCategorySelect` fields — legacy renders a text
// input backed by a category-TREE modal (dependent on the selected site). Here they are simple ID text
// fields for now: byte-compatible (the legacy input holds the same category id) and self-prefilling.
// A richer CategoryPicker (fetching category2's `/react-api/tree`) would be a follow-up.

/* ── Display categories ── template + start category + site ──────────────── */
function DisplayCategoriesProperties({ ctx }: { ctx: PluginTabContext }) {
  return (<div>
    <TemplateField ctx={ctx} hint="Gabarit de rendu des catégories." />
    <TextField ctx={ctx} name="category_start" label="Catégorie de départ (ID)" type="number" hint="ID de la catégorie racine à partir de laquelle afficher l'arbre." />
    <RemoteSelectField ctx={ctx} name="site_id" label="Site" hint="Le site dont on affiche les catégories." />
  </div>)
}

/* ── Cross-module: category filter contributed to the news list plugins ───── */
function NewsCategoryFilter({ ctx }: { ctx: PluginTabContext }) {
  return (<div>
    <TextField ctx={ctx} name="categoryIdNews" label="Catégorie (ID)" type="number" hint="ID de catégorie : ne montrer que les actualités de cette catégorie (fourni par le module Categories)." />
  </div>)
}

/** Register MelisCmsCategory2's native config tabs. Called from melis-cms's PluginForms registry. */
export function registerMelisCmsCategory2Plugins(): void {
  registerPluginTab('MelisCmsCategoryDisplayCategoriesPlugin', { id: 'properties', title: 'Propriétés', icon: COG, order: 0, Component: DisplayCategoriesProperties })
  // Contribute the category filter to the news list plugins (parity with the legacy config merge).
  registerPluginTab('MelisCmsNewsLatestNewsPlugin', { id: 'category', title: 'Catégorie', icon: TAG, order: 2, Component: NewsCategoryFilter })
  registerPluginTab('MelisCmsNewsListNewsPlugin', { id: 'category', title: 'Catégorie', icon: TAG, order: 3, Component: NewsCategoryFilter })
}
