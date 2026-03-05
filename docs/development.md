# Local Development

## Prerequisites

- [Node.js](https://nodejs.org/) 20+
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (for `wp-env`)
- [Composer](https://getcomposer.org/) (for PHP linting only)

---

## First-time setup

```bash
# Clone into your projects folder (anywhere — not inside WordPress)
git clone https://github.com/RegionallyFamous/monolith.git
cd monolith

# Install Node dependencies
npm install

# Start a local WordPress + WooCommerce environment (requires Docker)
npx wp-env start
```

WordPress will be available at:
- **Front end**: http://localhost:8888
- **Admin**: http://localhost:8888/wp-admin — `admin` / `password`

---

## Development workflow

```bash
# Watch JS and CSS for changes (outputs to build/)
npm run start
```

Edit files in `src/`. Changes compile to `build/` automatically. Hard-refresh the browser to pick them up.

---

## All commands

| Command | What it does |
|---|---|
| `npm run build` | Production build — minified, versioned assets to `build/` |
| `npm run start` | Development watch — rebuilds on every save |
| `npm run lint:js` | ESLint (WordPress coding standards) |
| `npm run lint:css` | Stylelint (WordPress coding standards) |
| `npm run lint:php` | PHPCS (WordPress Coding Standards) |
| `npm run i18n:make` | Generate `languages/monolith.pot` translation template |
| `npx wp-env start` | Start the local Docker environment |
| `npx wp-env stop` | Stop the local Docker environment |
| `npx wp-env destroy` | Wipe the environment (database included) |

---

## Environment config

The local environment is configured in `.wp-env.json`. It maps the project root as a plugin and uses the latest stable WordPress and WooCommerce.

---

## Making changes to PHP render files

`src/render.php` is the source of truth. When you run `npm run build`, `@wordpress/scripts` copies PHP files into `build/` automatically — you don't need to copy them manually.

> Both `src/render.php` and `build/render.php` are committed to the repo so the WordPress Playground demo (`blueprint.json`) works without a build step.

---

## Testing in WordPress Playground

The `blueprint.json` file sets up a full demo environment (WooCommerce + products + Twenty Twenty-Five) in the browser. To test your local changes against Playground you'll need to push to GitHub first — Playground fetches the plugin from the repo's `main` branch.
