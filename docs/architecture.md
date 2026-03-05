# How Monolith Works

## The big picture

Monolith is a WordPress block plugin that intercepts WooCommerce's product-click navigation event and replaces it with an in-page quick-view modal. The rest of WooCommerce — cart, checkout, payment gateways — is left completely untouched.

---

## Boot sequence

```
Plugin activated
    │
    ├─ Block Hooks          → modal auto-inserts as last child of every
    │                          woocommerce/product-collection block
    │
    ├─ Block Variation      → "Monolith Store" appears in the block inserter
    │
    ├─ Block Style Vars     → Default / Minimal / Fullscreen registered
    │
    ├─ Block Pattern        → "Monolith Store" full-layout pattern registered
    │
    └─ Speculation Rules    → /product/* excluded from prerendering
                               (prevents wc-blocks_viewed_product misfiring)
```

## Request / interaction lifecycle

```
User visits a page with a Product Collection block
    │
    WooCommerce renders the product grid server-side
    │
    Monolith's render.php outputs the modal shell (hidden)
    │
    view.js boots and registers a listener for wc-blocks_viewed_product
    │
    User clicks a product card
    │
    WooCommerce fires wc-blocks_viewed_product (CustomEvent with productId)
    │
    Monolith's openModal generator intercepts the event
    │
    ├─ View Transitions API → product card morphs into the modal position
    │
    ├─ state.modalOpen = true → modal becomes visible via CSS class
    │
    ├─ URL hash updates  → #product-{id} (shareable, back-button aware)
    │
    ├─ Body scroll locked → html.monolith-modal-open { overflow: hidden }
    │
    └─ Store API fetch    → GET /wc/store/v1/products/{id}
           │
           ├─ Success: product data written to Interactivity API state
           │           modal content renders via data-wp-* directives
           │           focus moves into modal
           │
           └─ Error:   modal closes, URL restored, error announced to SR

User closes modal (Escape / close button / overlay click / back button)
    │
    state.modalOpen = false → modal hidden
    Body scroll restored
    URL restored
    Focus returns to the triggering product card
```

---

## Key APIs used

| API | Why |
|---|---|
| **WordPress Interactivity API** | Reactive state → DOM binding. Replaces custom event bus + vanilla JS. |
| **Block Hooks** | Auto-insert the modal block without requiring editor action. |
| **WooCommerce Store API** | Fetch product data client-side after the card click. |
| **View Transitions API** | Animate the card → modal morph natively. No JS animation library. |
| **CSS `@starting-style`** | Entry animation when `display: none` switches to `display: flex`. |
| **Speculation Rules API** | Prevent browsers prerendering product pages (WP 6.8). |
| **`theme.json` CSS variables** | Zero-config theming — colors, spacing, typography from the active theme. |

---

## State shape

The Interactivity API store (`monolith` namespace) holds:

```js
{
  modalOpen:    boolean,  // controls modal visibility
  loading:      boolean,  // true while the Store API fetch is in flight
  product:      object|null, // WooCommerce Store API product response
  announcement: string,  // aria-live region text for screen readers
}
```

Config (set server-side in `render.php`, read-only in JS):

```js
{
  apiUrl: string,   // WooCommerce Store API base URL
  nonce:  string,   // wc_store_api nonce for authenticated requests
}
```

---

## URL routing

Monolith uses the URL hash for shareability and browser history:

- **Open**: `history.pushState({ monolithProductId }, '', '#product-{id}')`
- **Close**: `history.pushState({}, '', window.location.pathname)`
- **Error**: `history.replaceState({}, '', window.location.pathname)` (no extra history entry)
- **Back button**: `popstate` listener detects missing `monolithProductId` and closes modal

On page load, if a `#product-{id}` hash is present, a synthetic `wc-blocks_viewed_product` event is dispatched to reopen the modal (shared link support).

---

## CSS strategy

All structural values (layout, position, z-index, transitions) are defined in `style.css`. All visual values (color, typography, border-radius, box-shadow, padding) are inherited from `theme.json` CSS custom properties. Monolith deliberately sets none of these, so it looks right on every block theme without any customisation.
