# Formaxia — Code source

Projet conçu pour **Formaxia** — organisme de formation IA pour les professionnels du BTP.

---

## Fichiers livrés

| Fichier | Rôle | Comment l'ouvrir |
|---|---|---|
| `Formaxia.html` | Site marketing one-page (hero, problèmes, solution, stats, programme, témoignages, financement, formation, FAQ, à propos, contact, footer) | Ouvrir dans un navigateur — aucune dépendance locale |
| `programme.html` | Document pédagogique 16 pages A4 — programme officiel enrichi (méthode RTC, RGPD chantier, 30 prompts DCE/MT, etc.) | Ouvrir dans un navigateur ; bouton **« Imprimer en PDF »** en bas à droite |
| `tweaks-panel.jsx` | Panneau de réglages (accent secteur, URLs photos, densité) chargé par `Formaxia.html` | Doit rester **dans le même dossier** que `Formaxia.html` |
| `README.md` | Ce fichier | — |

---

## Stack technique

- **HTML5 sémantique** + **CSS3 vanilla** avec variables CSS — aucun framework
- **JavaScript vanilla** pour les animations scroll, le menu mobile, le compteur de stats, l'accordéon FAQ
- **React 18 + Babel standalone** (uniquement pour le panneau Tweaks de `Formaxia.html` — îlot isolé, ne casse pas le site si désactivé)
- **Polices Google Fonts** : Playfair Display (titres), DM Sans (texte), DM Mono (chiffres / mono)
- Toutes les dépendances sont chargées en **CDN avec SRI (integrity hash)** — aucun build, aucun `node_modules`

---

## Design system

```
--noir       #0a0a0f      /* primaire fixe */
--or         #c9a84c      /* or premium fixe */
--blanc      #f8f6f1      /* blanc cassé fixe */
--gris       #2a2a35      /* anthracite */
--accent     #E8610A      /* BTP orange — UNIQUE variable qui change par secteur */

Secteurs futurs :
  Commerce       #1A3A5C
  Agri           #1F8A5B
  Métiers bouche #7A1F1F
  Premium        #C9A84C
```

Tout le système est conçu pour qu'**une seule variable CSS** (`--accent`) suffise à adapter le site à un nouveau secteur.

---

## Déploiement

### Hébergement statique (Hostinger, Netlify, Vercel, OVH…)

1. Uploader les 3 fichiers (`Formaxia.html`, `programme.html`, `tweaks-panel.jsx`) à la racine du site
2. Renommer `Formaxia.html` en `index.html` si vous voulez qu'il s'affiche par défaut
3. C'est tout — aucune config serveur, aucun build

### Variables à compléter avant mise en ligne

Cherchez ces marqueurs dans `Formaxia.html` et remplacez par vos vraies valeurs :

- `contact@formaxia.fr` — email
- Numéro de téléphone (mentionné comme "à compléter")
- SIRET (footer)
- Liens réseaux sociaux (LinkedIn, Instagram, YouTube, WhatsApp)
- Photos placeholders (`img-01 · hero`, `img-02 · session`, `portrait · 01`) — soit éditer directement le HTML pour pointer vers vos URLs, soit utiliser le panneau Tweaks intégré

---

## Responsive

- **Desktop** ≥ 1080 px : navigation complète, hero 2 colonnes, grilles 3-5 colonnes
- **Tablette** 768–1079 px : navigation en menu hamburger, grilles 2 colonnes
- **Mobile** < 768 px : tout en 1 colonne, polices ajustées via `clamp()`

Le **document Programme** est en A4 imprimable au-dessus de 860 px ; en-dessous il passe automatiquement en lecture fluide mobile (une colonne, polices web).

---

## Pour Claude Code / un développeur

- Pas de framework, pas de build, pas de package.json
- Tous les commentaires de code sont en français
- Les sections CSS sont délimitées par des bandeaux `/* ───── SECTION ───── */`
- Les variables CSS sont toutes définies dans `:root` au début de chaque fichier
- Le bloc `TWEAK_DEFAULTS` entre commentaires `/*EDITMODE-BEGIN*/` … `/*EDITMODE-END*/` dans `Formaxia.html` contient les réglages persistés du panneau Tweaks

Bonne intégration ✨

— Formaxia · Édition 2026
