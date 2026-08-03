import { useEffect, useRef, useState, type CSSProperties } from 'react'
import {
  fetchCategory, saveCategory, fetchCategoryMedia, uploadMedia, deleteMedia,
  type Lang, type Site, type Translation, type SavePayload, type MediaItem,
} from './category-api'
import { useT, currentLang } from './i18n'
import { card, input, label, btnPrimary, btnGhost, makeCan, Toggle, LangFlag, DateField, melisNotify, useConfirm, IconPlus, IconTrash, IconFolder, IconArrowLeft } from './ui'

// Droits avancés — partie « l'Édition » (cf. config/react.capabilities.php).
const can = makeCan('melis_cms_category_v2_tools_section')

export interface EditTarget { id: number | null; parentId: number; parentName?: string }

interface Props {
  target: EditTarget | null
  langs: Lang[]
  sites: Site[]
  onSaved: (id: number) => void
  onCancel: () => void
  narrow?: boolean
  onBack?: () => void
}

type TransMap = Record<number, Translation>

export default function CategoryEditor({ target, langs, sites, onSaved, onCancel, narrow = false, onBack }: Props) {
  const t = useT()
  const canProps = can('edition.properties')
  const canMedia = can('edition.media')
  const [loading, setLoading] = useState(false)
  const [saving, setSaving] = useState(false)
  const [error, setError] = useState('')
  const [tab, setTab] = useState<'props' | 'media'>('props')
  const [activeLang, setActiveLang] = useState<number>(langs[0]?.id ?? 1)

  const [trans, setTrans] = useState<TransMap>({})
  const [status, setStatus] = useState(1)
  const [dateStart, setDateStart] = useState('')
  const [dateEnd, setDateEnd] = useState('')
  const [selectedSites, setSelectedSites] = useState<number[]>([])

  const key = target ? `${target.id ?? 'new'}:${target.parentId}` : 'none'

  useEffect(() => {
    setError(''); setTab(canProps ? 'props' : 'media'); setActiveLang(langs[0]?.id ?? 1)
    if (!target) return
    if (target.id == null) {
      setTrans({}); setStatus(1); setDateStart(''); setDateEnd(''); setSelectedSites([])
      return
    }
    setLoading(true)
    fetchCategory(target.id)
      .then(d => {
        setTrans(d.translations || {})
        setStatus(d.status)
        setDateStart(d.dateStart || '')
        setDateEnd(d.dateEnd || '')
        setSelectedSites(d.sites || [])
      })
      .catch(e => setError(e.message || t('err_generic')))
      .finally(() => setLoading(false))
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [key])

  if (!target) {
    return (
      <div style={{ ...card, height: '100%', display: 'flex', alignItems: 'center', justifyContent: 'center',
        color: 'var(--color-muted-foreground)', fontSize: 14, textAlign: 'center', padding: 24 }}>
        {t('empty_editor')}
      </div>
    )
  }

  // Droit d'édition : sans lui, le panneau ne se charge pas pour une catégorie EXISTANTE.
  // La création reste permise (le droit `tree.create` a ouvert ce panneau en mode « nouveau »).
  if (target.id != null && !can('edition')) {
    return (
      <div style={{ ...card, height: '100%', display: 'flex', alignItems: 'center', justifyContent: 'center',
        color: 'var(--color-muted-foreground)', fontSize: 14, textAlign: 'center', padding: 24 }}>
        {t('no_edit_access')}
      </div>
    )
  }

  const setTransField = (langId: number, field: keyof Translation, value: string) =>
    setTrans(prev => ({ ...prev, [langId]: { name: '', description: '', ...prev[langId], [field]: value } }))

  const toggleSite = (id: number) =>
    setSelectedSites(prev => prev.includes(id) ? prev.filter(s => s !== id) : [...prev, id])

  const doSave = async () => {
    setError('')
    const hasName = Object.values(trans).some(x => (x?.name || '').trim() !== '')
    if (!hasName) { setError(t('err_name') || 'Name required'); setTab('props'); return }
    if (selectedSites.length === 0) { setError(t('err_site') || 'Site required'); return }
    if (dateStart && dateEnd && dateStart > dateEnd) { setError(t('err_dates') || 'Invalid dates'); return }

    const payload: SavePayload = {
      id: target.id ?? undefined,
      parentId: target.parentId,
      status,
      dateStart,
      dateEnd,
      sites: selectedSites,
      translations: trans,
    }
    setSaving(true)
    try {
      const wasEdit = target.id != null
      const { id } = await saveCategory(payload)
      melisNotify('success', wasEdit ? t('notif_saved') : t('notif_created'))
      onSaved(id)
    } catch (e: any) {
      const msg = e?.message || t('err_generic')
      setError(msg)
      melisNotify('error', t('notif_error'), msg)
    } finally {
      setSaving(false)
    }
  }

  const isEdit = target.id != null
  const title = isEdit
    ? t('edit_title', { name: trans[activeLang]?.name || Object.values(trans).find(x => x.name)?.name || '#' + target.id })
    : t('new_title')
  const context = target.parentId === -1
    ? t('under_root')
    : t('under_parent', { name: target.parentName || '#' + target.parentId })

  return (
    <div style={{ ...card, height: '100%', display: 'flex', flexDirection: 'column', minHeight: 0, overflow: 'hidden' }}>
      {/* header */}
      <div style={{ display: 'flex', flexDirection: narrow ? 'column' : 'row', alignItems: narrow ? 'stretch' : 'center',
        gap: 12, padding: '14px 16px', borderBottom: '1px solid var(--color-border)' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 8, minWidth: 0, flex: 1 }}>
          {narrow && onBack ? (
            <button onClick={onBack} title={t('back')} aria-label={t('back')} style={backBtn}><IconArrowLeft size={16} /></button>
          ) : null}
          <div style={{ flex: 1, minWidth: 0 }}>
            <div style={{ fontSize: 16, fontWeight: 700, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{title}</div>
            <div style={{ fontSize: 12, color: 'var(--color-muted-foreground)' }}>{context}</div>
          </div>
        </div>
        <div style={{ display: 'flex', gap: 8 }}>
          <button style={{ ...btnGhost, ...(narrow ? { flex: 1 } : null) }} onClick={onCancel} disabled={saving}>{t('cancel')}</button>
          <button style={{ ...btnPrimary, opacity: saving ? 0.6 : 1, ...(narrow ? { flex: 1 } : null) }} onClick={doSave} disabled={saving}>
            {saving ? t('saving') : t('save')}
          </button>
        </div>
      </div>

      {/* tabs — gated by the `edition.properties` / `edition.media` capabilities */}
      <div style={{ display: 'flex', gap: 4, padding: '10px 16px 0' }}>
        {canProps && <TabBtn active={tab === 'props'} onClick={() => setTab('props')}>{t('tab_props')}</TabBtn>}
        {canMedia && <TabBtn active={tab === 'media'} onClick={() => setTab('media')}>{t('tab_media')}</TabBtn>}
      </div>

      <div style={{ flex: 1, overflow: 'auto', padding: 16 }}>
        {error ? (
          <div style={{ marginBottom: 14, padding: '10px 12px', borderRadius: 8, fontSize: 13,
            background: 'color-mix(in srgb, #ef4444 12%, transparent)', color: '#b91c1c' }}>{error}</div>
        ) : null}

        {loading ? (
          <div style={{ color: 'var(--color-muted-foreground)', fontSize: 14 }}>{t('loading')}</div>
        ) : (!canProps && !canMedia) ? (
          <div style={{ color: 'var(--color-muted-foreground)', fontSize: 14 }}>{t('no_edit_access')}</div>
        ) : (tab === 'media' && canMedia) ? (
          <MediaTab catId={target.id} t={t} narrow={narrow} />
        ) : canProps ? (
          <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : 'minmax(0,1fr) 300px', gap: 20 }}>
            {/* left: translations */}
            <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
              {/* Two languages per row (dynamic count → grid wraps automatically). */}
              <div style={{ display: 'grid', gridTemplateColumns: 'repeat(2, minmax(0, 1fr))', gap: 6 }}>
                {langs.map(l => {
                  const filled = (trans[l.id]?.name || '').trim() !== ''
                  return (
                    <button key={l.id} onClick={() => setActiveLang(l.id)}
                      style={{ ...langTab, width: '100%', ...(activeLang === l.id ? langTabActive : {}) }}>
                      <LangFlag locale={l.locale} size={15} />
                      <span style={{ flex: 1, minWidth: 0, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap', textAlign: 'left' }}>{l.name}</span>
                      <span title={filled ? undefined : t('no_name')}
                        style={{ width: 6, height: 6, borderRadius: 999, flexShrink: 0, background: filled ? '#22c55e' : 'var(--color-border)' }} />
                    </button>
                  )
                })}
              </div>
              <div>
                <div style={label}>{t('f_name')}</div>
                <input style={{ ...input, marginTop: 6 }} value={trans[activeLang]?.name || ''}
                  onChange={e => setTransField(activeLang, 'name', e.target.value)} placeholder={t('f_name_ph')} />
              </div>
              <div>
                <div style={label}>{t('f_desc')}</div>
                <textarea value={trans[activeLang]?.description || ''}
                  onChange={e => setTransField(activeLang, 'description', e.target.value)}
                  style={{ ...input, height: 120, padding: 12, resize: 'vertical', marginTop: 6, fontFamily: 'inherit' }} />
              </div>
            </div>

            {/* right: status (top) + validity + sites */}
            <div style={{ display: 'flex', flexDirection: 'column', gap: 18 }}>
              <div>
                <div style={label}>{t('f_status')}</div>
                <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginTop: 8 }}>
                  <Toggle checked={status === 1} onChange={v => setStatus(v ? 1 : 0)} />
                  <span style={{ fontSize: 14 }}>{status === 1 ? t('active') : t('inactive')}</span>
                </div>
              </div>
              <div>
                <div style={label}>{t('f_dates')}</div>
                <div style={{ display: 'flex', flexDirection: narrow ? 'column' : 'row', gap: 8, marginTop: 6 }}>
                  <div style={{ flex: 1 }}>
                    <div style={{ fontSize: 11, color: 'var(--color-muted-foreground)', marginBottom: 4 }}>{t('f_date_start')}</div>
                    <DateField value={dateStart} onChange={setDateStart} lang={currentLang()} />
                  </div>
                  <div style={{ flex: 1 }}>
                    <div style={{ fontSize: 11, color: 'var(--color-muted-foreground)', marginBottom: 4 }}>{t('f_date_end')}</div>
                    <DateField value={dateEnd} onChange={setDateEnd} lang={currentLang()} />
                  </div>
                </div>
              </div>
              <div>
                <div style={label}>{t('f_sites')}</div>
                <div style={{ display: 'flex', flexDirection: 'column', gap: 6, marginTop: 8 }}>
                  {sites.map(s => (
                    <label key={s.id} style={{ display: 'flex', alignItems: 'center', gap: 8, fontSize: 14, cursor: 'pointer' }}>
                      <input type="checkbox" checked={selectedSites.includes(s.id)} onChange={() => toggleSite(s.id)} />
                      {s.name}
                    </label>
                  ))}
                </div>
              </div>
            </div>
          </div>
        ) : null}
      </div>
    </div>
  )
}

const langTab: CSSProperties = {
  display: 'inline-flex', alignItems: 'center', gap: 6, height: 30, padding: '0 12px', borderRadius: 6,
  border: '1px solid var(--color-border)', background: 'transparent', color: 'var(--color-muted-foreground)',
  fontSize: 13, cursor: 'pointer',
}
const langTabActive: CSSProperties = { background: 'var(--color-card)', color: 'var(--color-foreground)', borderColor: 'var(--color-primary,#e11d48)' }
const backBtn: CSSProperties = {
  display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 36, height: 36, flexShrink: 0,
  borderRadius: 8, border: '1px solid var(--color-border)', background: 'var(--color-card)',
  color: 'var(--color-foreground)', cursor: 'pointer',
}

type T = (k: string, v?: Record<string, string | number>) => string

function MediaTab({ catId, t, narrow }: { catId: number | null; t: T; narrow: boolean }) {
  const [images, setImages] = useState<MediaItem[]>([])
  const [files, setFiles] = useState<MediaItem[]>([])
  const [loading, setLoading] = useState(false)
  const [busy, setBusy] = useState(false)
  const { ask, el: confirmEl } = useConfirm()

  useEffect(() => {
    if (catId == null) return
    setLoading(true)
    fetchCategoryMedia(catId).then(d => { setImages(d.images); setFiles(d.files) })
      .catch(() => { /* ignore */ }).finally(() => setLoading(false))
  }, [catId])

  if (catId == null) {
    return <div style={{ padding: 24, textAlign: 'center', color: 'var(--color-muted-foreground)', fontSize: 14 }}>{t('media_save_first')}</div>
  }

  const upload = async (type: 'image' | 'file', file: File) => {
    setBusy(true)
    try {
      const item = await uploadMedia(catId, type, file)
      if (type === 'image') setImages(p => [...p, item]); else setFiles(p => [...p, item])
      melisNotify('success', t('media_uploaded'))
    } catch (e: any) { melisNotify('error', t('notif_error'), e?.message) }
    finally { setBusy(false) }
  }
  const del = async (type: 'image' | 'file', item: MediaItem) => {
    const ok = await ask({ title: t('del'), message: t('media_del_confirm'), confirmLabel: t('del'), cancelLabel: t('cancel'), danger: true })
    if (!ok) return
    try {
      await deleteMedia(item.id)
      if (type === 'image') setImages(p => p.filter(x => x.id !== item.id)); else setFiles(p => p.filter(x => x.id !== item.id))
      melisNotify('success', t('media_deleted'))
    } catch (e: any) { melisNotify('error', t('notif_error'), e?.message) }
  }

  if (loading) return <div style={{ color: 'var(--color-muted-foreground)', fontSize: 14 }}>{t('loading')}</div>

  return (
    <>
    <div style={{ display: 'grid', gridTemplateColumns: narrow ? '1fr' : 'repeat(2, minmax(0,1fr))', gap: 20, opacity: busy ? 0.6 : 1, pointerEvents: busy ? 'none' : 'auto' }}>
      <MediaColumn title={t('media_images')} addLabel={t('media_add_image')} accept="image/*" onPick={f => upload('image', f)}>
        {images.length === 0 ? (
          <div style={emptyStyle}>{t('media_empty_img')}</div>
        ) : (
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(84px,1fr))', gap: 8 }}>
            {images.map(im => (
              <div key={im.id} style={{ position: 'relative', aspectRatio: '1 / 1', borderRadius: 8, overflow: 'hidden', border: '1px solid var(--color-border)' }}>
                <img src={im.path} alt={im.name} title={im.name} style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }} />
                <button onClick={() => del('image', im)} title={t('del')} style={delOverlay}><IconTrash size={13} /></button>
              </div>
            ))}
          </div>
        )}
      </MediaColumn>
      <MediaColumn title={t('media_files')} addLabel={t('media_add_file')} accept="" onPick={f => upload('file', f)}>
        {files.length === 0 ? (
          <div style={emptyStyle}>{t('media_empty_file')}</div>
        ) : (
          <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
            {files.map(f => (
              <div key={f.id} style={fileRow}>
                <span style={{ color: 'var(--color-muted-foreground)' }}><IconFolder size={16} /></span>
                <a href={f.path} target="_blank" rel="noreferrer"
                  style={{ flex: 1, minWidth: 0, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap', color: 'var(--color-foreground)', textDecoration: 'none', fontSize: 13 }}>{f.name}</a>
                <button onClick={() => del('file', f)} title={t('del')} style={fileDelBtn}><IconTrash size={14} /></button>
              </div>
            ))}
          </div>
        )}
      </MediaColumn>
    </div>
    {confirmEl}
    </>
  )
}

