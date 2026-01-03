# CLAUDE.md – Projekt-Kontext für Claude Code

> Diese Datei gibt Claude Code den nötigen Kontext über das Projekt.

## 📋 Projekt-Übersicht

**Projektname:** Ramböck Website Framework
**Typ:** WordPress Theme Framework
**Ziel:** Wiederverwendbares, performance-optimiertes Theme für Business-Websites
**Erste Implementation:** ramboeck.it (IT-Dienstleister)

## 🎯 Projektziele

1. Custom WordPress Theme ohne Elementor/Page Builder Abhängigkeit
2. Block-basiert (Gutenberg native)
3. Lighthouse Score 90+ in allen Kategorien
4. DSGVO-konform out-of-the-box
5. SEO-optimiert mit Schema.org Markup
6. Wiederverwendbar für Kundenprojekte (60% Zeitersparnis)

## 🛠️ Tech Stack

- **WordPress 6.x** – Full Site Editing
- **PHP 8.1+**
- **Vanilla JavaScript** – Kein jQuery
- **CSS Custom Properties** – Design Tokens
- **PostCSS** – CSS Processing
- **Node.js 18+** – Build Tools

## 📁 Wichtige Dateien & Struktur

```
ramboeck-theme/
├── theme.json          # Design Tokens (Farben, Fonts, Spacing)
├── functions.php       # Haupt-Entry-Point
├── style.css          # Theme Header
├── assets/            # CSS, JS, Fonts, Images
├── blocks/            # Custom Gutenberg Blocks
├── patterns/          # Block Patterns
├── parts/             # Header, Footer (FSE)
├── templates/         # Page Templates (FSE)
├── inc/               # PHP Includes
│   ├── setup.php      # Theme Setup
│   ├── enqueue.php    # Scripts & Styles
│   ├── blocks.php     # Block Registration
│   ├── seo/           # Schema, Meta Tags
│   ├── security/      # Headers, Hardening
│   └── ai/            # Claude API Integration
└── docs/              # Dokumentation
```

## 🎨 Design System

### Farben (Ramböck.IT)
- **Primary:** #3b82f6 (Blau)
- **Secondary:** #1e40af (Dunkelblau)
- **Accent:** #f97316 (Orange) – für CTAs
- **Text:** #0f172a (Fast-Schwarz)
- **Muted:** #64748b (Grau)
- **Background:** #ffffff / #f8fafc

### Typografie
- **Font:** Inter (lokal gehostet)
- **H1:** clamp(2rem, 5vw, 3.5rem)
- **H2:** clamp(1.5rem, 4vw, 2.5rem)
- **Body:** clamp(1rem, 2vw, 1.125rem)

### Spacing
- **xs:** clamp(0.5rem, 1vw, 0.75rem)
- **sm:** clamp(0.75rem, 1.5vw, 1rem)
- **md:** clamp(1.5rem, 3vw, 2rem)
- **lg:** clamp(2rem, 5vw, 4rem)
- **xl:** clamp(3rem, 8vw, 6rem)

## ⚡ Performance-Regeln

1. **Kein jQuery** – Vanilla JS only
2. **Keine Icon Fonts** – SVG inline
3. **Fonts lokal** – Kein Google Fonts CDN
4. **Bilder:** WebP mit Fallback, Lazy Loading
5. **CSS:** Kritisch inline, Rest async
6. **JS:** Defer, minimal (< 100KB)
7. **Total Page Weight:** < 500KB

## 🔒 Security-Regeln

1. Alle Security Headers implementieren (CSP, HSTS, etc.)
2. XML-RPC deaktivieren
3. REST API User Enumeration verhindern
4. WordPress Version verstecken
5. File Editor deaktivieren

## 🇩🇪 DSGVO-Regeln

1. Keine externen Ressourcen ohne Consent (Google Fonts, Maps, YouTube)
2. Fonts immer lokal hosten
3. 2-Klick-Lösung für externe Embeds
4. Cookie Consent vor Tracking
5. Kontaktformulare mit DSE-Verweis

## 🔍 SEO-Regeln

1. Semantisches HTML (korrekte Heading-Hierarchie)
2. Schema.org Markup für: LocalBusiness, Service, FAQ, Article
3. Automatische Meta Tags (Title, Description, OG, Twitter)
4. Canonical URLs
5. Breadcrumbs mit Schema

## 📝 Code-Konventionen

### PHP
```php
<?php
/**
 * Funktionsbeschreibung
 *
 * @param string $param Beschreibung
 * @return void
 */
function ramboeck_function_name( $param ) {
    // WordPress Coding Standards
}
```

### JavaScript
```javascript
// ES6+ Module
// Keine globalen Variablen
// Event Delegation wo möglich
```

### CSS
```css
/* BEM-ähnliche Namenskonvention */
.block {}
.block__element {}
.block--modifier {}

/* CSS Custom Properties für Werte */
color: var(--color-primary);
```

### Commits
```
feat: Neue Funktion
fix: Bugfix
docs: Dokumentation
style: Formatting
refactor: Code-Refactoring
perf: Performance
```

## 🚀 Aktuelle Phase

**Phase 1: Foundation**

Nächster Task: Siehe ROADMAP.md

## 💡 Hinweise für Claude Code

1. **Immer die Roadmap konsultieren** – Tasks der Reihe nach abarbeiten
2. **WordPress Coding Standards** einhalten
3. **Performance im Blick behalten** – Jedes KB zählt
4. **DSGVO beachten** – Keine externen Ressourcen ohne Consent
5. **Semantic HTML** – Accessibility ist wichtig
6. **Testen** – Änderungen lokal testen bevor Commit
7. **Dokumentieren** – Code kommentieren, README aktuell halten

## 🔗 Ressourcen

- [WordPress Theme Handbook](https://developer.wordpress.org/themes/)
- [Block Editor Handbook](https://developer.wordpress.org/block-editor/)
- [theme.json Reference](https://developer.wordpress.org/block-editor/reference-guides/theme-json-reference/)
- [Schema.org](https://schema.org/)
- [Web.dev Performance](https://web.dev/performance/)
