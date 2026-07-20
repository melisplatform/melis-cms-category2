import { useMemo, useState, type CSSProperties } from 'react'
import { type TreeNode, type Lang, type Site } from './category-api'
import { useT } from './i18n'
import {
  card, input, btnPrimary, makeCan, IconPlus, IconTrash, IconChevron, IconSearch, IconRefresh, IconGrip,
  StatusDot, LangFlag,
} from './ui'

// Droits avancés — partie « l'Arbre » (cf. config/react.capabilities.php).
const can = makeCan('melis_cms_category_v2_tools_section')

interface Props {
  nodes: TreeNode[]
  langs: Lang[]
  sites: Site[]
  currentLangId: number
  onLangChange: (id: number) => void
  selectedId: number | null
  onSelect: (id: number) => void
  onAddRoot: () => void
  onAddChild: (parentId: number) => void
  onDelete: (node: TreeNode) => void
  onReorder: (parentId: number, orderedIds: number[]) => void
  onRefresh: () => void
  loading: boolean
}

/** Does the node or any descendant match the (lowercased) search text? */
function matchesSearch(node: TreeNode, q: string): boolean {
  if (!q) return true
  if (node.name.toLowerCase().includes(q)) return true
  return node.children.some(c => matchesSearch(c, q))
}
/** Does the node or any descendant belong to the site? */
function matchesSite(node: TreeNode, siteId: number): boolean {
  if (!siteId) return true
  if (node.sites.includes(siteId)) return true
  return node.children.some(c => matchesSite(c, siteId))
}
function findNode(nodes: TreeNode[], id: number): TreeNode | null {
  for (const n of nodes) { if (n.id === id) return n; const r = findNode(n.children, id); if (r) return r }
  return null
}
/** The ordered sibling list a node lives in (root siblings = the top-level array). */
function siblingsOf(nodes: TreeNode[], parentId: number): TreeNode[] {
  if (parentId === -1) return nodes
  return findNode(nodes, parentId)?.children ?? []
}

/** Custom language selector with flags (a native <select> can't render flag images). */
function LangDropdown({ langs, currentLangId, onChange }: { langs: Lang[]; currentLangId: number; onChange: (id: number) => void }) {
  const [open, setOpen] = useState(false)
  const current = langs.find(l => l.id === currentLangId) ?? langs[0]
  return (
    <div style={{ position: 'relative' }} onBlur={() => setTimeout(() => setOpen(false), 120)}>
      <button type="button" onClick={() => setOpen(o => !o)}
        style={{ ...input, width: 'auto', display: 'inline-flex', alignItems: 'center', gap: 6, cursor: 'pointer' }}>
        {current && <LangFlag locale={current.locale} size={16} />}
        <span style={{ fontSize: 14 }}>{current?.name ?? ''}</span>
        <span style={{ color: 'var(--color-muted-foreground)', fontSize: 10, marginLeft: 2 }}>▾</span>
      </button>
      {open && (
        <div style={{ ...card, position: 'absolute', top: 42, left: 0, zIndex: 30, minWidth: 170, padding: 4, display: 'flex', flexDirection: 'column', gap: 2 }}>
          {langs.map(l => (
            <button key={l.id} type="button" onMouseDown={() => { onChange(l.id); setOpen(false) }}
              style={{ display: 'flex', alignItems: 'center', gap: 8, padding: '7px 8px', borderRadius: 6, border: 0, cursor: 'pointer', fontSize: 14, textAlign: 'left',
                color: 'var(--color-foreground)',
                background: l.id === currentLangId ? 'color-mix(in srgb, var(--color-primary,#e11d48) 12%, transparent)' : 'transparent' }}>
              <LangFlag locale={l.locale} size={16} />{l.name}
            </button>
          ))}
        </div>
      )}
    </div>
  )
}