function MediaColumn({ title, addLabel, accept, onPick, children }:
  { title: string; addLabel: string; accept: string; onPick: (f: File) => void; children: any }) {
  const ref = useRef<HTMLInputElement>(null)
  return (
    <div style={{ ...card, padding: 12, display: 'flex', flexDirection: 'column', gap: 10 }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 8 }}>
        <div style={{ fontWeight: 700, fontSize: 14 }}>{title}</div>
        <button onClick={() => ref.current?.click()} style={{ ...btnGhost, height: 30, padding: '0 10px', fontSize: 13 }}>
          <IconPlus size={14} />{addLabel}
        </button>
        <input ref={ref} type="file" accept={accept || undefined} style={{ display: 'none' }}
          onChange={e => { const f = e.target.files?.[0]; if (f) onPick(f); e.currentTarget.value = '' }} />
      </div>
      {children}
    </div>
  )
}

const emptyStyle: CSSProperties = { padding: '24px 8px', textAlign: 'center', fontSize: 13, color: 'var(--color-muted-foreground)' }
const delOverlay: CSSProperties = {
  position: 'absolute', top: 4, right: 4, width: 24, height: 24, borderRadius: 6, border: 0, cursor: 'pointer',
  display: 'inline-flex', alignItems: 'center', justifyContent: 'center', background: 'rgba(0,0,0,.55)', color: '#fff',
}
const fileRow: CSSProperties = {
  display: 'flex', alignItems: 'center', gap: 8, padding: '6px 8px', borderRadius: 8, border: '1px solid var(--color-border)',
}
const fileDelBtn: CSSProperties = {
  display: 'inline-flex', alignItems: 'center', justifyContent: 'center', width: 26, height: 26, borderRadius: 6,
  border: 0, background: 'transparent', color: 'var(--color-muted-foreground)', cursor: 'pointer', flexShrink: 0,
}

function TabBtn({ active, disabled, title, onClick, children }:
  { active: boolean; disabled?: boolean; title?: string; onClick?: () => void; children: any }) {
  return (
    <button onClick={onClick} disabled={disabled} title={title}
      style={{ height: 34, padding: '0 14px', borderRadius: '8px 8px 0 0', border: 0, cursor: disabled ? 'not-allowed' : 'pointer',
        fontSize: 13, fontWeight: 600, opacity: disabled ? 0.5 : 1,
        background: active ? 'color-mix(in srgb, var(--color-primary,#e11d48) 12%, transparent)' : 'transparent',
        color: active ? 'var(--color-foreground)' : 'var(--color-muted-foreground)',
        borderBottom: active ? '2px solid var(--color-primary,#e11d48)' : '2px solid transparent' }}>
      {children}
    </button>
  )
}
