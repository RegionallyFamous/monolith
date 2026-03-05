# Monolith

**Turn your WooCommerce store into a single-page shopping experience — in minutes, with zero configuration.**

Customers browse products, peek at details, and add to cart without ever leaving the page. No JavaScript framework. No custom theme required. Just activate and go.

[![Try the live demo](https://img.shields.io/badge/Try%20the%20live%20demo-%E2%86%92-blue?logo=wordpress&style=for-the-badge)](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/RegionallyFamous/monolith/main/blueprint.json)

---

## What it does

When a customer clicks a product, Monolith catches that click and opens a quick-view modal over the page — complete with name, price, description, and an Add to Cart button. No navigation. No reload.

When they close the modal, they're back exactly where they were. When they're ready to buy, checkout happens on WooCommerce's standard `/checkout` page — every payment gateway, every order flow, completely untouched.

---

## Why Monolith

**Zero configuration.** Install and activate. Monolith automatically attaches to every Product Collection block on your site via WordPress Block Hooks. No editor setup. No shortcodes. No template editing.

**Looks exactly like your theme.** Monolith inherits your theme's colors, fonts, and spacing from `theme.json`. It doesn't ship a design — it adopts yours.

**Three built-in modal styles.** Switch between Default, Minimal, and Fullscreen from the Editor's Style panel without touching code. Each adapts to your theme automatically.

**One-click store layout.** Use the **Monolith Store** block pattern to drop a complete storefront — filters, product grid, and mini cart — onto any page in a single click.

**Smooth, native animations.** Product cards morph into the modal using the browser-native View Transitions API. CSS `@starting-style` handles the entry animation. No animation library, no jank.

**Accessible from day one.** Full keyboard navigation, focus trap, Escape to close, screen-reader live announcements, and WCAG 2.1 AA compliant focus indicators throughout.

**Works great on every device.** Full-screen modal on mobile with safe-area insets for notched iPhones. The mini cart becomes a full-height side drawer. Scroll-locked background. No horizontal overflow.

---

## Requirements

| | Minimum |
|---|---|
| WordPress | 6.8+ |
| WooCommerce | 10.4+ |
| PHP | 8.0+ |
| Theme | Any block theme |

---

## Installation

1. Download the latest release `.zip`.
2. Go to **Plugins → Add New → Upload Plugin**, select the file, and install.
3. Activate — that's it.

> **Want to try first?** [Open the live Playground demo →](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/RegionallyFamous/monolith/main/blueprint.json)

---

## Setting up your store

**Already using Product Collection blocks?** Nothing to do. The quick-view modal is already active on every one.

**Starting from scratch?** Open the Block Editor, press **+**, search for **Monolith Store**, and insert the pattern. You get a complete layout — filters, product grid, and mini cart — in one click.

---

## Documentation

- [How it works](docs/architecture.md)
- [Local development](docs/development.md)
- [File structure](docs/file-structure.md)

---

## License

GPL v2 or later — [gnu.org/licenses/gpl-2.0](https://www.gnu.org/licenses/gpl-2.0.html).
