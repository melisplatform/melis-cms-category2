// Category selector (id) — a TREE picker analogous to the page selector (melis-cms PagePicker), but
// backed by category2's OWN react-api (category-api.fetchTree, same endpoint the Categories tool uses).
// Shows the current category name + a popover tree (click = select). Styles inline + theme CSS vars
// (brick rule — no Tailwind pipeline here). No backend change: reuses /melis/MelisCmsCategory2/react-api.
import { useEffect, useRef, useState } from 'react'
import { fetchTree, fetchLangs, type TreeNode } from '../src/category-api'

const box: React.CSSProperties = { borderRadius: 8, border: '1px solid var(--color-border,#e5e7eb)', background: 'var(--color-background,#fff)' }
const btn: React.CSSProperties = { display: 'inline-flex', alignItems: 'center', justifyContent: 'space-between', gap: 8, height: 36, width: '100%', padding: '0 10px', cursor: 'pointer', fontSize: 14, ...box }

function findNode(nodes: TreeNode[], id: number): TreeNode | null {
  for (const n of nodes) {
    if (n.id === id) return n
    const c = findNode(n.children || [], id)
    if (c) return c
  }
  return null
}

/** Resolve the lang id whose category names we show: the BO document lang, else the first available. */
async function resolveLangId(): Promise<number> {
  const langs = await fetchLangs()
  if (!langs.length) return 1
  const doc = (document.documentElement.lang || 'en').toLowerCase()
  const hit = langs.find((l) => l.locale.toLowerCase().startsWith(doc.slice(0, 2)))
  return (hit || langs[0]).id
}

function CatNode({ node, depth, onPick }: { node: TreeNode; depth: number; onPick: (id: number, name: string) => void }) {
  const hasChildren = (node.children?.length ?? 0) > 0
  const [open, setOpen] = useState(true)
  return (
    <div>
      <div style={{ display: 'flex', alignItems: 'center', gap: 4, padding: '4px 6px', paddingLeft: 6 + depth * 16 }}>
        <button
          onClick={() => hasChildren && setOpen((o) => !o)}
          style={{ width: 18, height: 18, border: 0, background: 'transparent', cursor: hasChildren ? 'pointer' : 'default', color: 'var(--color-muted-foreground,#6b7280)', fontSize: 11 }}
        >{hasChildren ? (open ? '▾' : '▸') : '·'}</button>
        <button
          onClick={() => onPick(node.id, node.name)}
          style={{ flex: 1, textAlign: 'left', border: 0, background: 'transparent', cursor: 'pointer', fontSize: 13, padding: '2px 4px', borderRadius: 6 }}
        >{node.name || `#${node.id}`}</button>
      </div>
      {open && hasChildren && (node.children).map((c) => <CatNode key={c.id} node={c} depth={depth + 1} onPick={onPick} />)}
    </div>
  )
}

export function CategoryPicker({ value, onChange, placeholder }: {
  value: number
  onChange: (id: number, name: string) => void
  placeholder?: string
}) {
  const [open, setOpen] = useState(false)
  const [nodes, setNodes] = useState<TreeNode[] | null>(null)
  const ref = useRef<HTMLDivElement>(null)

  // Load the tree once (small, non-lazy) so the current value's NAME shows immediately and the panel is ready.
  useEffect(() => {
    let cancelled = false
    ;(async () => {
      try {
        const langId = await resolveLangId()
        const t = await fetchTree(langId)
        if (!cancelled) setNodes(t)
      } catch { if (!cancelled) setNodes([]) }
    })()
    return () => { cancelled = true }
  }, [])

  useEffect(() => {
    function onDoc(e: MouseEvent) { if (ref.current && !ref.current.contains(e.target as Node)) setOpen(false) }
    document.addEventListener('mousedown', onDoc)
    return () => document.removeEventListener('mousedown', onDoc)
  }, [])

  const current = value && nodes ? findNode(nodes, value) : null
  const display = value ? (current?.name || `Catégorie #${value}`) : (placeholder || 'Choisir une catégorie…')

  return (
    <div ref={ref} style={{ position: 'relative' }}>
      <button style={btn} onClick={() => setOpen((o) => !o)} type="button" data-testid="category-picker">
        <span style={{ overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap', color: value ? 'inherit' : 'var(--color-muted-foreground)' }}>{display}</span>
        <span style={{ color: 'var(--color-muted-foreground)' }}>▾</span>
      </button>
      {open && (
        <div style={{ ...box, position: 'absolute', zIndex: 60, top: 40, left: 0, right: 0, maxHeight: 320, overflow: 'auto', boxShadow: '0 8px 24px rgba(0,0,0,.12)', padding: 6 }}>
          {nodes === null ? (
            <div style={{ padding: 12, fontSize: 13, color: 'var(--color-muted-foreground)' }}>Chargement…</div>
          ) : nodes.length === 0 ? (
            <div style={{ padding: 12, fontSize: 13, color: 'var(--color-muted-foreground)' }}>Aucune catégorie</div>
          ) : nodes.map((n) => (
            <CatNode key={n.id} node={n} depth={0} onPick={(id, nm) => { onChange(id, nm); setOpen(false) }} />
          ))}
        </div>
      )}
    </div>
  )
}

export default CategoryPicker
