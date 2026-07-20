<?php

namespace MelisCmsCategory2\Controller;

use Laminas\Http\PhpEnvironment\Response as HttpResponse;
use MelisCore\Controller\MelisAbstractActionController;

/**
 * JSON CRUD API for the native React "Categories" tool (MelisCmsCategory2).
 *
 * Lives INSIDE the module (modularity rule) — the UI is a React BRICK; this is its own
 * API layer, not melis-react-api. Reuses the module's tables directly (raw SQL, same
 * approach as the MelisCms react-api gabarit) — business logic stays server-side.
 *
 * The Category tool is a HIERARCHICAL TREE (cat2_father_cat_id, -1 = root) with per-language
 * translations (melis_cms_category2_trans), site associations (melis_cms_category2_sites)
 * and media (melis_cms_category2_media, phase 2).
 *
 * Routes (config/react-api.php):
 *   GET    /melis/MelisCmsCategory2/react-api/tree?lang=&site=   → full category tree
 *   GET    /melis/MelisCmsCategory2/react-api/langs              → CMS languages
 *   GET    /melis/MelisCmsCategory2/react-api/sites              → sites (filter + form)
 *   GET    /melis/MelisCmsCategory2/react-api/category/:id       → one category (edit)
 *   POST   /melis/MelisCmsCategory2/react-api/save               → create / update
 *   DELETE /melis/MelisCmsCategory2/react-api/delete/:id         → delete (blocked if children)
 *   POST   /melis/MelisCmsCategory2/react-api/reorder            → resequence siblings of a parent
 */
class MelisCmsCategoryReactApiController extends MelisAbstractActionController
{
    /** melisKey of the RIGHTS-BEARING menu node — the rights guard AND the capability key.
     *  MUST stay in sync with config/react.capabilities.php: denyUnlessCan() resolves capabilities
     *  through this constant, so a mismatch makes every server-side capability check silently
     *  default-allow. This is the key React grants and the rights XML stores. NOT
     *  `melis_cms_categories_v2` — that is the `type` target, kept as the renderable ZONE key
     *  (iframe), and it is not granted on its own, so guarding on it would 403 every request. */
    private const MELIS_KEY = 'melis_cms_category_v2_tools_section';

    private const ROOT = -1;

    // ─── GET /tree ────────────────────────────────────────────────────────────────

