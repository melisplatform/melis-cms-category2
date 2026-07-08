import { useEffect, useRef, useState, type CSSProperties, type ReactNode } from 'react'

/* Shared inline-style helpers + tiny primitives for the Categories brick (theme CSS vars). */

// ── Capacités (droits avancés) — lit le global window.MelisCan (default-allow). Clé = melisKey du
// nœud menu porteur de droits (cf. config/react.capabilities.php). ──
export function makeCan(melisKey: string) {
  return (cap: string): boolean =>
    (window as unknown as { MelisCan?: (k: string, c: string) => boolean }).MelisCan?.(melisKey, cap) ?? true
}

export const card: CSSProperties = {
  border: '1px solid var(--color-border)', background: 'var(--color-card)',
  borderRadius: 12, boxShadow: '0 1px 2px rgba(0,0,0,.04)',
}
export const input: CSSProperties = {
  height: 36, boxSizing: 'border-box', borderRadius: 8,
  border: '1px solid var(--color-input,var(--color-border))', background: 'var(--color-card)',
  color: 'var(--color-foreground)', padding: '0 12px', fontSize: 14, outline: 'none', width: '100%',
}
export const btnGhost: CSSProperties = {
  display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: 6, height: 36,
  padding: '0 12px', borderRadius: 8, border: '1px solid var(--color-border)',
  background: 'var(--color-card)', color: 'var(--color-foreground)', fontSize: 14, cursor: 'pointer',
}
export const btnPrimary: CSSProperties = {
  display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: 6, height: 36,
  padding: '0 16px', borderRadius: 8, border: 0, background: 'var(--color-primary,#e11d48)',
  color: 'var(--color-primary-foreground,#fff)', fontSize: 14, fontWeight: 600, cursor: 'pointer',
}
export const label: CSSProperties = { fontSize: 12, fontWeight: 600, color: 'var(--color-muted-foreground)' }

const svg = (path: ReactNode, size = 16): CSSProperties =>
  ({ width: size, height: size, flexShrink: 0 }) as CSSProperties
const S = ({ children, size = 16 }: { children: ReactNode; size?: number }) => (
  <svg style={svg(children, size)} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">{children}</svg>
)
export const IconPlus = ({ size = 16 }: { size?: number }) => <S size={size}><path d="M12 5v14M5 12h14" /></S>
export const IconTrash = ({ size = 16 }: { size?: number }) => <S size={size}><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" /></S>
export const IconChevron = ({ open, size = 16 }: { open: boolean; size?: number }) => (
  <svg style={{ width: size, height: size, flexShrink: 0, transform: open ? 'rotate(90deg)' : 'none', transition: 'transform .12s' }} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="m9 18 6-6-6-6" /></svg>
)
export const IconSearch = ({ size = 16 }: { size?: number }) => <S size={size}><circle cx="11" cy="11" r="8" /><path d="m21 21-4.3-4.3" /></S>
export const IconRefresh = ({ size = 16 }: { size?: number }) => <S size={size}><path d="M3 12a9 9 0 0 1 15-6.7L21 8M21 3v5h-5M21 12a9 9 0 0 1-15 6.7L3 16M3 21v-5h5" /></S>
export const IconFolder = ({ size = 16 }: { size?: number }) => <S size={size}><path d="M4 20a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5l2 3h7a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2Z" /></S>
export const IconGrip = ({ size = 16 }: { size?: number }) => (
  <svg style={{ width: size, height: size, flexShrink: 0 }} viewBox="0 0 24 24" fill="currentColor" stroke="none"><circle cx="9" cy="6" r="1.4" /><circle cx="15" cy="6" r="1.4" /><circle cx="9" cy="12" r="1.4" /><circle cx="15" cy="12" r="1.4" /><circle cx="9" cy="18" r="1.4" /><circle cx="15" cy="18" r="1.4" /></svg>
)
export const IconCalendar = ({ size = 16 }: { size?: number }) => <S size={size}><rect x="3" y="4" width="18" height="18" rx="2" /><path d="M16 2v4M8 2v4M3 10h18" /></S>

