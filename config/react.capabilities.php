<?php

/**
 * Capacités d'outils — droits avancés du back-office React (module MelisCmsCategory2).
 *
 * Même convention que melis-commerce / melis-cms-slider : chaque module déclare ICI les capacités
 * de SES outils, par melisKey, dans la clé mergée `melisReactToolCapabilities` (lue par
 * MelisReactApi\Service\Capabilities). Fichier séparé ; mergé dans MelisCmsCategory2\Module::getConfig().
 * Default-allow.
 *
 * ⚠️ Key = melisKey of the RIGHTS-BEARING menu node (nodeKey = melisKey||key), i.e. the one with
 * rights_checkbox_disable=false: `melis_cms_category_v2_tools_section` (app.interface.php). That is
 * what RightsTreeView hangs capabilities on, what the legacy rights modal now stores, and the
 * MELIS_KEY of the react-api controller. NOT `melis_cms_categories_v2`: that is the `type` TARGET,
 * which stays the renderable ZONE key (iframe react-tool-page?key=, CategoryPage.tsx) and is not
 * granted on its own. Same 3-key split as MelisCmsSlider / MelisCmsNews.
 *
 * L'outil Catégories est un MASTER-DETAIL : arbre à gauche + panneau d'édition à droite. Les droits
 * sont donc divisés en DEUX parties (deux onglets de capacités) :
 *   1. tree    (l'Arbre)   → create (bouton « Nouveau » ET « + » dans l'arbre, même droit),
 *                            order (drag'n'drop de réordonnancement), delete (suppression)
 *   2. edition (l'Édition) → l'accès à l'onglet = charge le panneau de droite (sans lui, le panneau
 *                            ne se charge pas en édition) ; sous-onglets properties (Propriétés) et media
 *
 * `Capabilities::flatten()` aplati en chaînes complètes : `tree`, `tree.create`, `tree.order`,
 * `tree.delete`, `edition`, `edition.properties`, `edition.media` — exactement les chaînes passées à
 * `MelisCan(melisKey, cap)` côté React. Les `label` sont des clés de traduction Melis existantes.
 */

return [
    'melisReactToolCapabilities' => [
        'melis_cms_category_v2_tools_section' => [
            // Pas d'actions au niveau outil : tout est réparti dans les deux onglets ci-dessous.
            'tabs' => [
                [
                    // Partie 1 — l'arbre (colonne de gauche).
                    'key' => 'tree', 'label' => 'tr_melis_cms_category_v2_list', // "Category Lists"
                    'actions' => ['create', 'order', 'delete'],
                ],
                [
                    // Partie 2 — l'édition (panneau de droite). L'accès à cet onglet = droit de charger
                    // le panneau ; ses sous-onglets = les deux onglets du panneau.
                    'key' => 'edition', 'label' => 'tr_meliscore_tool_gen_edit', // "Edit" / "Édition"
                    'tabs' => [
                        ['key' => 'properties', 'label' => 'tr_melis_cms_category_v2_properties'], // "Properties"
                        ['key' => 'media',      'label' => 'tr_melis_cms_category_media_tab'],       // "Media"
                    ],
                ],
            ],
        ],
    ],
];
