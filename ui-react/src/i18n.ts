/**
 * Minimal FR/EN i18n for the Categories brick. The brick can't import the host dictionaries,
 * so it carries its own, read from <html lang> (set by the shell from the session locale).
 */
export type Lang = 'fr' | 'en'

export function currentLang(): Lang {
  return (document.documentElement.lang || 'en').toLowerCase().startsWith('fr') ? 'fr' : 'en'
}

const DICT: Record<Lang, Record<string, string>> = {
  fr: {
    title: 'Catégories',
    subtitle: 'Arborescence des catégories multilingues',
    search: 'Rechercher une catégorie…',
    all_sites: 'Tous les sites',
    new_root: 'Nouvelle catégorie',
    add_child: 'Ajouter une sous-catégorie',
    edit: 'Modifier', del: 'Supprimer', refresh: 'Rafraîchir', back: 'Retour à l’arbre',
    empty_tree: 'Aucune catégorie. Créez-en une pour commencer.',
    drag_hint: 'Glisser pour réordonner ou déplacer dans une catégorie',
    empty_editor: 'Sélectionnez une catégorie dans l’arbre, ou créez-en une.',
    no_edit_access: 'Vous n’avez pas le droit d’éditer les catégories.',
    loading: 'Chargement…',
    tab_props: 'Propriétés', tab_media: 'Média', media_soon: 'Média — bientôt (phase 2)',
    new_title: 'Nouvelle catégorie', edit_title: 'Modifier « {name} »',
    under_parent: 'sous « {name} »', under_root: 'à la racine',
    f_name: 'Nom', f_name_ph: 'Nom de la catégorie', f_desc: 'Description',
    f_dates: 'Validité', f_date_start: 'Début', f_date_end: 'Fin',
    f_sites: 'Sites', f_status: 'Statut', active: 'Active', inactive: 'Inactive',
    save: 'Enregistrer', saving: 'Enregistrement…', saved: 'Enregistré ✓', cancel: 'Annuler',
    del_confirm: 'Supprimer la catégorie « {name} » ? Action irréversible.',
    err_generic: 'Une erreur est survenue.',
    err_name: 'Au moins un nom (dans une langue) est obligatoire.',
    err_site: 'Au moins un site doit être sélectionné.',
    err_dates: 'La date de début doit précéder la date de fin.',
    notif_created: 'Catégorie créée', notif_saved: 'Catégorie enregistrée',
    notif_deleted: 'Catégorie supprimée', notif_error: 'Erreur',
    media_images: 'Images', media_files: 'Fichiers',
    media_add_image: 'Ajouter une image', media_add_file: 'Ajouter un fichier',
    media_empty_img: 'Aucune image.', media_empty_file: 'Aucun fichier.',
    media_save_first: 'Enregistrez la catégorie avant d’ajouter des médias.',
    media_uploaded: 'Fichier ajouté', media_deleted: 'Fichier supprimé',
    media_del_confirm: 'Supprimer ce fichier ?',
    no_name: 'Aucun nom', lang_fallback: 'nom en {lang}',
  },
  en: {
    title: 'Categories',
    subtitle: 'Multilingual category tree',
    search: 'Search a category…',
    all_sites: 'All sites',
    new_root: 'New category',
    add_child: 'Add a sub-category',
    edit: 'Edit', del: 'Delete', refresh: 'Refresh', back: 'Back to tree',
    empty_tree: 'No category yet. Create one to get started.',
    drag_hint: 'Drag to reorder or move into a category',
    empty_editor: 'Select a category in the tree, or create one.',
    no_edit_access: 'You do not have permission to edit categories.',
    loading: 'Loading…',
    tab_props: 'Properties', tab_media: 'Media', media_soon: 'Media — coming soon (phase 2)',
    new_title: 'New category', edit_title: 'Edit “{name}”',
    under_parent: 'under “{name}”', under_root: 'at the root',
    f_name: 'Name', f_name_ph: 'Category name', f_desc: 'Description',
    f_dates: 'Validity', f_date_start: 'Start', f_date_end: 'End',
    f_sites: 'Sites', f_status: 'Status', active: 'Active', inactive: 'Inactive',
    save: 'Save', saving: 'Saving…', saved: 'Saved ✓', cancel: 'Cancel',
    del_confirm: 'Delete category “{name}”? This cannot be undone.',
    err_generic: 'Something went wrong.',
    err_name: 'At least one name (in one language) is required.',
    err_site: 'At least one site must be selected.',
    err_dates: 'The start date must precede the end date.',
    notif_created: 'Category created', notif_saved: 'Category saved',
    notif_deleted: 'Category deleted', notif_error: 'Error',
    media_images: 'Images', media_files: 'Files',
    media_add_image: 'Add an image', media_add_file: 'Add a file',
    media_empty_img: 'No image.', media_empty_file: 'No file.',
    media_save_first: 'Save the category before adding media.',
    media_uploaded: 'File added', media_deleted: 'File deleted',
    media_del_confirm: 'Delete this file?',
    no_name: 'No name', lang_fallback: '{lang} name',
  },
}

export function useT() {
  const lang = currentLang()
  return (key: string, vars?: Record<string, string | number>) => {
    let s = DICT[lang][key] ?? key
    if (vars) for (const [k, v] of Object.entries(vars)) s = s.replaceAll(`{${k}}`, String(v))
    return s
  }
}