/* ── Localized date field ────────────────────────────────────────────────────
 * `<input type="date">` displays in the BROWSER locale (ignores the `lang` attribute in
 * Chromium), so a FR back-office on an EN browser shows mm/dd/yyyy. This field displays/edits
 * in the BO LANGUAGE format (fr → dd/mm/yyyy, else mm/dd/yyyy) while storing ISO `Y-m-d`, and
 * keeps a native calendar via a hidden <input type=date> + showPicker(). */
function isoToDisplay(iso: string, lang: 'fr' | 'en'): string {
  if (!iso) return ''
  const [y, m, d] = iso.split('-')
  if (!y || !m || !d) return ''
  return lang === 'fr' ? `${d}/${m}/${y}` : `${m}/${d}/${y}`
}
function displayToIso(text: string, lang: 'fr' | 'en'): string | null {
  const s = text.trim()
  if (s === '') return ''
  const m = s.match(/^(\d{1,2})[/\-.](\d{1,2})[/\-.](\d{4})$/)
  if (!m) return null
  const a = m[1].padStart(2, '0'), b = m[2].padStart(2, '0'), y = m[3]
  const [dd, mm] = lang === 'fr' ? [a, b] : [b, a]
  if (+mm < 1 || +mm > 12 || +dd < 1 || +dd > 31) return null
  return `${y}-${mm}-${dd}`
}
export function DateField({ value, onChange, lang }: { value: string; onChange: (iso: string) => void; lang: 'fr' | 'en' }) {
  const [text, setText] = useState(() => isoToDisplay(value, lang))
  const nativeRef = useRef<HTMLInputElement>(null)
  useEffect(() => { setText(isoToDisplay(value, lang)) }, [value, lang])
  const commit = () => {
    const iso = displayToIso(text, lang)
    if (iso === null) setText(isoToDisplay(value, lang)) // revert unparseable input
    else if (iso !== value) onChange(iso)
  }
  const openPicker = () => {
    const el = nativeRef.current as (HTMLInputElement & { showPicker?: () => void }) | null
    if (el?.showPicker) el.showPicker()
    else el?.focus()
  }
  return (
    <div style={{ position: 'relative' }}>
      <input value={text} onChange={e => setText(e.target.value)} onBlur={commit}
        onKeyDown={e => { if (e.key === 'Enter') (e.currentTarget as HTMLInputElement).blur() }}
        placeholder={lang === 'fr' ? 'jj/mm/aaaa' : 'mm/dd/yyyy'} inputMode="numeric"
        style={{ ...input, paddingRight: 34 }} />
      <button type="button" onClick={openPicker} aria-label="calendar" tabIndex={-1}
        style={{ position: 'absolute', right: 2, top: 2, width: 32, height: 32, display: 'inline-flex', alignItems: 'center', justifyContent: 'center', border: 0, background: 'transparent', color: 'var(--color-muted-foreground)', cursor: 'pointer' }}>
        <IconCalendar size={16} />
      </button>
      {/* Hidden native picker anchored under the calendar button. */}
      <input ref={nativeRef} type="date" value={value || ''} onChange={e => onChange(e.target.value)}
        tabIndex={-1} aria-hidden
        style={{ position: 'absolute', right: 6, bottom: 0, width: 1, height: 1, opacity: 0, pointerEvents: 'none' }} />
    </div>
  )
}

export function StatusDot({ active }: { active: boolean }) {
  return <span style={{ width: 8, height: 8, borderRadius: 999, flexShrink: 0, background: active ? '#22c55e' : '#ef4444' }} />
}

export function Toggle({ checked, onChange }: { checked: boolean; onChange: (v: boolean) => void }) {
  return (
    <button type="button" onClick={() => onChange(!checked)} aria-pressed={checked}
      style={{ width: 42, height: 24, borderRadius: 999, border: 0, cursor: 'pointer', padding: 2,
        background: checked ? '#22c55e' : 'color-mix(in srgb, var(--color-muted,#888) 40%, transparent)',
        display: 'inline-flex', alignItems: 'center', transition: 'background .15s' }}>
      <span style={{ width: 20, height: 20, borderRadius: 999, background: '#fff', boxShadow: '0 1px 2px rgba(0,0,0,.3)', transform: checked ? 'translateX(18px)' : 'none', transition: 'transform .15s' }} />
    </button>
  )
}

export function Chip({ children }: { children: ReactNode }) {
  return <span style={{ fontSize: 10, fontWeight: 600, padding: '1px 6px', borderRadius: 4, background: 'color-mix(in srgb, var(--color-muted,#888) 18%, transparent)', color: 'var(--color-muted-foreground)' }}>{children}</span>
}

