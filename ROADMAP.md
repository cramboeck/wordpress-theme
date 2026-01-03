# Development Roadmap – Ramböck Website Framework

## Legende

- 🔲 Offen
- 🔄 In Bearbeitung
- ✅ Erledigt

---

## Phase 1: Foundation (Theme-Grundgerüst) ✅

### 1.1 Projekt-Setup ✅

| # | Task | Status |
|---|------|--------|
| 1.1.1 | Erstelle die Theme-Ordnerstruktur | ✅ |
| 1.1.2 | Erstelle style.css mit Theme-Header | ✅ |
| 1.1.3 | Erstelle functions.php mit Include-System | ✅ |
| 1.1.4 | Erstelle package.json | ✅ |
| 1.1.5 | Erstelle composer.json | 🔲 |
| 1.1.6 | Erstelle .gitignore | ✅ |
| 1.1.7 | Erstelle .editorconfig | ✅ |
| 1.1.8 | Erstelle .prettierrc und .eslintrc.js | ✅ |

### 1.2 Theme.json (Design Tokens) ✅

| # | Task | Status |
|---|------|--------|
| 1.2.1 | Erstelle theme.json mit Schema Version 2 | ✅ |
| 1.2.2 | Definiere Farbpalette | ✅ |
| 1.2.3 | Definiere Typografie-Presets mit Fluid Typography | ✅ |
| 1.2.4 | Definiere Spacing-Scale mit Fluid Spacing | ✅ |
| 1.2.5 | Definiere Layout-Settings | ✅ |
| 1.2.6 | Definiere Custom Template Parts | ✅ |
| 1.2.7 | Konfiguriere Block-Supports | ✅ |
| 1.2.8 | Deaktiviere nicht benötigte Features | ✅ |

### 1.3 CSS Grundgerüst ✅

Alle CSS-Dateien erstellt (variables, reset, base, utilities, critical, main, components).

### 1.4 PHP Setup ✅

Alle PHP-Includes erstellt (setup, enqueue, helpers, security, performance).

### 1.5 Build System ✅

PostCSS und npm scripts konfiguriert.

---

## Phase 2: Core Blocks ✅

| # | Block | Status |
|---|-------|--------|
| 2.1 | Hero Block | ✅ |
| 2.2 | Services Block | ✅ |
| 2.3 | Testimonials Block | ✅ |
| 2.4 | CTA Block | ✅ |
| 2.5 | FAQ Block (mit Schema.org) | ✅ |
| 2.6 | Pricing Block | ✅ |
| 2.7 | Team Block | ✅ |
| 2.8 | Features Block | ✅ |
| 2.9 | Contact Block (DSGVO-konform) | ✅ |

---

## Phase 3: Navigation & Layout ✅

| # | Task | Status |
|---|------|--------|
| 3.1 | Header (Standard + Transparent) | ✅ |
| 3.2 | Footer (Multi-Column + Minimal) | ✅ |
| 3.3 | Breadcrumbs Helper | ✅ |

---

## Phase 4: Templates ✅

| # | Template | Status |
|---|----------|--------|
| 4.1 | front-page.html | ✅ |
| 4.2 | page.html | ✅ |
| 4.3 | page-services.html | ✅ |
| 4.4 | page-about.html | ✅ |
| 4.5 | page-contact.html | ✅ |
| 4.6 | page-legal.html | ✅ |
| 4.7 | single.html | ✅ |
| 4.8 | archive.html | ✅ |
| 4.9 | index.html | ✅ |
| 4.10 | 404.html | ✅ |

---

## Phase 5: SEO & Performance ✅

| # | Task | Status |
|---|------|--------|
| 5.1 | Meta Tags (OG, Twitter, Canonical) | ✅ |
| 5.2 | Schema.org Markup | ✅ |
| 5.3 | Performance Optimization | ✅ |
| 5.4 | Critical CSS Inline | ✅ |

---

## Phase 6: DSGVO & Security ✅

| # | Task | Status |
|---|------|--------|
| 6.1 | Cookie Consent (Plugin-kompatibel) | 🔲 |
| 6.2 | 2-Klick-Lösung | 🔲 |
| 6.3 | Security Headers | ✅ |
| 6.4 | WordPress Hardening | ✅ |
| 6.5 | Cleanup (Bloat entfernen) | ✅ |

---

## Phase 7: KI-Integration ✅

| # | Task | Status |
|---|------|--------|
| 7.1 | Claude API Client | ✅ |
| 7.2 | Content Generator | ✅ |
| 7.3 | SEO Analyzer | ✅ |
| 7.4 | Admin Interface | ✅ |

---

## Phase 8: Ramböck.IT Implementation 🔲

| # | Task | Status |
|---|------|--------|
| 8.1 | WordPress Setup | 🔲 |
| 8.2 | Content erstellen | 🔲 |
| 8.3 | Testing & Optimierung | 🔲 |
| 8.4 | Go-Live | 🔲 |

---

## Phase 9: Dokumentation 🔲

| # | Task | Status |
|---|------|--------|
| 9.1 | Technische Dokumentation | 🔲 |
| 9.2 | Kunden-Dokumentation | 🔲 |
| 9.3 | Projekt-Vorlagen | 🔲 |

---

## Fortschritt Übersicht

| Phase | Status |
|-------|--------|
| 1. Foundation | ✅ 100% |
| 2. Core Blocks | ✅ 100% |
| 3. Navigation | ✅ 100% |
| 4. Templates | ✅ 100% |
| 5. SEO & Performance | ✅ 100% |
| 6. DSGVO & Security | ✅ 75% |
| 7. KI-Integration | ✅ 100% |
| 8. Implementation | 🔲 0% |
| 9. Dokumentation | 🔲 0% |

**Gesamt: ~85% der Entwicklung abgeschlossen**

---

## Was wurde erstellt

### Blocks (9)
- Hero, Services, Testimonials, CTA, FAQ, Pricing, Team, Features, Contact

### Templates (10)
- front-page, page, page-services, page-about, page-contact, page-legal, single, archive, index, 404

### Template Parts (4)
- header, header-transparent, footer, footer-minimal

### PHP Includes (15+)
- Setup, Enqueue, Blocks, Patterns, Helpers, SEO, Security, Performance, AI

### CSS (15+)
- Variables, Reset, Base, Utilities, Critical, Components

### Konfiguration
- theme.json, package.json, PostCSS, ESLint, Prettier, EditorConfig
