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
  TemplateField, RemoteSelectField, Field, usePrefill,
} from '../../../melis-cms/ui-react/src/PluginFormKit'
import { CategoryPicker } from './CategoryPicker'

const COG = 'fa fa-cog', TAG = 'fa fa-tags'

/** A category selector bound to ctx[name] (the numeric category id) — a TREE picker like the page
 *  selector, reusing category2's own category-api tree. `MelisCmsCategorySelect` = a text input holding
 *  the category id in legacy, so writing the id here stays byte-compatible. */
function CategoryField({ ctx, name, label, hint }: { ctx: PluginTabContext; name: string; label: string; hint?: string }) {
  usePrefill(ctx, name)
  const id = parseInt(ctx.value(name), 10)
  return (
    <Field label={label} error={ctx.error(name)} hint={hint}>
      <div data-testid={`field-${name}`}>
        <CategoryPicker value={Number.isFinite(id) ? id : 0} onChange={(cid) => ctx.setValue(name, cid ? String(cid) : '')} placeholder="Choisir une catégorie…" />
      </div>
    </Field>
  )
}

/* ── Display categories ── template + start category + site ──────────────── */
function DisplayCategoriesProperties({ ctx }: { ctx: PluginTabContext }) {
  return (<div>
    <TemplateField ctx={ctx} hint="Gabarit de rendu des catégories." />
    <CategoryField ctx={ctx} name="category_start" label="Catégorie de départ" hint="La catégorie racine à partir de laquelle afficher l'arbre." />
    <RemoteSelectField ctx={ctx} name="site_id" label="Site" hint="Le site dont on affiche les catégories." />
  </div>)
}

/* ── Cross-module: category filter contributed to the news list plugins ───── */
function NewsCategoryFilter({ ctx }: { ctx: PluginTabContext }) {
  return (<div>
    <CategoryField ctx={ctx} name="categoryIdNews" label="Catégorie" hint="Ne montrer que les actualités de cette catégorie (fourni par le module Categories)." />
  </div>)
}

/** Register MelisCmsCategory2's native config tabs. Called from melis-cms's PluginForms registry. */
export function registerMelisCmsCategory2Plugins(): void {
  registerPluginTab('MelisCmsCategoryDisplayCategoriesPlugin', { id: 'properties', title: 'Propriétés', icon: COG, order: 0, Component: DisplayCategoriesProperties })
  // Contribute the category filter to the news list plugins (parity with the legacy config merge).
  registerPluginTab('MelisCmsNewsLatestNewsPlugin', { id: 'category', title: 'Catégorie', icon: TAG, order: 2, Component: NewsCategoryFilter })
  registerPluginTab('MelisCmsNewsListNewsPlugin', { id: 'category', title: 'Catégorie', icon: TAG, order: 3, Component: NewsCategoryFilter })
}