/**
 * Fire a host back-office toast from the brick. MelisCore's <Notifications> listens for
 * window messages `{ __melisNotif: true, kind, title, message }` (same bridge used by legacy
 * iframes) — success toasts auto-dismiss, errors stay. Keeps notifications consistent BO-wide.
 */
export function melisNotify(kind: 'success' | 'error', title: string, message = '') {
  try { window.postMessage({ __melisNotif: true, kind, title, message }, '*') } catch { /* noop */ }
}

const btnDanger: CSSProperties = {
  display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: 6, height: 36,
  padding: '0 16px', borderRadius: 8, border: 0, background: '#dc2626', color: '#fff',
  fontSize: 14, fontWeight: 600, cursor: 'pointer',
}
const overlay: CSSProperties = {
  position: 'fixed', inset: 0, zIndex: 9999, background: 'rgba(0,0,0,.45)',
  display: 'flex', alignItems: 'center', justifyContent: 'center', padding: 16,
}

interface ConfirmOpts { title?: string; message: string; confirmLabel?: string; cancelLabel?: string; danger?: boolean }

function ConfirmModal({ title, message, confirmLabel, cancelLabel, danger, onConfirm, onCancel }:
  ConfirmOpts & { onConfirm: () => void; onCancel: () => void }) {
  useEffect(() => {
    const onKey = (e: KeyboardEvent) => { if (e.key === 'Escape') onCancel(); else if (e.key === 'Enter') onConfirm() }
    window.addEventListener('keydown', onKey)
    return () => window.removeEventListener('keydown', onKey)
  }, [onConfirm, onCancel])
  return (
    <div style={overlay} onClick={onCancel}>
      <div style={{ ...card, width: 'min(420px, 92vw)', padding: '20px 22px' }} onClick={e => e.stopPropagation()}>
        {title ? <div style={{ fontSize: 16, fontWeight: 700, marginBottom: 8 }}>{title}</div> : null}
        <div style={{ fontSize: 14, color: 'var(--color-muted-foreground)', marginBottom: 20, lineHeight: 1.5 }}>{message}</div>
        <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
          <button style={btnGhost} onClick={onCancel}>{cancelLabel ?? 'Annuler'}</button>
          <button style={danger ? btnDanger : btnPrimary} onClick={onConfirm} autoFocus>{confirmLabel ?? 'OK'}</button>
        </div>
      </div>
    </div>
  )
}

/**
 * Promise-based confirmation modal (replaces window.confirm). Usage:
 *   const { ask, el } = useConfirm()
 *   … if (!(await ask({ message, danger: true }))) return
 *   return (<> … {el} </>)   // render `el` once in the component tree
 */
export function useConfirm() {
  const [opts, setOpts] = useState<ConfirmOpts | null>(null)
  const resolver = useRef<((v: boolean) => void) | null>(null)
  const ask = (o: ConfirmOpts) => new Promise<boolean>(resolve => { resolver.current = resolve; setOpts(o) })
  const close = (ok: boolean) => { setOpts(null); const r = resolver.current; resolver.current = null; r?.(ok) }
  const el = opts ? <ConfirmModal {...opts} onConfirm={() => close(true)} onCancel={() => close(false)} /> : null
  return { ask, el }
}

/**
 * Language flag — reuses MelisCore's flag PNGs (served at /MelisCore/assets/images/lang/<short>.png,
 * short = language code, e.g. en_EN → "en"). Emoji flags don't render on Windows, hence images.
 * Missing flag → the <img> hides itself, leaving just the surrounding label.
 */
export function LangFlag({ locale, size = 16 }: { locale: string; size?: number }) {
  const short = (locale || '').split('_')[0].toLowerCase()
  if (!short) return null
  return (
    <img src={`/MelisCore/assets/images/lang/${short}.png`} alt="" width={size} height={Math.round(size * 2 / 3)}
      style={{ display: 'inline-block', borderRadius: 2, objectFit: 'cover', boxShadow: '0 0 0 1px rgba(0,0,0,.10)', flexShrink: 0 }}
      onError={(e) => { (e.currentTarget as HTMLImageElement).style.display = 'none' }} />
  )
}