    public function treeAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }

        try {
            $langId = (int) $this->params()->fromQuery('lang', 0) ?: null;
            $db     = $this->db();

            $cats = iterator_to_array($db->query(
                'SELECT cat2_id, cat2_father_cat_id, cat2_order, cat2_status
                 FROM melis_cms_category2 ORDER BY cat2_father_cat_id ASC, cat2_order ASC, cat2_id ASC',
                []
            ));
            $transRows = iterator_to_array($db->query(
                'SELECT catt2_category_id, catt2_lang_id, catt2_name FROM melis_cms_category2_trans',
                []
            ));
            $siteRows = iterator_to_array($db->query(
                'SELECT cats2_cat2_id, cats2_site_id FROM melis_cms_category2_sites',
                []
            ));

            // group by category id
            $transByCat = [];
            foreach ($transRows as $r) {
                $transByCat[(int) $r['catt2_category_id']][(int) $r['catt2_lang_id']] = (string) $r['catt2_name'];
            }
            $sitesByCat = [];
            foreach ($siteRows as $r) {
                $sitesByCat[(int) $r['cats2_cat2_id']][] = (int) $r['cats2_site_id'];
            }
            $childrenByFather = [];
            foreach ($cats as $c) {
                $childrenByFather[(int) $c['cat2_father_cat_id']][] = $c;
            }

            $nodes = $this->buildTree(self::ROOT, $childrenByFather, $transByCat, $sitesByCat, $langId);

            return $this->jsonResponse(['success' => true, 'data' => ['langId' => $langId, 'nodes' => $nodes]]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    /** Recursively assemble the tree for a given father id. */
    private function buildTree(int $fatherId, array $childrenByFather, array $transByCat, array $sitesByCat, ?int $langId): array
    {
        $out = [];
        foreach ($childrenByFather[$fatherId] ?? [] as $c) {
            $id = (int) $c['cat2_id'];
            [$name, $nameLangId, $isFallback] = $this->resolveName($transByCat[$id] ?? [], $langId);
            $out[] = [
                'id'         => $id,
                'parentId'   => (int) $c['cat2_father_cat_id'],
                'order'      => (int) $c['cat2_order'],
                'status'     => (int) $c['cat2_status'],
                'name'       => $name,
                'nameLangId' => $nameLangId,
                'isFallback' => $isFallback,
                'sites'      => $sitesByCat[$id] ?? [],
                'children'   => $this->buildTree($id, $childrenByFather, $transByCat, $sitesByCat, $langId),
            ];
        }
        return $out;
    }

    /**
     * Resolve the display name for a category: prefer the requested language; fall back to the
     * first non-empty translation (flagged, so the UI can show "(lang)"). Returns [name, langId, isFallback].
     */
    private function resolveName(array $transByLang, ?int $langId): array
    {
        if ($langId && isset($transByLang[$langId]) && trim($transByLang[$langId]) !== '') {
            return [$transByLang[$langId], $langId, false];
        }
        foreach ($transByLang as $lid => $name) {
            if (trim((string) $name) !== '') {
                return [(string) $name, (int) $lid, $langId !== null];
            }
        }
        return ['', $langId ?? 0, false];
    }

    // ─── GET /langs ───────────────────────────────────────────────────────────────

    public function langsAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $rows = iterator_to_array($this->db()->query(
                'SELECT lang_cms_id, lang_cms_locale, lang_cms_name FROM melis_cms_lang ORDER BY lang_cms_id ASC',
                []
            ));
            $langs = array_map(fn ($r) => [
                'id'     => (int) $r['lang_cms_id'],
                'locale' => (string) $r['lang_cms_locale'],
                'name'   => (string) $r['lang_cms_name'],
            ], $rows);
            return $this->jsonResponse(['success' => true, 'data' => ['langs' => $langs]]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── GET /sites ───────────────────────────────────────────────────────────────

    public function sitesAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        try {
            $rows = iterator_to_array($this->db()->query(
                'SELECT site_id, site_name, site_label FROM melis_cms_site ORDER BY site_label ASC, site_name ASC',
                []
            ));
            $sites = array_map(fn ($r) => [
                'id'   => (int) $r['site_id'],
                'name' => trim((string) $r['site_label']) !== '' ? (string) $r['site_label'] : (string) $r['site_name'],
            ], $rows);
            return $this->jsonResponse(['success' => true, 'data' => ['sites' => $sites]]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── GET /category/:id ────────────────────────────────────────────────────────

    public function getAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }

        try {
            $db  = $this->db();
            $rows = iterator_to_array($db->query(
                'SELECT cat2_id, cat2_father_cat_id, cat2_status, cat2_date_valid_start, cat2_date_valid_end
                 FROM melis_cms_category2 WHERE cat2_id = ?',
                [$id]
            ));
            if (!$rows) { return $this->jsonResponse(['success' => false, 'error' => 'Not found'], 404); }
            $c = (array) $rows[0];

            $translations = [];
            foreach (iterator_to_array($db->query(
                'SELECT catt2_lang_id, catt2_name, catt2_description FROM melis_cms_category2_trans WHERE catt2_category_id = ?',
                [$id]
            )) as $t) {
                $translations[(int) $t['catt2_lang_id']] = [
                    'name'        => (string) $t['catt2_name'],
                    'description' => (string) ($t['catt2_description'] ?? ''),
                ];
            }

            $sites = array_map(
                fn ($r) => (int) $r['cats2_site_id'],
                iterator_to_array($db->query('SELECT cats2_site_id FROM melis_cms_category2_sites WHERE cats2_cat2_id = ?', [$id]))
            );

            return $this->jsonResponse(['success' => true, 'data' => [
                'id'           => $id,
                'parentId'     => (int) $c['cat2_father_cat_id'],
                'status'       => (int) $c['cat2_status'],
                'dateStart'    => $this->toDate($c['cat2_date_valid_start']),
                'dateEnd'      => $this->toDate($c['cat2_date_valid_end']),
                'sites'        => $sites,
                'translations' => (object) $translations,
            ]]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── POST /save ───────────────────────────────────────────────────────────────

    public function saveAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }

        try {
            $body     = json_decode($this->getRequest()->getContent(), true) ?? [];
            $id       = isset($body['id']) && $body['id'] ? (int) $body['id'] : null;
            $parentId = isset($body['parentId']) ? (int) $body['parentId'] : self::ROOT;
            $status   = !empty($body['status']) ? 1 : 0;
            $dateStart = trim((string) ($body['dateStart'] ?? ''));
            $dateEnd   = trim((string) ($body['dateEnd'] ?? ''));
            $sites     = array_values(array_unique(array_map('intval', (array) ($body['sites'] ?? []))));
            $trans     = (array) ($body['translations'] ?? []);

            // ── Validation (parity with the legacy saveCategoryAction) ──
            $hasName = false;
            foreach ($trans as $t) {
                if (trim((string) ($t['name'] ?? '')) !== '') { $hasName = true; break; }
            }
            if (!$hasName) {
                return $this->jsonResponse(['success' => false, 'error' => 'Au moins un nom de catégorie (dans une langue) est obligatoire.'], 400);
            }
            if (!$sites) {
                return $this->jsonResponse(['success' => false, 'error' => 'Au moins un site doit être sélectionné.'], 400);
            }
            if ($dateStart !== '' && $dateEnd !== '' && strtotime($dateStart) > strtotime($dateEnd)) {
                return $this->jsonResponse(['success' => false, 'error' => 'La date de début doit précéder la date de fin.'], 400);
            }

            $db     = $this->db();
            $userId = $this->currentUserId();
            $startSql = $dateStart !== '' ? date('Y-m-d H:i:s', strtotime($dateStart)) : null;
            $endSql   = $dateEnd !== ''   ? date('Y-m-d H:i:s', strtotime($dateEnd))   : null;

            if ($id) {
                if (!iterator_to_array($db->query('SELECT cat2_id FROM melis_cms_category2 WHERE cat2_id = ?', [$id]))) {
                    return $this->jsonResponse(['success' => false, 'error' => 'Not found'], 404);
                }
                $db->query(
                    'UPDATE melis_cms_category2 SET cat2_status = ?, cat2_date_valid_start = ?, cat2_date_valid_end = ?,
                     cat2_date_edit = ?, cat2_user_id_edit = ? WHERE cat2_id = ?',
                    [$status, $startSql, $endSql, date('Y-m-d H:i:s'), $userId, $id]
                );
            } else {
                $maxRow = iterator_to_array($db->query(
                    'SELECT COALESCE(MAX(cat2_order), 0) AS m FROM melis_cms_category2 WHERE cat2_father_cat_id = ?',
                    [$parentId]
                ));
                $order = (int) ($maxRow[0]['m'] ?? 0) + 1;
                $db->query(
                    'INSERT INTO melis_cms_category2
                     (cat2_father_cat_id, cat2_status, cat2_order, cat2_date_valid_start, cat2_date_valid_end,
                      cat2_date_creation, cat2_user_id_creation)
                     VALUES (?, ?, ?, ?, ?, ?, ?)',
                    [$parentId, $status, $order, $startSql, $endSql, date('Y-m-d H:i:s'), $userId]
                );
                $id = (int) iterator_to_array($db->query('SELECT LAST_INSERT_ID() AS id', []))[0]['id'];
            }

            // ── Resync translations (delete-all + insert non-empty) ──
            $db->query('DELETE FROM melis_cms_category2_trans WHERE catt2_category_id = ?', [$id]);
            foreach ($trans as $langId => $t) {
                $name = trim((string) ($t['name'] ?? ''));
                if ($name === '') { continue; }
                $db->query(
                    'INSERT INTO melis_cms_category2_trans (catt2_category_id, catt2_lang_id, catt2_name, catt2_description)
                     VALUES (?, ?, ?, ?)',
                    [$id, (int) $langId, $name, (string) ($t['description'] ?? '')]
                );
            }

            // ── Resync sites (delete-all + insert selected) ──
            $db->query('DELETE FROM melis_cms_category2_sites WHERE cats2_cat2_id = ?', [$id]);
            foreach ($sites as $siteId) {
                $db->query('INSERT INTO melis_cms_category2_sites (cats2_cat2_id, cats2_site_id) VALUES (?, ?)', [$id, $siteId]);
            }

            return $this->jsonResponse(['success' => true, 'data' => ['id' => $id]], $body['id'] ?? null ? 200 : 201);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── DELETE /delete/:id ───────────────────────────────────────────────────────

    public function deleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }

        try {
            $db  = $this->db();
            $rows = iterator_to_array($db->query(
                'SELECT cat2_father_cat_id, cat2_order FROM melis_cms_category2 WHERE cat2_id = ?', [$id]
            ));
            if (!$rows) { return $this->jsonResponse(['success' => false, 'error' => 'Not found'], 404); }

            // Protection: cannot delete a category that has children (legacy parity).
            $kids = iterator_to_array($db->query(
                'SELECT COUNT(*) AS n FROM melis_cms_category2 WHERE cat2_father_cat_id = ?', [$id]
            ));
            if ((int) ($kids[0]['n'] ?? 0) > 0) {
                return $this->jsonResponse(['success' => false, 'error' => 'Impossible de supprimer une catégorie qui a des sous-catégories.'], 400);
            }

            $father = (int) $rows[0]['cat2_father_cat_id'];
            $order  = (int) $rows[0]['cat2_order'];

            $db->query('DELETE FROM melis_cms_category2_trans WHERE catt2_category_id = ?', [$id]);
            $db->query('DELETE FROM melis_cms_category2_sites WHERE cats2_cat2_id = ?', [$id]);
            $db->query('DELETE FROM melis_cms_category2_media WHERE catm2_cat_id = ?', [$id]);
            $db->query('DELETE FROM melis_cms_category2 WHERE cat2_id = ?', [$id]);
            // Re-sequence remaining siblings.
            $db->query(
                'UPDATE melis_cms_category2 SET cat2_order = cat2_order - 1 WHERE cat2_father_cat_id = ? AND cat2_order > ?',
                [$father, $order]
            );

            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── POST /reorder ────────────────────────────────────────────────────────────

    /**
     * Resequence the children of one parent (drag-drop). Body: { parentId, orderedIds:[...] }.
     * Sets cat2_father_cat_id = parentId and cat2_order = index+1 for each id, in order.
     * A cross-parent move is two calls (old parent + new parent) from the client.
     */
    public function reorderAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }

        try {
            $body     = json_decode($this->getRequest()->getContent(), true) ?? [];
            $parentId = isset($body['parentId']) ? (int) $body['parentId'] : self::ROOT;
            $ids      = array_map('intval', (array) ($body['orderedIds'] ?? []));

            $db = $this->db();
            $order = 1;
            foreach ($ids as $cid) {
                $db->query(
                    'UPDATE melis_cms_category2 SET cat2_father_cat_id = ?, cat2_order = ? WHERE cat2_id = ?',
                    [$parentId, $order++, $cid]
                );
            }
            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── GET /category/:id/media ──────────────────────────────────────────────────

    public function categoryMediaAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }

        try {
            $rows = iterator_to_array($this->db()->query(
                'SELECT catm2_id, catm2_type, catm2_path FROM melis_cms_category2_media WHERE catm2_cat_id = ? ORDER BY catm2_id ASC',
                [$id]
            ));
            $images = []; $files = [];
            foreach ($rows as $r) {
                $item = [
                    'id'   => (int) $r['catm2_id'],
                    'path' => (string) $r['catm2_path'],
                    'name' => basename((string) $r['catm2_path']),
                ];
                if (($r['catm2_type'] ?? '') === 'image') { $images[] = $item; } else { $files[] = $item; }
            }
            return $this->jsonResponse(['success' => true, 'data' => ['images' => $images, 'files' => $files]]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── POST /media/upload  (multipart: catId, type, file) ───────────────────────

    public function mediaUploadAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }

        try {
            $catId = (int) $this->params()->fromPost('catId', 0);
            $type  = $this->params()->fromPost('type', 'file') === 'image' ? 'image' : 'file';
            $file  = $this->params()->fromFiles('file');

            if ($catId <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Catégorie invalide.'], 400); }
            if (empty($file) || empty($file['tmp_name']) || !empty($file['error'])) {
                return $this->jsonResponse(['success' => false, 'error' => 'Aucun fichier reçu.'], 400);
            }
            if ($type === 'image' && @getimagesize($file['tmp_name']) === false) {
                return $this->jsonResponse(['success' => false, 'error' => 'Le fichier n’est pas une image valide.'], 400);
            }

            $db = $this->db();
            if (!iterator_to_array($db->query('SELECT cat2_id FROM melis_cms_category2 WHERE cat2_id = ?', [$catId]))) {
                return $this->jsonResponse(['success' => false, 'error' => 'Not found'], 404);
            }

            $dir = $this->mediaDir($catId);
            if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
                return $this->jsonResponse(['success' => false, 'error' => 'Dossier média non accessible en écriture.'], 500);
            }
            $target = $this->uniquePath($dir, $this->sanitizeFilename((string) $file['name']));
            if (!@move_uploaded_file($file['tmp_name'], $target)) {
                return $this->jsonResponse(['success' => false, 'error' => 'Échec de l’enregistrement du fichier.'], 500);
            }
            @chmod($target, 0664);

            $webPath = '/media/categories/' . $catId . '/' . basename($target);
            $db->query(
                'INSERT INTO melis_cms_category2_media (catm2_cat_id, catm2_type, catm2_path) VALUES (?, ?, ?)',
                [$catId, $type, $webPath]
            );
            $mediaId = (int) iterator_to_array($db->query('SELECT LAST_INSERT_ID() AS id', []))[0]['id'];

            return $this->jsonResponse(['success' => true, 'data' => [
                'id' => $mediaId, 'type' => $type, 'path' => $webPath, 'name' => basename($target),
            ]], 201);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── DELETE /media/delete/:id ─────────────────────────────────────────────────

    public function mediaDeleteAction(): HttpResponse
    {
        if ($deny = $this->denyUnlessAccess()) { return $deny; }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id <= 0) { return $this->jsonResponse(['success' => false, 'error' => 'Invalid ID'], 400); }

        try {
            $db  = $this->db();
            $rows = iterator_to_array($db->query('SELECT catm2_path FROM melis_cms_category2_media WHERE catm2_id = ?', [$id]));
            if (!$rows) { return $this->jsonResponse(['success' => false, 'error' => 'Not found'], 404); }

            $abs = rtrim((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''), '/\\') . (string) $rows[0]['catm2_path'];
            if (is_file($abs)) { @unlink($abs); }
            $db->query('DELETE FROM melis_cms_category2_media WHERE catm2_id = ?', [$id]);

            return $this->jsonResponse(['success' => true, 'data' => null]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────────

    /** Absolute upload dir for a category's media (web-served under /media/categories/<id>/). */
    private function mediaDir(int $catId): string
    {
        return rtrim((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''), '/\\') . '/media/categories/' . $catId . '/';
    }

    /** Keep only a safe base name + a lowercased extension. */
    private function sanitizeFilename(string $name): string
    {
        $name = basename($name);
        $ext  = strtolower(preg_replace('/[^A-Za-z0-9]/', '', pathinfo($name, PATHINFO_EXTENSION)) ?? '');
        $base = preg_replace('/[^A-Za-z0-9_-]+/', '_', pathinfo($name, PATHINFO_FILENAME)) ?? '';
        $base = trim($base, '_') ?: 'file';
        return $ext !== '' ? ($base . '.' . $ext) : $base;
    }

    /** A non-colliding path in $dir for $name (append _1, _2, … if it already exists). */
    private function uniquePath(string $dir, string $name): string
    {
        $target = $dir . $name;
        if (!file_exists($target)) { return $target; }
        $ext  = pathinfo($name, PATHINFO_EXTENSION);
        $base = pathinfo($name, PATHINFO_FILENAME);
        $i = 1;
        do { $target = $dir . $base . '_' . $i . ($ext !== '' ? '.' . $ext : ''); $i++; } while (file_exists($target));
        return $target;
    }

    private function db()
    {
        return $this->getServiceManager()->get('Laminas\Db\Adapter\AdapterInterface');
    }

    /** datetime|date DB value → 'Y-m-d' (for <input type=date>), or null. */
    private function toDate($value): ?string
    {
        if (empty($value) || $value === '0000-00-00' || $value === '0000-00-00 00:00:00') { return null; }
        $ts = strtotime((string) $value);
        return $ts ? date('Y-m-d', $ts) : null;
    }

    private function currentUserId(): int
    {
        try {
            $auth = $this->getServiceManager()->get('MelisCoreAuth');
            if ($auth->hasIdentity()) {
                $id = $auth->getIdentity();
                return (int) ($id->usr_id ?? 0);
            }
        } catch (\Throwable) {}
        return 0;
    }

    private function isAuthenticated(): bool
    {
        return $this->getServiceManager()->get('MelisCoreAuth')->hasIdentity();
    }

    private function denyUnlessAccess(): ?HttpResponse
    {
        if (!$this->isAuthenticated()) {
            return $this->jsonResponse(['success' => false, 'error' => 'Unauthenticated'], 401);
        }
        try {
            if (!$this->getServiceManager()->get('MelisCoreRights')->canAccess(self::MELIS_KEY)) {
                return $this->jsonResponse(['success' => false, 'error' => 'Forbidden'], 403);
            }
        } catch (\Throwable) {}
        return null;
    }

    private function jsonResponse(array $data, int $status = 200): HttpResponse
    {
        /** @var HttpResponse $response */
        $response = $this->getResponse();
        $response->setStatusCode($status);
        $response->getHeaders()->addHeaders([
            'Content-Type'           => 'application/json; charset=utf-8',
            'X-Content-Type-Options' => 'nosniff',
        ]);
        $response->setContent(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        return $response;
    }

    private function errorResponse(\Throwable $e, int $status = 500): HttpResponse
    {
        return $this->jsonResponse([
            'success' => false,
            'error'   => $e->getMessage(),
            'file'    => basename($e->getFile()) . ':' . $e->getLine(),
        ], $status);
    }
}
