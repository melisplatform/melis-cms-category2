/**
 * Typed client for the Categories JSON API — owned by MelisCmsCategory2 itself (module brick),
 * backend: MelisCmsCategoryReactApiController. Base: /melis/MelisCmsCategory2/react-api.
 */

const XHR = { 'X-Requested-With': 'XMLHttpRequest' } as const
const BASE = '/melis/MelisCmsCategory2/react-api'

export interface Lang { id: number; locale: string; name: string }
export interface Site { id: number; name: string }

export interface TreeNode {
  id: number
  parentId: number
  order: number
  status: number
  name: string
  nameLangId: number
  isFallback: boolean
  sites: number[]
  children: TreeNode[]
}

export interface Translation { name: string; description: string }

export interface CategoryDetail {
  id: number
  parentId: number
  status: number
  dateStart: string | null
  dateEnd: string | null
  sites: number[]
  translations: Record<number, Translation>
}

export interface SavePayload {
  id?: number | null
  parentId: number
  status: number
  dateStart: string
  dateEnd: string
  sites: number[]
  translations: Record<number, Translation>
}

async function apiFetch<T>(url: string, init?: RequestInit): Promise<T> {
  const res = await fetch(url, {
    ...init,
    headers: { ...XHR, ...(init?.body ? { 'Content-Type': 'application/json' } : {}), ...(init?.headers || {}) },
    credentials: 'include',
  })
  const data = (await res.json().catch(() => ({ success: false, error: `HTTP ${res.status}` }))) as
    { success: boolean; data?: T; error?: string }
  if (!res.ok || !data.success) throw new Error(data.error ?? `HTTP ${res.status}`)
  return data.data as T
}

export const fetchLangs = () => apiFetch<{ langs: Lang[] }>(`${BASE}/langs`).then(d => d.langs)
export const fetchSites = () => apiFetch<{ sites: Site[] }>(`${BASE}/sites`).then(d => d.sites)
export const fetchTree = (langId: number) =>
  apiFetch<{ langId: number; nodes: TreeNode[] }>(`${BASE}/tree?lang=${langId}`).then(d => d.nodes)
export const fetchCategory = (id: number) => apiFetch<CategoryDetail>(`${BASE}/category/${id}`)
export const saveCategory = (payload: SavePayload) =>
  apiFetch<{ id: number }>(`${BASE}/save`, { method: 'POST', body: JSON.stringify(payload) })
export const deleteCategory = (id: number) =>
  apiFetch<null>(`${BASE}/delete/${id}`, { method: 'DELETE' })
export const reorderCategories = (parentId: number, orderedIds: number[]) =>
  apiFetch<null>(`${BASE}/reorder`, { method: 'POST', body: JSON.stringify({ parentId, orderedIds }) })

// ── Media (images / files) ──
export interface MediaItem { id: number; path: string; name: string; type?: string }

export const fetchCategoryMedia = (id: number) =>
  apiFetch<{ images: MediaItem[]; files: MediaItem[] }>(`${BASE}/category/${id}/media`)
export const deleteMedia = (id: number) =>
  apiFetch<null>(`${BASE}/media/delete/${id}`, { method: 'DELETE' })

/** Multipart upload (own fetch — the browser must set the multipart boundary itself). */
export async function uploadMedia(catId: number, type: 'image' | 'file', file: File): Promise<MediaItem> {
  const fd = new FormData()
  fd.append('catId', String(catId)); fd.append('type', type); fd.append('file', file)
  const res = await fetch(`${BASE}/media/upload`, {
    method: 'POST', headers: { ...XHR }, credentials: 'include', body: fd,
  })
  const data = (await res.json().catch(() => ({ success: false, error: `HTTP ${res.status}` }))) as
    { success: boolean; data?: MediaItem; error?: string }
  if (!res.ok || !data.success) throw new Error(data.error ?? `HTTP ${res.status}`)
  return data.data as MediaItem
}
