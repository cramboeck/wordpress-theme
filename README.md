# Ramböck Website Framework

Ein modulares, performance-optimiertes WordPress-Theme-Framework für professionelle Business-Websites.

## Ziele

- **60% schnellere** Website-Projekte durch wiederverwendbare Komponenten
- **Lighthouse Score 90+** für Performance, SEO, Accessibility, Best Practices
- **DSGVO-konform** out-of-the-box
- **SEO-optimiert** mit Schema.org Markup

## Tech Stack

- **WordPress 6.x** – Full Site Editing
- **PHP 8.1+**
- **Vanilla JavaScript** – Kein jQuery
- **CSS Custom Properties** – Design Tokens
- **PostCSS** – CSS Processing
- **Node.js 18+** – Build Tools

## Projektstruktur

```
ramboeck-theme/
├── assets/
│   ├── css/
│   │   ├── critical.css
│   │   ├── main.css
│   │   ├── variables.css
│   │   └── components/
│   ├── js/
│   │   ├── main.js
│   │   └── modules/
│   ├── fonts/
│   ├── images/
│   └── icons/
├── blocks/
├── patterns/
├── parts/
├── templates/
├── inc/
│   ├── setup.php
│   ├── enqueue.php
│   ├── blocks.php
│   ├── patterns.php
│   ├── seo/
│   ├── security/
│   ├── performance/
│   ├── ai/
│   └── helpers/
├── languages/
├── docs/
├── theme.json
├── functions.php
└── style.css
```

## Installation

```bash
# Dependencies installieren
npm install

# Development
npm run dev

# Production Build
npm run build
```

## Roadmap

Siehe [ROADMAP.md](./ROADMAP.md)

## Lizenz

Proprietär – © 2025 Ramböck IT
