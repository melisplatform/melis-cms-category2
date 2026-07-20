(function(react, react_jsx_runtime) {
	//#region src/category-api.ts
	/**
	* Typed client for the Categories JSON API — owned by MelisCmsCategory2 itself (module brick),
	* backend: MelisCmsCategoryReactApiController. Base: /melis/MelisCmsCategory2/react-api.
	*/
	var XHR = { "X-Requested-With": "XMLHttpRequest" };
	var BASE = "/melis/MelisCmsCategory2/react-api";
	async function apiFetch(url, init) {
		const res = await fetch(url, {
			...init,
			headers: {
				...XHR,
				...init?.body ? { "Content-Type": "application/json" } : {},
				...init?.headers || {}
			},
			credentials: "include"
		});
		const data = await res.json().catch(() => ({
			success: false,
			error: `HTTP ${res.status}`
		}));
		if (!res.ok || !data.success) throw new Error(data.error ?? `HTTP ${res.status}`);
		return data.data;
	}
	var fetchLangs = () => apiFetch(`${BASE}/langs`).then((d) => d.langs);
	var fetchSites = () => apiFetch(`${BASE}/sites`).then((d) => d.sites);
	var fetchTree = (langId) => apiFetch(`${BASE}/tree?lang=${langId}`).then((d) => d.nodes);
	var fetchCategory = (id) => apiFetch(`${BASE}/category/${id}`);
	var saveCategory = (payload) => apiFetch(`${BASE}/save`, {
		method: "POST",
		body: JSON.stringify(payload)
	});
	var deleteCategory = (id) => apiFetch(`${BASE}/delete/${id}`, { method: "DELETE" });
	var reorderCategories = (parentId, orderedIds) => apiFetch(`${BASE}/reorder`, {
		method: "POST",
		body: JSON.stringify({
			parentId,
			orderedIds
		})
	});
	var fetchCategoryMedia = (id) => apiFetch(`${BASE}/category/${id}/media`);
	var deleteMedia = (id) => apiFetch(`${BASE}/media/delete/${id}`, { method: "DELETE" });
	/** Multipart upload (own fetch — the browser must set the multipart boundary itself). */
	async function uploadMedia(catId, type, file) {
		const fd = new FormData();
		fd.append("catId", String(catId));
		fd.append("type", type);
		fd.append("file", file);
		const res = await fetch(`${BASE}/media/upload`, {
			method: "POST",
			headers: { ...XHR },
			credentials: "include",
			body: fd
		});
		const data = await res.json().catch(() => ({
			success: false,
			error: `HTTP ${res.status}`
		}));
		if (!res.ok || !data.success) throw new Error(data.error ?? `HTTP ${res.status}`);
		return data.data;
	}
	//#endregion
	//#region src/i18n.ts
	function currentLang() {
		return (document.documentElement.lang || "en").toLowerCase().startsWith("fr") ? "fr" : "en";
	}
	var DICT = {
		fr: {
			title: "Catégories",
			subtitle: "Arborescence des catégories multilingues",
			search: "Rechercher une catégorie…",
			all_sites: "Tous les sites",
			new_root: "Nouvelle catégorie",
			add_child: "Ajouter une sous-catégorie",
			edit: "Modifier",
			del: "Supprimer",
			refresh: "Rafraîchir",
			empty_tree: "Aucune catégorie. Créez-en une pour commencer.",
			drag_hint: "Glisser pour réordonner ou déplacer dans une catégorie",
			empty_editor: "Sélectionnez une catégorie dans l’arbre, ou créez-en une.",
			no_edit_access: "Vous n’avez pas le droit d’éditer les catégories.",
			loading: "Chargement…",
			tab_props: "Propriétés",
			tab_media: "Média",
			media_soon: "Média — bientôt (phase 2)",
			new_title: "Nouvelle catégorie",
			edit_title: "Modifier « {name} »",
			under_parent: "sous « {name} »",
			under_root: "à la racine",
			f_name: "Nom",
			f_name_ph: "Nom de la catégorie",
			f_desc: "Description",
			f_dates: "Validité",
			f_date_start: "Début",
			f_date_end: "Fin",
			f_sites: "Sites",
			f_status: "Statut",
			active: "Active",
			inactive: "Inactive",
			save: "Enregistrer",
			saving: "Enregistrement…",
			saved: "Enregistré ✓",
			cancel: "Annuler",
			del_confirm: "Supprimer la catégorie « {name} » ? Action irréversible.",
			err_generic: "Une erreur est survenue.",
			err_name: "Au moins un nom (dans une langue) est obligatoire.",
			err_site: "Au moins un site doit être sélectionné.",
			err_dates: "La date de début doit précéder la date de fin.",
			notif_created: "Catégorie créée",
			notif_saved: "Catégorie enregistrée",
			notif_deleted: "Catégorie supprimée",
			notif_error: "Erreur",
			media_images: "Images",
			media_files: "Fichiers",
			media_add_image: "Ajouter une image",
			media_add_file: "Ajouter un fichier",
			media_empty_img: "Aucune image.",
			media_empty_file: "Aucun fichier.",
			media_save_first: "Enregistrez la catégorie avant d’ajouter des médias.",
			media_uploaded: "Fichier ajouté",
			media_deleted: "Fichier supprimé",
			media_del_confirm: "Supprimer ce fichier ?",
			no_name: "Aucun nom",
			lang_fallback: "nom en {lang}"
		},
		en: {
			title: "Categories",
			subtitle: "Multilingual category tree",
			search: "Search a category…",
			all_sites: "All sites",
			new_root: "New category",
			add_child: "Add a sub-category",
			edit: "Edit",
			del: "Delete",
			refresh: "Refresh",
			empty_tree: "No category yet. Create one to get started.",
			drag_hint: "Drag to reorder or move into a category",
			empty_editor: "Select a category in the tree, or create one.",
			no_edit_access: "You do not have permission to edit categories.",
			loading: "Loading…",
			tab_props: "Properties",
			tab_media: "Media",
			media_soon: "Media — coming soon (phase 2)",
			new_title: "New category",
			edit_title: "Edit “{name}”",
			under_parent: "under “{name}”",
			under_root: "at the root",
			f_name: "Name",
			f_name_ph: "Category name",
			f_desc: "Description",
			f_dates: "Validity",
			f_date_start: "Start",
			f_date_end: "End",
			f_sites: "Sites",
			f_status: "Status",
			active: "Active",
			inactive: "Inactive",
			save: "Save",
			saving: "Saving…",
			saved: "Saved ✓",
			cancel: "Cancel",
			del_confirm: "Delete category “{name}”? This cannot be undone.",
			err_generic: "Something went wrong.",
			err_name: "At least one name (in one language) is required.",
			err_site: "At least one site must be selected.",
			err_dates: "The start date must precede the end date.",
			notif_created: "Category created",
			notif_saved: "Category saved",
			notif_deleted: "Category deleted",
			notif_error: "Error",
			media_images: "Images",
			media_files: "Files",
			media_add_image: "Add an image",
			media_add_file: "Add a file",
			media_empty_img: "No image.",
			media_empty_file: "No file.",
			media_save_first: "Save the category before adding media.",
			media_uploaded: "File added",
			media_deleted: "File deleted",
			media_del_confirm: "Delete this file?",
			no_name: "No name",
			lang_fallback: "{lang} name"
		}
	};
	function useT() {
		const lang = currentLang();
		return (key, vars) => {
			let s = DICT[lang][key] ?? key;
			if (vars) for (const [k, v] of Object.entries(vars)) s = s.replaceAll(`{${k}}`, String(v));
			return s;
		};
	}
	//#endregion
	//#region src/ui.tsx
	function makeCan(melisKey) {
		return (cap) => window.MelisCan?.(melisKey, cap) ?? true;
	}
	var card = {
		border: "1px solid var(--color-border)",
		background: "var(--color-card)",
		borderRadius: 12,
		boxShadow: "0 1px 2px rgba(0,0,0,.04)"
	};
	var input = {
		height: 36,
		boxSizing: "border-box",
		borderRadius: 8,
		border: "1px solid var(--color-input,var(--color-border))",
		background: "var(--color-card)",
		color: "var(--color-foreground)",
		padding: "0 12px",
		fontSize: 14,
		outline: "none",
		width: "100%"
	};
	var btnGhost = {
		display: "inline-flex",
		alignItems: "center",
		justifyContent: "center",
		gap: 6,
		height: 36,
		padding: "0 12px",
		borderRadius: 8,
		border: "1px solid var(--color-border)",
		background: "var(--color-card)",
		color: "var(--color-foreground)",
		fontSize: 14,
		cursor: "pointer"
	};
	var btnPrimary = {
		display: "inline-flex",
		alignItems: "center",
		justifyContent: "center",
		gap: 6,
		height: 36,
		padding: "0 16px",
		borderRadius: 8,
		border: 0,
		background: "var(--color-primary,#e11d48)",
		color: "var(--color-primary-foreground,#fff)",
		fontSize: 14,
		fontWeight: 600,
		cursor: "pointer"
	};
	var label = {
		fontSize: 12,
		fontWeight: 600,
		color: "var(--color-muted-foreground)"
	};
	var svg = (path, size = 16) => ({
		width: size,
		height: size,
		flexShrink: 0
	});
	var S = ({ children, size = 16 }) => /* @__PURE__ */ (0, react_jsx_runtime.jsx)("svg", {
		style: svg(children, size),
		viewBox: "0 0 24 24",
		fill: "none",
		stroke: "currentColor",
		strokeWidth: "2",
		strokeLinecap: "round",
		strokeLinejoin: "round",
		children
	});
	var IconPlus = ({ size = 16 }) => /* @__PURE__ */ (0, react_jsx_runtime.jsx)(S, {
		size,
		children: /* @__PURE__ */ (0, react_jsx_runtime.jsx)("path", { d: "M12 5v14M5 12h14" })
	});
	var IconTrash = ({ size = 16 }) => /* @__PURE__ */ (0, react_jsx_runtime.jsx)(S, {
		size,
		children: /* @__PURE__ */ (0, react_jsx_runtime.jsx)("path", { d: "M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" })
	});
	var IconChevron = ({ open, size = 16 }) => /* @__PURE__ */ (0, react_jsx_runtime.jsx)("svg", {
		style: {
			width: size,
			height: size,
			flexShrink: 0,
			transform: open ? "rotate(90deg)" : "none",
			transition: "transform .12s"
		},
		viewBox: "0 0 24 24",
		fill: "none",
		stroke: "currentColor",
		strokeWidth: "2",
		strokeLinecap: "round",
		strokeLinejoin: "round",
		children: /* @__PURE__ */ (0, react_jsx_runtime.jsx)("path", { d: "m9 18 6-6-6-6" })
	});
	var IconSearch = ({ size = 16 }) => /* @__PURE__ */ (0, react_jsx_runtime.jsxs)(S, {
		size,
		children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)("circle", {
			cx: "11",
			cy: "11",
			r: "8"
		}), /* @__PURE__ */ (0, react_jsx_runtime.jsx)("path", { d: "m21 21-4.3-4.3" })]
	});
	var IconRefresh = ({ size = 16 }) => /* @__PURE__ */ (0, react_jsx_runtime.jsx)(S, {
		size,
		children: /* @__PURE__ */ (0, react_jsx_runtime.jsx)("path", { d: "M3 12a9 9 0 0 1 15-6.7L21 8M21 3v5h-5M21 12a9 9 0 0 1-15 6.7L3 16M3 21v-5h5" })
	});
	var IconFolder = ({ size = 16 }) => /* @__PURE__ */ (0, react_jsx_runtime.jsx)(S, {
		size,
		children: /* @__PURE__ */ (0, react_jsx_runtime.jsx)("path", { d: "M4 20a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5l2 3h7a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2Z" })
	});
	var IconGrip = ({ size = 16 }) => /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("svg", {
		style: {
			width: size,
			height: size,
			flexShrink: 0
		},
		viewBox: "0 0 24 24",
		fill: "currentColor",
		stroke: "none",
		children: [
			/* @__PURE__ */ (0, react_jsx_runtime.jsx)("circle", {
				cx: "9",
				cy: "6",
				r: "1.4"
			}),
			/* @__PURE__ */ (0, react_jsx_runtime.jsx)("circle", {
				cx: "15",
				cy: "6",
				r: "1.4"
			}),
			/* @__PURE__ */ (0, react_jsx_runtime.jsx)("circle", {
				cx: "9",
				cy: "12",
				r: "1.4"
			}),
			/* @__PURE__ */ (0, react_jsx_runtime.jsx)("circle", {
				cx: "15",
				cy: "12",
				r: "1.4"
			}),
			/* @__PURE__ */ (0, react_jsx_runtime.jsx)("circle", {
				cx: "9",
				cy: "18",
				r: "1.4"
			}),
			/* @__PURE__ */ (0, react_jsx_runtime.jsx)("circle", {
				cx: "15",
				cy: "18",
				r: "1.4"
			})
		]
	});
	var IconCalendar = ({ size = 16 }) => /* @__PURE__ */ (0, react_jsx_runtime.jsxs)(S, {
		size,
		children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)("rect", {
			x: "3",
			y: "4",
			width: "18",
			height: "18",
			rx: "2"
		}), /* @__PURE__ */ (0, react_jsx_runtime.jsx)("path", { d: "M16 2v4M8 2v4M3 10h18" })]
	});
	function isoToDisplay(iso, lang) {
		if (!iso) return "";
		const [y, m, d] = iso.split("-");
		if (!y || !m || !d) return "";
		return lang === "fr" ? `${d}/${m}/${y}` : `${m}/${d}/${y}`;
	}
	function displayToIso(text, lang) {
		const s = text.trim();
		if (s === "") return "";
		const m = s.match(/^(\d{1,2})[/\-.](\d{1,2})[/\-.](\d{4})$/);
		if (!m) return null;
		const a = m[1].padStart(2, "0"), b = m[2].padStart(2, "0"), y = m[3];
		const [dd, mm] = lang === "fr" ? [a, b] : [b, a];
		if (+mm < 1 || +mm > 12 || +dd < 1 || +dd > 31) return null;
		return `${y}-${mm}-${dd}`;
	}
	function DateField({ value, onChange, lang }) {
		const [text, setText] = (0, react.useState)(() => isoToDisplay(value, lang));
		const nativeRef = (0, react.useRef)(null);
		(0, react.useEffect)(() => {
			setText(isoToDisplay(value, lang));
		}, [value, lang]);
		const commit = () => {
			const iso = displayToIso(text, lang);
			if (iso === null) setText(isoToDisplay(value, lang));
			else if (iso !== value) onChange(iso);
		};
		const openPicker = () => {
			const el = nativeRef.current;
			if (el?.showPicker) el.showPicker();
			else el?.focus();
		};
		return /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
			style: { position: "relative" },
			children: [
				/* @__PURE__ */ (0, react_jsx_runtime.jsx)("input", {
					value: text,
					onChange: (e) => setText(e.target.value),
					onBlur: commit,
					onKeyDown: (e) => {
						if (e.key === "Enter") e.currentTarget.blur();
					},
					placeholder: lang === "fr" ? "jj/mm/aaaa" : "mm/dd/yyyy",
					inputMode: "numeric",
					style: {
						...input,
						paddingRight: 34
					}
				}),
				/* @__PURE__ */ (0, react_jsx_runtime.jsx)("button", {
					type: "button",
					onClick: openPicker,
					"aria-label": "calendar",
					tabIndex: -1,
					style: {
						position: "absolute",
						right: 2,
						top: 2,
						width: 32,
						height: 32,
						display: "inline-flex",
						alignItems: "center",
						justifyContent: "center",
						border: 0,
						background: "transparent",
						color: "var(--color-muted-foreground)",
						cursor: "pointer"
					},
					children: /* @__PURE__ */ (0, react_jsx_runtime.jsx)(IconCalendar, { size: 16 })
				}),
				/* @__PURE__ */ (0, react_jsx_runtime.jsx)("input", {
					ref: nativeRef,
					type: "date",
					value: value || "",
					onChange: (e) => onChange(e.target.value),
					tabIndex: -1,
					"aria-hidden": true,
					style: {
						position: "absolute",
						right: 6,
						bottom: 0,
						width: 1,
						height: 1,
						opacity: 0,
						pointerEvents: "none"
					}
				})
			]
		});
	}
	function StatusDot({ active }) {
		return /* @__PURE__ */ (0, react_jsx_runtime.jsx)("span", { style: {
			width: 8,
			height: 8,
			borderRadius: 999,
			flexShrink: 0,
			background: active ? "#22c55e" : "#ef4444"
		} });
	}
	function Toggle({ checked, onChange }) {
		return /* @__PURE__ */ (0, react_jsx_runtime.jsx)("button", {
			type: "button",
			onClick: () => onChange(!checked),
			"aria-pressed": checked,
			style: {
				width: 42,
				height: 24,
				borderRadius: 999,
				border: 0,
				cursor: "pointer",
				padding: 2,
				background: checked ? "#22c55e" : "color-mix(in srgb, var(--color-muted,#888) 40%, transparent)",
				display: "inline-flex",
				alignItems: "center",
				transition: "background .15s"
			},
			children: /* @__PURE__ */ (0, react_jsx_runtime.jsx)("span", { style: {
				width: 20,
				height: 20,
				borderRadius: 999,
				background: "#fff",
				boxShadow: "0 1px 2px rgba(0,0,0,.3)",
				transform: checked ? "translateX(18px)" : "none",
				transition: "transform .15s"
			} })
		});
	}
	/**
	* Fire a host back-office toast from the brick. MelisCore's <Notifications> listens for
	* window messages `{ __melisNotif: true, kind, title, message }` (same bridge used by legacy
	* iframes) — success toasts auto-dismiss, errors stay. Keeps notifications consistent BO-wide.
	*/
	function melisNotify(kind, title, message = "") {
		try {
			window.postMessage({
				__melisNotif: true,
				kind,
				title,
				message
			}, "*");
		} catch {}
	}
	var btnDanger = {
		display: "inline-flex",
		alignItems: "center",
		justifyContent: "center",
		gap: 6,
		height: 36,
		padding: "0 16px",
		borderRadius: 8,
		border: 0,
		background: "#dc2626",
		color: "#fff",
		fontSize: 14,
		fontWeight: 600,
		cursor: "pointer"
	};
	var overlay = {
		position: "fixed",
		inset: 0,
		zIndex: 9999,
		background: "rgba(0,0,0,.45)",
		display: "flex",
		alignItems: "center",
		justifyContent: "center",
		padding: 16
	};
	function ConfirmModal({ title, message, confirmLabel, cancelLabel, danger, onConfirm, onCancel }) {
		(0, react.useEffect)(() => {
			const onKey = (e) => {
				if (e.key === "Escape") onCancel();
				else if (e.key === "Enter") onConfirm();
			};
			window.addEventListener("keydown", onKey);
			return () => window.removeEventListener("keydown", onKey);
		}, [onConfirm, onCancel]);
		return /* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
			style: overlay,
			onClick: onCancel,
			children: /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
				style: {
					...card,
					width: "min(420px, 92vw)",
					padding: "20px 22px"
				},
				onClick: (e) => e.stopPropagation(),
				children: [
					title ? /* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
						style: {
							fontSize: 16,
							fontWeight: 700,
							marginBottom: 8
						},
						children: title
					}) : null,
					/* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
						style: {
							fontSize: 14,
							color: "var(--color-muted-foreground)",
							marginBottom: 20,
							lineHeight: 1.5
						},
						children: message
					}),
					/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
						style: {
							display: "flex",
							justifyContent: "flex-end",
							gap: 8
						},
						children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)("button", {
							style: btnGhost,
							onClick: onCancel,
							children: cancelLabel ?? "Annuler"
						}), /* @__PURE__ */ (0, react_jsx_runtime.jsx)("button", {
							style: danger ? btnDanger : btnPrimary,
							onClick: onConfirm,
							autoFocus: true,
							children: confirmLabel ?? "OK"
						})]
					})
				]
			})
		});
	}
	/**
	* Promise-based confirmation modal (replaces window.confirm). Usage:
	*   const { ask, el } = useConfirm()
	*   … if (!(await ask({ message, danger: true }))) return
	*   return (<> … {el} </>)   // render `el` once in the component tree
	*/
	function useConfirm() {
		const [opts, setOpts] = (0, react.useState)(null);
		const resolver = (0, react.useRef)(null);
		const ask = (o) => new Promise((resolve) => {
			resolver.current = resolve;
			setOpts(o);
		});
		const close = (ok) => {
			setOpts(null);
			const r = resolver.current;
			resolver.current = null;
			r?.(ok);
		};
		return {
			ask,
			el: opts ? /* @__PURE__ */ (0, react_jsx_runtime.jsx)(ConfirmModal, {
				...opts,
				onConfirm: () => close(true),
				onCancel: () => close(false)
			}) : null
		};
	}
	/**
	* Language flag — reuses MelisCore's flag PNGs (served at /MelisCore/assets/images/lang/<short>.png,
	* short = language code, e.g. en_EN → "en"). Emoji flags don't render on Windows, hence images.
	* Missing flag → the <img> hides itself, leaving just the surrounding label.
	*/
	function LangFlag({ locale, size = 16 }) {
		const short = (locale || "").split("_")[0].toLowerCase();
		if (!short) return null;
		return /* @__PURE__ */ (0, react_jsx_runtime.jsx)("img", {
			src: `/MelisCore/assets/images/lang/${short}.png`,
			alt: "",
			width: size,
			height: Math.round(size * 2 / 3),
			style: {
				display: "inline-block",
				borderRadius: 2,
				objectFit: "cover",
				boxShadow: "0 0 0 1px rgba(0,0,0,.10)",
				flexShrink: 0
			},
			onError: (e) => {
				e.currentTarget.style.display = "none";
			}
		});
	}
	//#endregion
	//#region src/ViewToggle.tsx
	var sIcon = {
		width: 15,
		height: 15,
		flexShrink: 0
	};
	var SparkIcon = () => /* @__PURE__ */ (0, react_jsx_runtime.jsx)("svg", {
		style: sIcon,
		viewBox: "0 0 24 24",
		fill: "none",
		stroke: "currentColor",
		strokeWidth: "2",
		strokeLinecap: "round",
		strokeLinejoin: "round",
		children: /* @__PURE__ */ (0, react_jsx_runtime.jsx)("path", { d: "m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3Z" })
	});
	var LayoutIcon = () => /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("svg", {
		style: sIcon,
		viewBox: "0 0 24 24",
		fill: "none",
		stroke: "currentColor",
		strokeWidth: "2",
		strokeLinecap: "round",
		strokeLinejoin: "round",
		children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)("rect", {
			x: "3",
			y: "3",
			width: "18",
			height: "18",
			rx: "2"
		}), /* @__PURE__ */ (0, react_jsx_runtime.jsx)("path", { d: "M3 9h18M9 21V9" })]
	});
	function ViewToggle({ mode, onChange }) {
		const tab = (active) => ({
			display: "inline-flex",
			alignItems: "center",
			gap: 6,
			height: 30,
			padding: "0 12px",
			borderRadius: 6,
			border: 0,
			fontSize: 12,
			fontWeight: 500,
			cursor: "pointer",
			background: active ? "var(--color-card)" : "transparent",
			color: active ? "var(--color-foreground)" : "var(--color-muted-foreground)",
			boxShadow: active ? "0 1px 2px rgba(0,0,0,.06)" : "none"
		});
		return /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
			style: {
				display: "inline-flex",
				gap: 4,
				padding: 4,
				borderRadius: 8,
				border: "1px solid var(--color-border)",
				background: "color-mix(in srgb, var(--color-muted,#888) 12%, transparent)"
			},
			children: [/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("button", {
				style: tab(mode === "react"),
				onClick: () => onChange("react"),
				children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)(SparkIcon, {}), "New"]
			}), /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("button", {
				style: tab(mode === "iframe"),
				onClick: () => onChange("iframe"),
				children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)(LayoutIcon, {}), "Old"]
			})]
		});
	}
	//#endregion
	//#region src/CategoryTree.tsx
	var can$1 = makeCan("melis_cms_category_v2_tools_section");
	/** Does the node or any descendant match the (lowercased) search text? */
	function matchesSearch(node, q) {
		if (!q) return true;
		if (node.name.toLowerCase().includes(q)) return true;
		return node.children.some((c) => matchesSearch(c, q));
	}
	/** Does the node or any descendant belong to the site? */
	function matchesSite(node, siteId) {
		if (!siteId) return true;
		if (node.sites.includes(siteId)) return true;
		return node.children.some((c) => matchesSite(c, siteId));
	}
	function findNode$1(nodes, id) {
		for (const n of nodes) {
			if (n.id === id) return n;
			const r = findNode$1(n.children, id);
			if (r) return r;
		}
		return null;
	}
	/** The ordered sibling list a node lives in (root siblings = the top-level array). */
	function siblingsOf(nodes, parentId) {
		if (parentId === -1) return nodes;
		return findNode$1(nodes, parentId)?.children ?? [];
	}
	/** Custom language selector with flags (a native <select> can't render flag images). */
	function LangDropdown({ langs, currentLangId, onChange }) {
		const [open, setOpen] = (0, react.useState)(false);
		const current = langs.find((l) => l.id === currentLangId) ?? langs[0];
		return /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
			style: { position: "relative" },
			onBlur: () => setTimeout(() => setOpen(false), 120),
			children: [/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("button", {
				type: "button",
				onClick: () => setOpen((o) => !o),
				style: {
					...input,
					width: "auto",
					display: "inline-flex",
					alignItems: "center",
					gap: 6,
					cursor: "pointer"
				},
				children: [
					current && /* @__PURE__ */ (0, react_jsx_runtime.jsx)(LangFlag, {
						locale: current.locale,
						size: 16
					}),
					/* @__PURE__ */ (0, react_jsx_runtime.jsx)("span", {
						style: { fontSize: 14 },
						children: current?.name ?? ""
					}),
					/* @__PURE__ */ (0, react_jsx_runtime.jsx)("span", {
						style: {
							color: "var(--color-muted-foreground)",
							fontSize: 10,
							marginLeft: 2
						},
						children: "▾"
					})
				]
			}), open && /* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
				style: {
					...card,
					position: "absolute",
					top: 42,
					left: 0,
					zIndex: 30,
					minWidth: 170,
					padding: 4,
					display: "flex",
					flexDirection: "column",
					gap: 2
				},
				children: langs.map((l) => /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("button", {
					type: "button",
					onMouseDown: () => {
						onChange(l.id);
						setOpen(false);
					},
					style: {
						display: "flex",
						alignItems: "center",
						gap: 8,
						padding: "7px 8px",
						borderRadius: 6,
						border: 0,
						cursor: "pointer",
						fontSize: 14,
						textAlign: "left",
						color: "var(--color-foreground)",
						background: l.id === currentLangId ? "color-mix(in srgb, var(--color-primary,#e11d48) 12%, transparent)" : "transparent"
					},
					children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)(LangFlag, {
						locale: l.locale,
						size: 16
					}), l.name]
				}, l.id))
			})]
		});
	}
	function CategoryTree(p) {
		const t = useT();
		const [search, setSearch] = (0, react.useState)("");
		const [siteFilter, setSiteFilter] = (0, react.useState)(0);
		const [collapsed, setCollapsed] = (0, react.useState)(/* @__PURE__ */ new Set());
		const [drag, setDrag] = (0, react.useState)(null);
		const [dropInfo, setDropInfo] = (0, react.useState)(null);
		const q = search.trim().toLowerCase();
		const dndEnabled = !q && !siteFilter && can$1("tree.order");
		const visible = (0, react.useMemo)(() => {
			const prune = (list) => list.filter((n) => matchesSearch(n, q) && matchesSite(n, siteFilter)).map((n) => ({
				...n,
				children: prune(n.children)
			}));
			return prune(p.nodes);
		}, [
			p.nodes,
			q,
			siteFilter
		]);
		const toggle = (id) => setCollapsed((prev) => {
			const s = new Set(prev);
			s.has(id) ? s.delete(id) : s.add(id);
			return s;
		});
		const dragNode = drag ? findNode$1(p.nodes, drag.id) : null;
		const isForbiddenTarget = (nodeId) => !dragNode || nodeId === dragNode.id || !!findNode$1(dragNode.children, nodeId);
		const onDrop = (target) => {
			if (drag && dropInfo && dropInfo.id === target.id && !isForbiddenTarget(target.id)) if (dropInfo.pos === "inside") {
				const ids = target.children.map((n) => n.id).filter((id) => id !== drag.id);
				ids.push(drag.id);
				setCollapsed((prev) => {
					const s = new Set(prev);
					s.delete(target.id);
					return s;
				});
				p.onReorder(target.id, ids);
			} else {
				const ids = siblingsOf(p.nodes, target.parentId).map((n) => n.id).filter((id) => id !== drag.id);
				let idx = ids.indexOf(target.id);
				if (dropInfo.pos === "after") idx += 1;
				ids.splice(idx, 0, drag.id);
				p.onReorder(target.parentId, ids);
			}
			setDrag(null);
			setDropInfo(null);
		};
		const renderNode = (node, depth) => {
			const hasChildren = node.children.length > 0;
			const open = q ? true : !collapsed.has(node.id);
			const selected = p.selectedId === node.id;
			const isDropTarget = dropInfo?.id === node.id;
			return /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", { children: [/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
				style: {
					display: "flex",
					alignItems: "center",
					gap: 6,
					height: 34,
					paddingRight: 8,
					paddingLeft: 8 + depth * 18,
					borderRadius: 8,
					cursor: "pointer",
					background: selected ? "color-mix(in srgb, var(--color-primary,#e11d48) 12%, transparent)" : "transparent",
					color: "var(--color-foreground)",
					opacity: drag?.id === node.id ? .4 : 1,
					boxShadow: isDropTarget ? dropInfo.pos === "before" ? "inset 0 2px 0 0 var(--color-primary,#e11d48)" : dropInfo.pos === "after" ? "inset 0 -2px 0 0 var(--color-primary,#e11d48)" : "inset 0 0 0 2px var(--color-primary,#e11d48)" : "none",
					...isDropTarget && dropInfo.pos === "inside" ? { background: "color-mix(in srgb, var(--color-primary,#e11d48) 8%, transparent)" } : null
				},
				onDragOver: (e) => {
					if (!drag || isForbiddenTarget(node.id)) return;
					e.preventDefault();
					const r = e.currentTarget.getBoundingClientRect();
					const y = e.clientY - r.top;
					const pos = y < r.height * .25 ? "before" : y > r.height * .75 ? "after" : "inside";
					setDropInfo({
						id: node.id,
						pos
					});
				},
				onDragLeave: () => setDropInfo((d) => d?.id === node.id ? null : d),
				onDrop: (e) => {
					e.preventDefault();
					e.stopPropagation();
					onDrop(node);
				},
				onMouseEnter: (e) => {
					if (!selected) e.currentTarget.style.background = "color-mix(in srgb, var(--color-muted,#888) 10%, transparent)";
				},
				onMouseLeave: (e) => {
					if (!selected) e.currentTarget.style.background = "transparent";
				},
				onClick: () => p.onSelect(node.id),
				children: [
					dndEnabled ? /* @__PURE__ */ (0, react_jsx_runtime.jsx)("span", {
						style: {
							color: "var(--color-muted-foreground)",
							opacity: .5,
							cursor: "grab",
							display: "inline-flex"
						},
						title: t("drag_hint"),
						draggable: true,
						onDragStart: (e) => {
							e.stopPropagation();
							setDrag({
								id: node.id,
								parentId: node.parentId
							});
							e.dataTransfer.effectAllowed = "move";
						},
						onDragEnd: () => {
							setDrag(null);
							setDropInfo(null);
						},
						onClick: (e) => e.stopPropagation(),
						children: /* @__PURE__ */ (0, react_jsx_runtime.jsx)(IconGrip, { size: 14 })
					}) : null,
					/* @__PURE__ */ (0, react_jsx_runtime.jsx)("span", {
						style: {
							width: 18,
							display: "inline-flex",
							justifyContent: "center",
							color: "var(--color-muted-foreground)"
						},
						onClick: (e) => {
							e.stopPropagation();
							if (hasChildren) toggle(node.id);
						},
						children: hasChildren ? /* @__PURE__ */ (0, react_jsx_runtime.jsx)(IconChevron, {
							open,
							size: 14
						}) : null
					}),
					/* @__PURE__ */ (0, react_jsx_runtime.jsx)(StatusDot, { active: node.status === 1 }),
					/* @__PURE__ */ (0, react_jsx_runtime.jsx)("span", {
						style: {
							flex: 1,
							minWidth: 0,
							fontSize: 14,
							fontWeight: selected ? 600 : 400,
							overflow: "hidden",
							textOverflow: "ellipsis",
							whiteSpace: "nowrap"
						},
						children: node.name || /* @__PURE__ */ (0, react_jsx_runtime.jsx)("span", {
							style: {
								fontStyle: "italic",
								color: "var(--color-muted-foreground)"
							},
							children: t("no_name")
						})
					}),
					node.isFallback && node.name && (() => {
						const fb = p.langs.find((l) => l.id === node.nameLangId);
						return fb ? /* @__PURE__ */ (0, react_jsx_runtime.jsx)("span", {
							style: {
								display: "inline-flex",
								flexShrink: 0
							},
							title: t("lang_fallback", { lang: fb.name }),
							children: /* @__PURE__ */ (0, react_jsx_runtime.jsx)(LangFlag, {
								locale: fb.locale,
								size: 15
							})
						}) : null;
					})(),
					can$1("tree.create") && /* @__PURE__ */ (0, react_jsx_runtime.jsx)("button", {
						title: t("add_child"),
						onClick: (e) => {
							e.stopPropagation();
							p.onAddChild(node.id);
						},
						style: iconBtn,
						children: /* @__PURE__ */ (0, react_jsx_runtime.jsx)(IconPlus, { size: 15 })
					}),
					can$1("tree.delete") && /* @__PURE__ */ (0, react_jsx_runtime.jsx)("button", {
						title: t("del"),
						onClick: (e) => {
							e.stopPropagation();
							p.onDelete(node);
						},
						style: iconBtn,
						children: /* @__PURE__ */ (0, react_jsx_runtime.jsx)(IconTrash, { size: 15 })
					})
				]
			}), hasChildren && open ? node.children.map((c) => renderNode(c, depth + 1)) : null] }, node.id);
		};
		return /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
			style: {
				...card,
				display: "flex",
				flexDirection: "column",
				minHeight: 0,
				height: "100%",
				overflow: "hidden"
			},
			children: [/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
				style: {
					display: "flex",
					flexDirection: "column",
					gap: 8,
					padding: 12,
					borderBottom: "1px solid var(--color-border)"
				},
				children: [
					/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
						style: {
							display: "flex",
							gap: 8
						},
						children: [
							/* @__PURE__ */ (0, react_jsx_runtime.jsx)(LangDropdown, {
								langs: p.langs,
								currentLangId: p.currentLangId,
								onChange: p.onLangChange
							}),
							/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("select", {
								value: siteFilter,
								onChange: (e) => setSiteFilter(Number(e.target.value)),
								style: {
									...input,
									width: "auto",
									flex: 1
								},
								children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)("option", {
									value: 0,
									children: t("all_sites")
								}), p.sites.map((s) => /* @__PURE__ */ (0, react_jsx_runtime.jsx)("option", {
									value: s.id,
									children: s.name
								}, s.id))]
							}),
							/* @__PURE__ */ (0, react_jsx_runtime.jsx)("button", {
								title: t("refresh"),
								onClick: p.onRefresh,
								style: {
									...iconBtnBox,
									opacity: p.loading ? .5 : 1
								},
								children: /* @__PURE__ */ (0, react_jsx_runtime.jsx)(IconRefresh, { size: 16 })
							})
						]
					}),
					/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
						style: { position: "relative" },
						children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)("span", {
							style: {
								position: "absolute",
								left: 10,
								top: 10,
								color: "var(--color-muted-foreground)"
							},
							children: /* @__PURE__ */ (0, react_jsx_runtime.jsx)(IconSearch, { size: 16 })
						}), /* @__PURE__ */ (0, react_jsx_runtime.jsx)("input", {
							value: search,
							onChange: (e) => setSearch(e.target.value),
							placeholder: t("search"),
							style: {
								...input,
								paddingLeft: 34
							}
						})]
					}),
					can$1("tree.create") && /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("button", {
						onClick: p.onAddRoot,
						style: {
							...btnPrimary,
							height: 38
						},
						children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)(IconPlus, { size: 16 }), t("new_root")]
					})
				]
			}), /* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
				style: {
					flex: 1,
					overflow: "auto",
					padding: 8
				},
				children: visible.length === 0 ? /* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
					style: {
						padding: "32px 12px",
						textAlign: "center",
						fontSize: 13,
						color: "var(--color-muted-foreground)"
					},
					children: t("empty_tree")
				}) : visible.map((n) => renderNode(n, 0))
			})]
		});
	}
	var iconBtn = {
		display: "inline-flex",
		alignItems: "center",
		justifyContent: "center",
		width: 26,
		height: 26,
		borderRadius: 6,
		border: 0,
		background: "transparent",
		color: "var(--color-muted-foreground)",
		cursor: "pointer"
	};
	var iconBtnBox = {
		display: "inline-flex",
		alignItems: "center",
		justifyContent: "center",
		width: 36,
		height: 36,
		borderRadius: 8,
		border: "1px solid var(--color-border)",
		background: "var(--color-card)",
		color: "var(--color-foreground)",
		cursor: "pointer"
	};
	//#endregion
	//#region src/CategoryEditor.tsx
	var can = makeCan("melis_cms_category_v2_tools_section");
	function CategoryEditor({ target, langs, sites, onSaved, onCancel }) {
		const t = useT();
		const canProps = can("edition.properties");
		const canMedia = can("edition.media");
		const [loading, setLoading] = (0, react.useState)(false);
		const [saving, setSaving] = (0, react.useState)(false);
		const [error, setError] = (0, react.useState)("");
		const [tab, setTab] = (0, react.useState)("props");
		const [activeLang, setActiveLang] = (0, react.useState)(langs[0]?.id ?? 1);
		const [trans, setTrans] = (0, react.useState)({});
		const [status, setStatus] = (0, react.useState)(1);
		const [dateStart, setDateStart] = (0, react.useState)("");
		const [dateEnd, setDateEnd] = (0, react.useState)("");
		const [selectedSites, setSelectedSites] = (0, react.useState)([]);
		(0, react.useEffect)(() => {
			setError("");
			setTab(canProps ? "props" : "media");
			setActiveLang(langs[0]?.id ?? 1);
			if (!target) return;
			if (target.id == null) {
				setTrans({});
				setStatus(1);
				setDateStart("");
				setDateEnd("");
				setSelectedSites([]);
				return;
			}
			setLoading(true);
			fetchCategory(target.id).then((d) => {
				setTrans(d.translations || {});
				setStatus(d.status);
				setDateStart(d.dateStart || "");
				setDateEnd(d.dateEnd || "");
				setSelectedSites(d.sites || []);
			}).catch((e) => setError(e.message || t("err_generic"))).finally(() => setLoading(false));
		}, [target ? `${target.id ?? "new"}:${target.parentId}` : "none"]);
		if (!target) return /* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
			style: {
				...card,
				height: "100%",
				display: "flex",
				alignItems: "center",
				justifyContent: "center",
				color: "var(--color-muted-foreground)",
				fontSize: 14,
				textAlign: "center",
				padding: 24
			},
			children: t("empty_editor")
		});
		if (target.id != null && !can("edition")) return /* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
			style: {
				...card,
				height: "100%",
				display: "flex",
				alignItems: "center",
				justifyContent: "center",
				color: "var(--color-muted-foreground)",
				fontSize: 14,
				textAlign: "center",
				padding: 24
			},
			children: t("no_edit_access")
		});
		const setTransField = (langId, field, value) => setTrans((prev) => ({
			...prev,
			[langId]: {
				name: "",
				description: "",
				...prev[langId],
				[field]: value
			}
		}));
		const toggleSite = (id) => setSelectedSites((prev) => prev.includes(id) ? prev.filter((s) => s !== id) : [...prev, id]);
		const doSave = async () => {
			setError("");
			if (!Object.values(trans).some((x) => (x?.name || "").trim() !== "")) {
				setError(t("err_name") || "Name required");
				setTab("props");
				return;
			}
			if (selectedSites.length === 0) {
				setError(t("err_site") || "Site required");
				return;
			}
			if (dateStart && dateEnd && dateStart > dateEnd) {
				setError(t("err_dates") || "Invalid dates");
				return;
			}
			const payload = {
				id: target.id ?? void 0,
				parentId: target.parentId,
				status,
				dateStart,
				dateEnd,
				sites: selectedSites,
				translations: trans
			};
			setSaving(true);
			try {
				const wasEdit = target.id != null;
				const { id } = await saveCategory(payload);
				melisNotify("success", wasEdit ? t("notif_saved") : t("notif_created"));
				onSaved(id);
			} catch (e) {
				const msg = e?.message || t("err_generic");
				setError(msg);
				melisNotify("error", t("notif_error"), msg);
			} finally {
				setSaving(false);
			}
		};
		const title = target.id != null ? t("edit_title", { name: trans[activeLang]?.name || Object.values(trans).find((x) => x.name)?.name || "#" + target.id }) : t("new_title");
		const context = target.parentId === -1 ? t("under_root") : t("under_parent", { name: target.parentName || "#" + target.parentId });
		return /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
			style: {
				...card,
				height: "100%",
				display: "flex",
				flexDirection: "column",
				minHeight: 0,
				overflow: "hidden"
			},
			children: [
				/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
					style: {
						display: "flex",
						alignItems: "center",
						gap: 12,
						padding: "14px 16px",
						borderBottom: "1px solid var(--color-border)"
					},
					children: [
						/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
							style: {
								flex: 1,
								minWidth: 0
							},
							children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
								style: {
									fontSize: 16,
									fontWeight: 700,
									overflow: "hidden",
									textOverflow: "ellipsis",
									whiteSpace: "nowrap"
								},
								children: title
							}), /* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
								style: {
									fontSize: 12,
									color: "var(--color-muted-foreground)"
								},
								children: context
							})]
						}),
						/* @__PURE__ */ (0, react_jsx_runtime.jsx)("button", {
							style: btnGhost,
							onClick: onCancel,
							disabled: saving,
							children: t("cancel")
						}),
						/* @__PURE__ */ (0, react_jsx_runtime.jsx)("button", {
							style: {
								...btnPrimary,
								opacity: saving ? .6 : 1
							},
							onClick: doSave,
							disabled: saving,
							children: saving ? t("saving") : t("save")
						})
					]
				}),
				/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
					style: {
						display: "flex",
						gap: 4,
						padding: "10px 16px 0"
					},
					children: [canProps && /* @__PURE__ */ (0, react_jsx_runtime.jsx)(TabBtn, {
						active: tab === "props",
						onClick: () => setTab("props"),
						children: t("tab_props")
					}), canMedia && /* @__PURE__ */ (0, react_jsx_runtime.jsx)(TabBtn, {
						active: tab === "media",
						onClick: () => setTab("media"),
						children: t("tab_media")
					})]
				}),
				/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
					style: {
						flex: 1,
						overflow: "auto",
						padding: 16
					},
					children: [error ? /* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
						style: {
							marginBottom: 14,
							padding: "10px 12px",
							borderRadius: 8,
							fontSize: 13,
							background: "color-mix(in srgb, #ef4444 12%, transparent)",
							color: "#b91c1c"
						},
						children: error
					}) : null, loading ? /* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
						style: {
							color: "var(--color-muted-foreground)",
							fontSize: 14
						},
						children: t("loading")
					}) : !canProps && !canMedia ? /* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
						style: {
							color: "var(--color-muted-foreground)",
							fontSize: 14
						},
						children: t("no_edit_access")
					}) : tab === "media" && canMedia ? /* @__PURE__ */ (0, react_jsx_runtime.jsx)(MediaTab, {
						catId: target.id,
						t
					}) : canProps ? /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
						style: {
							display: "grid",
							gridTemplateColumns: "minmax(0,1fr) 300px",
							gap: 20
						},
						children: [/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
							style: {
								display: "flex",
								flexDirection: "column",
								gap: 12
							},
							children: [
								/* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
									style: {
										display: "grid",
										gridTemplateColumns: "repeat(2, minmax(0, 1fr))",
										gap: 6
									},
									children: langs.map((l) => {
										const filled = (trans[l.id]?.name || "").trim() !== "";
										return /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("button", {
											onClick: () => setActiveLang(l.id),
											style: {
												...langTab,
												width: "100%",
												...activeLang === l.id ? langTabActive : {}
											},
											children: [
												/* @__PURE__ */ (0, react_jsx_runtime.jsx)(LangFlag, {
													locale: l.locale,
													size: 15
												}),
												/* @__PURE__ */ (0, react_jsx_runtime.jsx)("span", {
													style: {
														flex: 1,
														minWidth: 0,
														overflow: "hidden",
														textOverflow: "ellipsis",
														whiteSpace: "nowrap",
														textAlign: "left"
													},
													children: l.name
												}),
												/* @__PURE__ */ (0, react_jsx_runtime.jsx)("span", {
													title: filled ? void 0 : t("no_name"),
													style: {
														width: 6,
														height: 6,
														borderRadius: 999,
														flexShrink: 0,
														background: filled ? "#22c55e" : "var(--color-border)"
													}
												})
											]
										}, l.id);
									})
								}),
								/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", { children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
									style: label,
									children: t("f_name")
								}), /* @__PURE__ */ (0, react_jsx_runtime.jsx)("input", {
									style: {
										...input,
										marginTop: 6
									},
									value: trans[activeLang]?.name || "",
									onChange: (e) => setTransField(activeLang, "name", e.target.value),
									placeholder: t("f_name_ph")
								})] }),
								/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", { children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
									style: label,
									children: t("f_desc")
								}), /* @__PURE__ */ (0, react_jsx_runtime.jsx)("textarea", {
									value: trans[activeLang]?.description || "",
									onChange: (e) => setTransField(activeLang, "description", e.target.value),
									style: {
										...input,
										height: 120,
										padding: 12,
										resize: "vertical",
										marginTop: 6,
										fontFamily: "inherit"
									}
								})] })
							]
						}), /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
							style: {
								display: "flex",
								flexDirection: "column",
								gap: 18
							},
							children: [
								/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", { children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
									style: label,
									children: t("f_status")
								}), /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
									style: {
										display: "flex",
										alignItems: "center",
										gap: 10,
										marginTop: 8
									},
									children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)(Toggle, {
										checked: status === 1,
										onChange: (v) => setStatus(v ? 1 : 0)
									}), /* @__PURE__ */ (0, react_jsx_runtime.jsx)("span", {
										style: { fontSize: 14 },
										children: status === 1 ? t("active") : t("inactive")
									})]
								})] }),
								/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", { children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
									style: label,
									children: t("f_dates")
								}), /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
									style: {
										display: "flex",
										gap: 8,
										marginTop: 6
									},
									children: [/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
										style: { flex: 1 },
										children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
											style: {
												fontSize: 11,
												color: "var(--color-muted-foreground)",
												marginBottom: 4
											},
											children: t("f_date_start")
										}), /* @__PURE__ */ (0, react_jsx_runtime.jsx)(DateField, {
											value: dateStart,
											onChange: setDateStart,
											lang: currentLang()
										})]
									}), /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
										style: { flex: 1 },
										children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
											style: {
												fontSize: 11,
												color: "var(--color-muted-foreground)",
												marginBottom: 4
											},
											children: t("f_date_end")
										}), /* @__PURE__ */ (0, react_jsx_runtime.jsx)(DateField, {
											value: dateEnd,
											onChange: setDateEnd,
											lang: currentLang()
										})]
									})]
								})] }),
								/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", { children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
									style: label,
									children: t("f_sites")
								}), /* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
									style: {
										display: "flex",
										flexDirection: "column",
										gap: 6,
										marginTop: 8
									},
									children: sites.map((s) => /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("label", {
										style: {
											display: "flex",
											alignItems: "center",
											gap: 8,
											fontSize: 14,
											cursor: "pointer"
										},
										children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)("input", {
											type: "checkbox",
											checked: selectedSites.includes(s.id),
											onChange: () => toggleSite(s.id)
										}), s.name]
									}, s.id))
								})] })
							]
						})]
					}) : null]
				})
			]
		});
	}
	var langTab = {
		display: "inline-flex",
		alignItems: "center",
		gap: 6,
		height: 30,
		padding: "0 12px",
		borderRadius: 6,
		border: "1px solid var(--color-border)",
		background: "transparent",
		color: "var(--color-muted-foreground)",
		fontSize: 13,
		cursor: "pointer"
	};
	var langTabActive = {
		background: "var(--color-card)",
		color: "var(--color-foreground)",
		borderColor: "var(--color-primary,#e11d48)"
	};
	function MediaTab({ catId, t }) {
		const [images, setImages] = (0, react.useState)([]);
		const [files, setFiles] = (0, react.useState)([]);
		const [loading, setLoading] = (0, react.useState)(false);
		const [busy, setBusy] = (0, react.useState)(false);
		const { ask, el: confirmEl } = useConfirm();
		(0, react.useEffect)(() => {
			if (catId == null) return;
			setLoading(true);
			fetchCategoryMedia(catId).then((d) => {
				setImages(d.images);
				setFiles(d.files);
			}).catch(() => {}).finally(() => setLoading(false));
		}, [catId]);
		if (catId == null) return /* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
			style: {
				padding: 24,
				textAlign: "center",
				color: "var(--color-muted-foreground)",
				fontSize: 14
			},
			children: t("media_save_first")
		});
		const upload = async (type, file) => {
			setBusy(true);
			try {
				const item = await uploadMedia(catId, type, file);
				if (type === "image") setImages((p) => [...p, item]);
				else setFiles((p) => [...p, item]);
				melisNotify("success", t("media_uploaded"));
			} catch (e) {
				melisNotify("error", t("notif_error"), e?.message);
			} finally {
				setBusy(false);
			}
		};
		const del = async (type, item) => {
			if (!await ask({
				title: t("del"),
				message: t("media_del_confirm"),
				confirmLabel: t("del"),
				cancelLabel: t("cancel"),
				danger: true
			})) return;
			try {
				await deleteMedia(item.id);
				if (type === "image") setImages((p) => p.filter((x) => x.id !== item.id));
				else setFiles((p) => p.filter((x) => x.id !== item.id));
				melisNotify("success", t("media_deleted"));
			} catch (e) {
				melisNotify("error", t("notif_error"), e?.message);
			}
		};
		if (loading) return /* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
			style: {
				color: "var(--color-muted-foreground)",
				fontSize: 14
			},
			children: t("loading")
		});
		return /* @__PURE__ */ (0, react_jsx_runtime.jsxs)(react_jsx_runtime.Fragment, { children: [/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
			style: {
				display: "grid",
				gridTemplateColumns: "repeat(2, minmax(0,1fr))",
				gap: 20,
				opacity: busy ? .6 : 1,
				pointerEvents: busy ? "none" : "auto"
			},
			children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)(MediaColumn, {
				title: t("media_images"),
				addLabel: t("media_add_image"),
				accept: "image/*",
				onPick: (f) => upload("image", f),
				children: images.length === 0 ? /* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
					style: emptyStyle,
					children: t("media_empty_img")
				}) : /* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
					style: {
						display: "grid",
						gridTemplateColumns: "repeat(auto-fill, minmax(84px,1fr))",
						gap: 8
					},
					children: images.map((im) => /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
						style: {
							position: "relative",
							aspectRatio: "1 / 1",
							borderRadius: 8,
							overflow: "hidden",
							border: "1px solid var(--color-border)"
						},
						children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)("img", {
							src: im.path,
							alt: im.name,
							title: im.name,
							style: {
								width: "100%",
								height: "100%",
								objectFit: "cover",
								display: "block"
							}
						}), /* @__PURE__ */ (0, react_jsx_runtime.jsx)("button", {
							onClick: () => del("image", im),
							title: t("del"),
							style: delOverlay,
							children: /* @__PURE__ */ (0, react_jsx_runtime.jsx)(IconTrash, { size: 13 })
						})]
					}, im.id))
				})
			}), /* @__PURE__ */ (0, react_jsx_runtime.jsx)(MediaColumn, {
				title: t("media_files"),
				addLabel: t("media_add_file"),
				accept: "",
				onPick: (f) => upload("file", f),
				children: files.length === 0 ? /* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
					style: emptyStyle,
					children: t("media_empty_file")
				}) : /* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
					style: {
						display: "flex",
						flexDirection: "column",
						gap: 6
					},
					children: files.map((f) => /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
						style: fileRow,
						children: [
							/* @__PURE__ */ (0, react_jsx_runtime.jsx)("span", {
								style: { color: "var(--color-muted-foreground)" },
								children: /* @__PURE__ */ (0, react_jsx_runtime.jsx)(IconFolder, { size: 16 })
							}),
							/* @__PURE__ */ (0, react_jsx_runtime.jsx)("a", {
								href: f.path,
								target: "_blank",
								rel: "noreferrer",
								style: {
									flex: 1,
									minWidth: 0,
									overflow: "hidden",
									textOverflow: "ellipsis",
									whiteSpace: "nowrap",
									color: "var(--color-foreground)",
									textDecoration: "none",
									fontSize: 13
								},
								children: f.name
							}),
							/* @__PURE__ */ (0, react_jsx_runtime.jsx)("button", {
								onClick: () => del("file", f),
								title: t("del"),
								style: fileDelBtn,
								children: /* @__PURE__ */ (0, react_jsx_runtime.jsx)(IconTrash, { size: 14 })
							})
						]
					}, f.id))
				})
			})]
		}), confirmEl] });
	}
	function MediaColumn({ title, addLabel, accept, onPick, children }) {
		const ref = (0, react.useRef)(null);
		return /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
			style: {
				...card,
				padding: 12,
				display: "flex",
				flexDirection: "column",
				gap: 10
			},
			children: [/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
				style: {
					display: "flex",
					alignItems: "center",
					justifyContent: "space-between",
					gap: 8
				},
				children: [
					/* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
						style: {
							fontWeight: 700,
							fontSize: 14
						},
						children: title
					}),
					/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("button", {
						onClick: () => ref.current?.click(),
						style: {
							...btnGhost,
							height: 30,
							padding: "0 10px",
							fontSize: 13
						},
						children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)(IconPlus, { size: 14 }), addLabel]
					}),
					/* @__PURE__ */ (0, react_jsx_runtime.jsx)("input", {
						ref,
						type: "file",
						accept: accept || void 0,
						style: { display: "none" },
						onChange: (e) => {
							const f = e.target.files?.[0];
							if (f) onPick(f);
							e.currentTarget.value = "";
						}
					})
				]
			}), children]
		});
	}
	var emptyStyle = {
		padding: "24px 8px",
		textAlign: "center",
		fontSize: 13,
		color: "var(--color-muted-foreground)"
	};
	var delOverlay = {
		position: "absolute",
		top: 4,
		right: 4,
		width: 24,
		height: 24,
		borderRadius: 6,
		border: 0,
		cursor: "pointer",
		display: "inline-flex",
		alignItems: "center",
		justifyContent: "center",
		background: "rgba(0,0,0,.55)",
		color: "#fff"
	};
	var fileRow = {
		display: "flex",
		alignItems: "center",
		gap: 8,
		padding: "6px 8px",
		borderRadius: 8,
		border: "1px solid var(--color-border)"
	};
	var fileDelBtn = {
		display: "inline-flex",
		alignItems: "center",
		justifyContent: "center",
		width: 26,
		height: 26,
		borderRadius: 6,
		border: 0,
		background: "transparent",
		color: "var(--color-muted-foreground)",
		cursor: "pointer",
		flexShrink: 0
	};
	function TabBtn({ active, disabled, title, onClick, children }) {
		return /* @__PURE__ */ (0, react_jsx_runtime.jsx)("button", {
			onClick,
			disabled,
			title,
			style: {
				height: 34,
				padding: "0 14px",
				borderRadius: "8px 8px 0 0",
				border: 0,
				cursor: disabled ? "not-allowed" : "pointer",
				fontSize: 13,
				fontWeight: 600,
				opacity: disabled ? .5 : 1,
				background: active ? "color-mix(in srgb, var(--color-primary,#e11d48) 12%, transparent)" : "transparent",
				color: active ? "var(--color-foreground)" : "var(--color-muted-foreground)",
				borderBottom: active ? "2px solid var(--color-primary,#e11d48)" : "2px solid transparent"
			},
			children
		});
	}
	//#endregion
	//#region src/CategoryPage.tsx
	var MELIS_KEY = "melis_cms_categories_v2";
	/** Find a node (and its parent's name) anywhere in the tree. */
	function findNode(nodes, id, parentName = "") {
		for (const n of nodes) {
			if (n.id === id) return {
				node: n,
				parentName
			};
			const found = findNode(n.children, id, n.name);
			if (found) return found;
		}
		return null;
	}
	function CategoryPage() {
		const t = useT();
		const [mode, setMode] = (0, react.useState)("react");
		const [frameLoaded, setFrameLoaded] = (0, react.useState)(false);
		const [langs, setLangs] = (0, react.useState)([]);
		const [sites, setSites] = (0, react.useState)([]);
		const [currentLangId, setCurrentLangId] = (0, react.useState)(0);
		const [nodes, setNodes] = (0, react.useState)([]);
		const [loading, setLoading] = (0, react.useState)(false);
		const [tick, setTick] = (0, react.useState)(0);
		const [selectedId, setSelectedId] = (0, react.useState)(null);
		const [target, setTarget] = (0, react.useState)(null);
		const { ask, el: confirmEl } = useConfirm();
		(0, react.useEffect)(() => {
			Promise.all([fetchLangs(), fetchSites()]).then(([ls, ss]) => {
				setLangs(ls);
				setSites(ss);
				const wantFr = currentLang() === "fr";
				setCurrentLangId((ls.find((l) => l.locale.toLowerCase().startsWith(wantFr ? "fr" : "en")) ?? ls[0])?.id ?? 1);
			}).catch(() => {
				setLangs([]);
				setSites([]);
			});
		}, []);
		(0, react.useEffect)(() => {
			if (!currentLangId) return;
			setLoading(true);
			fetchTree(currentLangId).then(setNodes).catch(() => setNodes([])).finally(() => setLoading(false));
		}, [currentLangId, tick]);
		const reloadTree = () => setTick((x) => x + 1);
		const onSelect = (id) => {
			const found = findNode(nodes, id);
			setSelectedId(id);
			setTarget({
				id,
				parentId: found?.node.parentId ?? -1,
				parentName: found?.parentName
			});
		};
		const onAddRoot = () => {
			setSelectedId(null);
			setTarget({
				id: null,
				parentId: -1
			});
		};
		const onAddChild = (parentId) => {
			const found = findNode(nodes, parentId);
			setSelectedId(null);
			setTarget({
				id: null,
				parentId,
				parentName: found?.node.name
			});
		};
		const onDelete = async (node) => {
			if (!await ask({
				title: t("del"),
				message: t("del_confirm", { name: node.name || "#" + node.id }),
				confirmLabel: t("del"),
				cancelLabel: t("cancel"),
				danger: true
			})) return;
			try {
				await deleteCategory(node.id);
				if (target?.id === node.id) {
					setTarget(null);
					setSelectedId(null);
				}
				reloadTree();
				melisNotify("success", t("notif_deleted"));
			} catch (e) {
				const msg = e?.message || t("err_generic");
				melisNotify("error", t("notif_error"), msg);
			}
		};
		const onSaved = (id) => {
			reloadTree();
			setSelectedId(id);
			setTarget((prev) => ({
				id,
				parentId: prev?.parentId ?? -1,
				parentName: prev?.parentName
			}));
		};
		const onReorder = async (parentId, orderedIds) => {
			try {
				await reorderCategories(parentId, orderedIds);
			} catch (e) {
				window.alert(e?.message || t("err_generic"));
			} finally {
				reloadTree();
			}
		};
		return /* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
			style: {
				display: "flex",
				flexDirection: "column",
				gap: 16,
				padding: 24,
				height: "100%",
				boxSizing: "border-box",
				overflow: "hidden"
			},
			children: [
				/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
					style: {
						display: "flex",
						alignItems: "center",
						justifyContent: "space-between",
						gap: 16,
						flexShrink: 0
					},
					children: [/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", { children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)("h1", {
						style: {
							fontSize: 20,
							fontWeight: 700,
							margin: 0
						},
						children: t("title")
					}), /* @__PURE__ */ (0, react_jsx_runtime.jsx)("p", {
						style: {
							fontSize: 14,
							color: "var(--color-muted-foreground)",
							margin: "2px 0 0"
						},
						children: t("subtitle")
					})] }), /* @__PURE__ */ (0, react_jsx_runtime.jsx)(ViewToggle, {
						mode,
						onChange: (m) => {
							setMode(m);
							if (m === "iframe") setFrameLoaded(true);
						}
					})]
				}),
				frameLoaded && /* @__PURE__ */ (0, react_jsx_runtime.jsx)("div", {
					style: {
						display: mode === "iframe" ? "block" : "none",
						flex: 1,
						minHeight: 0,
						border: "1px solid var(--color-border)",
						borderRadius: 12,
						overflow: "hidden"
					},
					children: /* @__PURE__ */ (0, react_jsx_runtime.jsx)("iframe", {
						src: `/melis/react-tool-page?key=${encodeURIComponent(MELIS_KEY)}`,
						style: {
							width: "100%",
							height: "100%",
							border: 0
						},
						title: t("title")
					})
				}),
				/* @__PURE__ */ (0, react_jsx_runtime.jsxs)("div", {
					style: {
						display: mode === "react" ? "grid" : "none",
						gridTemplateColumns: "minmax(320px, 420px) minmax(0, 1fr)",
						gap: 16,
						flex: 1,
						minHeight: 0
					},
					children: [/* @__PURE__ */ (0, react_jsx_runtime.jsx)(CategoryTree, {
						nodes,
						langs,
						sites,
						currentLangId,
						onLangChange: setCurrentLangId,
						selectedId,
						onSelect,
						onAddRoot,
						onAddChild,
						onDelete,
						onReorder,
						onRefresh: reloadTree,
						loading
					}), /* @__PURE__ */ (0, react_jsx_runtime.jsx)(CategoryEditor, {
						target,
						langs,
						sites,
						onSaved,
						onCancel: () => {
							setTarget(null);
							setSelectedId(null);
						}
					})]
				}),
				confirmEl
			]
		});
	}
	//#endregion
	//#region src/brick.tsx
	window.__melisRegisterBrick?.({
		id: "category2",
		Component: CategoryPage
	});
	//#endregion
})(MelisReact, MelisReactJsxRuntime);