export default function CategoryTree(p: Props) {
  const t = useT()
  const [search, setSearch] = useState('')
  const [siteFilter, setSiteFilter] = useState(0)
  const [collapsed, setCollapsed] = useState<Set<number>>(new Set())
  const [drag, setDrag] = useState<{ id: number; parentId: number } | null>(null)
  const [dropInfo, setDropInfo] = useState<{ id: number; pos: 'before' | 'inside' | 'after' } | null>(null)

  const q = search.trim().toLowerCase()
  // Drag-reorder is offered only in the FULL, unfiltered view (a pruned/searched view has no
  // meaningful sibling order to write back) AND when the user has the `order` right.
  const dndEnabled = !q && !siteFilter && can('tree.order')

  // Keep only branches that match search + site (ancestors preserved).
  const visible = useMemo(() => {
    const prune = (list: TreeNode[]): TreeNode[] =>
      list
        .filter(n => matchesSearch(n, q) && matchesSite(n, siteFilter))
        .map(n => ({ ...n, children: prune(n.children) }))
    return prune(p.nodes)
  }, [p.nodes, q, siteFilter])

  const toggle = (id: number) =>
    setCollapsed(prev => { const s = new Set(prev); s.has(id) ? s.delete(id) : s.add(id); return s })

  // A node can't be dropped onto itself or into its own subtree (would create a cycle).
  const dragNode = drag ? findNode(p.nodes, drag.id) : null
  const isForbiddenTarget = (nodeId: number) =>
    !dragNode || nodeId === dragNode.id || !!findNode(dragNode.children, nodeId)

  const onDrop = (target: TreeNode) => {
    if (drag && dropInfo && dropInfo.id === target.id && !isForbiddenTarget(target.id)) {
      if (dropInfo.pos === 'inside') {
        // Re-parent: move the dragged node in as the LAST child of target.
        const ids = target.children.map(n => n.id).filter(id => id !== drag.id)
        ids.push(drag.id)
        setCollapsed(prev => { const s = new Set(prev); s.delete(target.id); return s }) // reveal the moved child
        p.onReorder(target.id, ids)
      } else {
        // Reorder as a sibling of target (works within a parent OR across parents — the
        // backend's /reorder sets each id's father to this parentId).
        const ids = siblingsOf(p.nodes, target.parentId).map(n => n.id).filter(id => id !== drag.id)
        let idx = ids.indexOf(target.id)
        if (dropInfo.pos === 'after') idx += 1
        ids.splice(idx, 0, drag.id)
        p.onReorder(target.parentId, ids)
      }
    }
    setDrag(null); setDropInfo(null)
  }

  const renderNode = (node: TreeNode, depth: number) => {
    const hasChildren = node.children.length > 0
    // While searching, force-open so matches are visible.
    const open = q ? true : !collapsed.has(node.id)
    const selected = p.selectedId === node.id
    const isDropTarget = dropInfo?.id === node.id
    const rowStyle: CSSProperties = {
      display: 'flex', alignItems: 'center', gap: 6, height: 34, paddingRight: 8,
      paddingLeft: 8 + depth * 18, borderRadius: 8, cursor: 'pointer',
      background: selected ? 'color-mix(in srgb, var(--color-primary,#e11d48) 12%, transparent)' : 'transparent',
      color: 'var(--color-foreground)',
      opacity: drag?.id === node.id ? 0.4 : 1,
      // before/after → an insertion line at the top/bottom edge; inside → a full outline (becomes a child).
      boxShadow: isDropTarget
        ? (dropInfo!.pos === 'before' ? 'inset 0 2px 0 0 var(--color-primary,#e11d48)'
          : dropInfo!.pos === 'after' ? 'inset 0 -2px 0 0 var(--color-primary,#e11d48)'
          : 'inset 0 0 0 2px var(--color-primary,#e11d48)')
        : 'none',
      ...(isDropTarget && dropInfo!.pos === 'inside'
        ? { background: 'color-mix(in srgb, var(--color-primary,#e11d48) 8%, transparent)' }
        : null),
    }
    return (
      <div key={node.id}>
        <div style={rowStyle}
          // Only the grip handle is draggable (below) — NOT the whole row — so a real mouse click on
          // the +/trash buttons registers as a click instead of accidentally starting a drag.
          onDragOver={e => {
            if (!drag || isForbiddenTarget(node.id)) return
            e.preventDefault()
            const r = e.currentTarget.getBoundingClientRect()
            // Three zones: top ¼ = drop before, bottom ¼ = drop after, middle ½ = drop inside (re-parent).
            const y = e.clientY - r.top
            const pos = y < r.height * 0.25 ? 'before' : y > r.height * 0.75 ? 'after' : 'inside'
            setDropInfo({ id: node.id, pos })
          }}
          onDragLeave={() => setDropInfo(d => (d?.id === node.id ? null : d))}
          onDrop={e => { e.preventDefault(); e.stopPropagation(); onDrop(node) }}
          onMouseEnter={e => { if (!selected) e.currentTarget.style.background = 'color-mix(in srgb, var(--color-muted,#888) 10%, transparent)' }}
          onMouseLeave={e => { if (!selected) e.currentTarget.style.background = 'transparent' }}
          onClick={() => p.onSelect(node.id)}>
          {dndEnabled ? (
            <span style={{ color: 'var(--color-muted-foreground)', opacity: 0.5, cursor: 'grab', display: 'inline-flex' }}
              title={t('drag_hint')} draggable
              onDragStart={e => { e.stopPropagation(); setDrag({ id: node.id, parentId: node.parentId }); e.dataTransfer.effectAllowed = 'move' }}
              onDragEnd={() => { setDrag(null); setDropInfo(null) }}
              onClick={e => e.stopPropagation()}>
              <IconGrip size={14} />
            </span>
          ) : null}
          <span style={{ width: 18, display: 'inline-flex', justifyContent: 'center', color: 'var(--color-muted-foreground)' }}
            onClick={e => { e.stopPropagation(); if (hasChildren) toggle(node.id) }}>
            {hasChildren ? <IconChevron open={open} size={14} /> : null}
          </span>
          <StatusDot active={node.status === 1} />
          <span style={{ flex: 1, minWidth: 0, fontSize: 14, fontWeight: selected ? 600 : 400,
            overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
            {node.name || <span style={{ fontStyle: 'italic', color: 'var(--color-muted-foreground)' }}>{t('no_name')}</span>}
          </span>
          {node.isFallback && node.name && (() => {
            const fb = p.langs.find(l => l.id === node.nameLangId)
            // Name shown in another language → just its flag (no language label), tooltip explains.
            return fb ? (
              <span style={{ display: 'inline-flex', flexShrink: 0 }} title={t('lang_fallback', { lang: fb.name })}>
                <LangFlag locale={fb.locale} size={15} />
              </span>
            ) : null
          })()}
          {can('tree.create') && (
            <button title={t('add_child')} onClick={e => { e.stopPropagation(); p.onAddChild(node.id) }}
              style={iconBtn}><IconPlus size={15} /></button>
          )}
          {can('tree.delete') && (
            <button title={t('del')} onClick={e => { e.stopPropagation(); p.onDelete(node) }}
              style={iconBtn}><IconTrash size={15} /></button>
          )}
        </div>
        {hasChildren && open ? node.children.map(c => renderNode(c, depth + 1)) : null}
      </div>
    )
  }

  return (
    <div style={{ ...card, display: 'flex', flexDirection: 'column', minHeight: 0, height: '100%', overflow: 'hidden' }}>
      {/* toolbar */}
      <div style={{ display: 'flex', flexDirection: 'column', gap: 8, padding: 12, borderBottom: '1px solid var(--color-border)' }}>
        <div style={{ display: 'flex', gap: 8 }}>
          <LangDropdown langs={p.langs} currentLangId={p.currentLangId} onChange={p.onLangChange} />
          <select value={siteFilter} onChange={e => setSiteFilter(Number(e.target.value))}
            style={{ ...input, width: 'auto', flex: 1 }}>
            <option value={0}>{t('all_sites')}</option>
            {p.sites.map(s => <option key={s.id} value={s.id}>{s.name}</option>)}
          </select>
          <button title={t('refresh')} onClick={p.onRefresh} style={{ ...iconBtnBox, opacity: p.loading ? 0.5 : 1 }}>
            <IconRefresh size={16} />
          </button>
        </div>
        <div style={{ position: 'relative' }}>
          <span style={{ position: 'absolute', left: 10, top: 10, color: 'var(--color-muted-foreground)' }}><IconSearch size={16} /></span>
          <input value={search} onChange={e => setSearch(e.target.value)} placeholder={t('search')}
            style={{ ...input, paddingLeft: 34 }} />
        </div>
        {can('tree.create') && (
          <button onClick={p.onAddRoot} style={{ ...btnPrimary, height: 38 }}><IconPlus size={16} />{t('new_root')}</button>
        )}
      </div>

      {/* tree */}
      <div style={{ flex: 1, overflow: 'auto', padding: 8 }}>
        {visible.length === 0 ? (
          <div style={{ padding: '32px 12px', textAlign: 'center', fontSize: 13, color: 'var(--color-muted-foreground)' }}>
            {t('empty_tree')}
          </div>
        ) : visible.map(n => renderNode(n, 0))}
      </div>
    </div>
  )
}

const iconBtn: CSSProperties = {
  display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 26, height: 26,
  borderRadius: 6, border: 0, background: 'transparent', color: 'var(--color-muted-foreground)', cursor: 'pointer',
}
const iconBtnBox: CSSProperties = {
  display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 36, height: 36,
  borderRadius: 8, border: '1px solid var(--color-border)', background: 'var(--color-card)',
  color: 'var(--color-foreground)', cursor: 'pointer',
}
