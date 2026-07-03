import { useEffect, useMemo, useState, type CSSProperties } from 'react'
import {
  fetchLangs, fetchSites, fetchTree, deleteCategory, reorderCategories,
  type Lang, type Site, type TreeNode,
} from './category-api'
import { currentLang, useT } from './i18n'
import { melisNotify, useConfirm } from './ui'
import { ViewToggle, type ViewMode } from './ViewToggle'
import CategoryTree from './CategoryTree'
import CategoryEditor, { type EditTarget } from './CategoryEditor'

/* ──────────────────────────────────────────────────────────────────────────
 * Categories tool (MelisCmsCategory2) — full React BRICK.
 * Master-detail: hierarchical tree (left) + tabbed editor (right). Multilingual
 * translations, validity dates, site associations, status. Media + drag-drop = phase 2.
 * The "Old" toggle mounts the legacy tool in an iframe. Styles inline + theme CSS vars,
 * i18n FR/EN via <html lang> (the brick doesn't share the host modules).
 * ────────────────────────────────────────────────────────────────────────── */

const MELIS_KEY = 'melis_cms_categories_v2'  // renderable legacy zone (Old view)

/** Find a node (and its parent's name) anywhere in the tree. */
function findNode(nodes: TreeNode[], id: number, parentName = ''): { node: TreeNode; parentName: string } | null {
  for (const n of nodes) {
    if (n.id === id) return { node: n, parentName }
    const found = findNode(n.children, id, n.name)
    if (found) return found
  }
  return null
}

export default function CategoryPage() {
  const t = useT()
  const [mode, setMode] = useState<ViewMode>('react')
  const [frameLoaded, setFrameLoaded] = useState(false)

  const [langs, setLangs] = useState<Lang[]>([])
  const [sites, setSites] = useState<Site[]>([])
  const [currentLangId, setCurrentLangId] = useState<number>(0)
  const [nodes, setNodes] = useState<TreeNode[]>([])
  const [loading, setLoading] = useState(false)
  const [tick, setTick] = useState(0)

  const [selectedId, setSelectedId] = useState<number | null>(null)
  const [target, setTarget] = useState<EditTarget | null>(null)
  const { ask, el: confirmEl } = useConfirm()

  // Load languages + sites once; pick the language matching the session locale.
  useEffect(() => {
    Promise.all([fetchLangs(), fetchSites()]).then(([ls, ss]) => {
      setLangs(ls); setSites(ss)
      const wantFr = currentLang() === 'fr'
      const match = ls.find(l => l.locale.toLowerCase().startsWith(wantFr ? 'fr' : 'en')) ?? ls[0]
      setCurrentLangId(match?.id ?? 1)
    }).catch(() => { setLangs([]); setSites([]) })
  }, [])

  // (Re)load the tree whenever the language or a refresh tick changes.
  useEffect(() => {
    if (!currentLangId) return
    setLoading(true)
    fetchTree(currentLangId)
      .then(setNodes)
      .catch(() => setNodes([]))
      .finally(() => setLoading(false))
  }, [currentLangId, tick])

  const reloadTree = () => setTick(x => x + 1)

  const onSelect = (id: number) => {
    const found = findNode(nodes, id)
    setSelectedId(id)
    setTarget({ id, parentId: found?.node.parentId ?? -1, parentName: found?.parentName })
  }
  const onAddRoot = () => { setSelectedId(null); setTarget({ id: null, parentId: -1 }) }
  const onAddChild = (parentId: number) => {
    const found = findNode(nodes, parentId)
    setSelectedId(null)
    setTarget({ id: null, parentId, parentName: found?.node.name })
  }
  const onDelete = async (node: TreeNode) => {
    const ok = await ask({
      title: t('del'), message: t('del_confirm', { name: node.name || '#' + node.id }),
      confirmLabel: t('del'), cancelLabel: t('cancel'), danger: true,
    })
    if (!ok) return
    try {
      await deleteCategory(node.id)
      if (target?.id === node.id) { setTarget(null); setSelectedId(null) }
      reloadTree()
      melisNotify('success', t('notif_deleted'))
    } catch (e: any) {
      const msg = e?.message || t('err_generic')
      melisNotify('error', t('notif_error'), msg)
    }
  }
  const onSaved = (id: number) => {
    reloadTree()
    setSelectedId(id)
    setTarget(prev => ({ id, parentId: prev?.parentId ?? -1, parentName: prev?.parentName }))
  }
  const onReorder = async (parentId: number, orderedIds: number[]) => {
    // Optimistic-ish: fire the reorder, then reload the authoritative tree order.
    try { await reorderCategories(parentId, orderedIds) }
    catch (e: any) { window.alert(e?.message || t('err_generic')) }
    finally { reloadTree() }
  }

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 16, padding: 24, height: '100%', boxSizing: 'border-box', overflow: 'hidden' }}>
      {/* header */}
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16, flexShrink: 0 }}>
        <div>
          <h1 style={{ fontSize: 20, fontWeight: 700, margin: 0 }}>{t('title')}</h1>
          <p style={{ fontSize: 14, color: 'var(--color-muted-foreground)', margin: '2px 0 0' }}>{t('subtitle')}</p>
        </div>
        <ViewToggle mode={mode} onChange={m => { setMode(m); if (m === 'iframe') setFrameLoaded(true) }} />
      </div>

      {/* Old view: legacy tool in an iframe (mounted once loaded, hidden in React mode) */}
      {frameLoaded && (
        <div style={{ display: mode === 'iframe' ? 'block' : 'none', flex: 1, minHeight: 0,
          border: '1px solid var(--color-border)', borderRadius: 12, overflow: 'hidden' }}>
          <iframe src={`/melis/react-tool-page?key=${encodeURIComponent(MELIS_KEY)}`}
            style={{ width: '100%', height: '100%', border: 0 }} title={t('title')} />
        </div>
      )}

      {/* New view: master-detail */}
      <div style={{ display: mode === 'react' ? 'grid' : 'none', gridTemplateColumns: 'minmax(320px, 420px) minmax(0, 1fr)',
        gap: 16, flex: 1, minHeight: 0 }}>
        <CategoryTree
          nodes={nodes} langs={langs} sites={sites} currentLangId={currentLangId}
          onLangChange={setCurrentLangId} selectedId={selectedId} onSelect={onSelect}
          onAddRoot={onAddRoot} onAddChild={onAddChild} onDelete={onDelete}
          onReorder={onReorder} onRefresh={reloadTree} loading={loading}
        />
        <CategoryEditor
          target={target} langs={langs} sites={sites}
          onSaved={onSaved} onCancel={() => { setTarget(null); setSelectedId(null) }}
        />
      </div>
      {confirmEl}
    </div>
  )
}
