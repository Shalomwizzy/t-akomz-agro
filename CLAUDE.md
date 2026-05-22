# T-Akomz Agro Estates Ltd — Claude Project File

## Project Purpose
Full-stack agricultural e-commerce + corporate website for T-Akomz Agro Estates Ltd.
This is NOT a template site. It must look premium, export-grade, investor-ready.

## Tech Stack
- **Framework**: Laravel 13 (PHP 8.3+)
- **Frontend**: Blade + Alpine.js + Tailwind CSS v3
- **Auth**: Laravel Breeze (already installed) + Spatie Permissions (roles)
- **Database**: SQLite (dev) → MySQL (prod via Neon/PlanetScale or VPS)
- **ORM**: Eloquent
- **Image Processing**: Intervention Image v4 (installed)
- **PDF**: barryvdh/laravel-dompdf (installed)
- **Excel**: maatwebsite/excel (installed)
- **Permissions**: spatie/laravel-permission (installed)
- **Sitemap**: spatie/laravel-sitemap (installed)
- **Payments**: Paystack (primary) + Flutterwave (secondary)
- **Email**: Laravel Mail (SMTP/Mailgun in prod)
- **Queue**: Database queue (dev) → Redis (prod)
- **Build**: Vite + npm

## Brand Identity
### Colors
```
Primary Green:   #B8F397   (main brand green from logo — bright lime/mint)
Dark Green:      #6FAE4B   (hover states, CTAs, deep green)
Background:      #050505   (primary background — near-black)
Dark Surface:    #111111   (cards, panels)
Dark Elevated:   #1A1A1A   (modals, sidebars)
Border:          #2A2A2A   (dividers, subtle borders)
Text Primary:    #F5F5F5   (headings on dark)
Text Secondary:  #CFCFCF   (subtext, labels)
Text Muted:      #666666   (placeholders)
Warning:         #F5A623
Error:           #E53935
Info:            #29B6F6
```

### Typography
- Display/Headings: **Playfair Display** (Google Fonts)
- Body/UI: **DM Sans** (Google Fonts)
- Mono (order IDs): **JetBrains Mono** (Google Fonts)

### Logo
- Dark background only (green logo on black)
- Minimum 120px wide, clear space = 1x height on all sides
- File: `public/images/logo-mark.svg` (SVG with hardcoded #B8F397 strokes)
- **Light mode fix**: always add class `logo-img` to `<img>` tags using the logo — CSS applies `filter: brightness(0.42) saturate(1.8)` to darken it in light mode
- Logo in AI admin badge uses same SVG via `<img class="logo-img">`

## Architecture Decisions
- App Router: Laravel routes in `routes/web.php` + `routes/api.php`
- Roles: CUSTOMER | ADMIN | SUPER_ADMIN (via Spatie permissions)
- Cart: Session-based for guests, DB `cart_items` for logged-in users
- Order numbers: Format `TAK-YYYY-NNNNN` (e.g. TAK-2024-00001)
- Currency: Nigerian Naira (₦) — format via `number_format()` helpers
- Images: stored in `storage/app/public/` (symlinked to `public/storage/`)
- Slug generation: use `Str::slug()` on save
- Blade components in `resources/views/components/`
- All admin routes prefixed: `/admin` — middleware: `auth` + role check
- All customer routes prefixed: `/account` — middleware: `auth`

## Project Structure (Key Paths)
```
app/
  Http/
    Controllers/
      Admin/           ← all admin controllers
      Auth/            ← Breeze auth (keep as-is)
      Shop/            ← public shop controllers
      Account/         ← customer account controllers
    Middleware/
      AdminMiddleware.php
  Models/              ← all Eloquent models
  Services/
    PaystackService.php
    FlutterwaveService.php
    CartService.php
    OrderService.php

resources/
  views/
    layouts/
      app.blade.php    ← PUBLIC master layout (dark theme)
      admin.blade.php  ← ADMIN master layout
      auth.blade.php   ← Auth pages layout
    components/
      navbar.blade.php
      footer.blade.php
      product-card.blade.php
      cart-drawer.blade.php
      ...
    pages/
      home.blade.php
      shop/
      product/
      cart.blade.php
      checkout/
      account/
      admin/
      blog/

database/
  migrations/          ← all schema migrations
  seeders/
    DatabaseSeeder.php ← master seeder
    AdminSeeder.php
    CategorySeeder.php
    ProductSeeder.php
    SiteSettingSeeder.php
    BlogPostSeeder.php
    CouponSeeder.php

routes/
  web.php              ← all routes
  api.php              ← API routes for AJAX/payment webhooks
```

## Database Tables
| Table | Purpose |
|-------|---------|
| users | All users (customers + admins) |
| model_has_roles | Spatie roles pivot |
| categories | Product categories (Poultry, Eggs, etc.) |
| products | Farm products |
| product_images | Multiple images per product |
| orders | Customer orders |
| order_items | Line items per order |
| order_timelines | Status history per order |
| cart_items | Server-side cart for logged-in users |
| addresses | Saved delivery addresses |
| reviews | Product reviews (moderated) |
| wishlist_items | Customer wishlists |
| blog_posts | Blog/news articles |
| coupons | Discount codes |
| newsletter_subscribers | Email list |
| site_settings | Key-value store for admin settings |

## Roles & Permissions
- **CUSTOMER**: shop, cart, checkout, account dashboard, orders, wishlist
- **ADMIN**: full admin panel access except settings
- **SUPER_ADMIN**: everything including settings, user management

## Payment Flow
### Paystack (Primary — Nigeria)
1. POST `/checkout` → create Order (PENDING, UNPAID)
2. Initialize Paystack transaction → get authorization_url
3. Redirect to Paystack hosted page
4. Paystack webhook → POST `/api/payment/paystack/webhook`
5. Verify signature → update Order (PAID, CONFIRMED)
6. Send confirmation email + SMS
7. Redirect → `/checkout/success?order=TAK-xxxx`

### Flutterwave (Secondary)
Similar flow using Flutterwave's hosted page.

### Cash on Delivery
Order created with PENDING payment status. Admin confirms manually.

## Build Phases
### Phase 1 — Foundation (CURRENT)
- [x] CLAUDE.md + BLUEPRINT.md created
- [ ] Tailwind brand config
- [ ] All migrations
- [ ] All models
- [ ] Seeders
- [ ] Master layouts

### Phase 2 — Public Website
- [ ] Homepage (all sections)
- [ ] Shop listing page
- [ ] Category page
- [ ] Product detail page
- [ ] Cart page
- [ ] Checkout (3-step)
- [ ] Order success page
- [ ] Order tracking (public)
- [ ] About, Contact, Blog, Farm Tour pages
- [ ] Newsletter subscription

### Phase 3 — Customer Account
- [x] Account dashboard (premium redesign — stats, recent orders, quick actions, mobile tabs)
- [ ] Order history + detail
- [x] Wishlist (page + navbar icon with count badge — desktop + mobile)
- [x] Saved addresses (controller + view exists)
- [x] Profile + password (controller + view exists)

### Phase 4 — Admin Panel
- [x] Admin dashboard (premium redesign — KPI stat cards, chart-ready layout)
- [x] Products CRUD (full — create/edit/delete, images, nutrition facts, AI content generation)
- [x] Categories CRUD
- [x] Orders management
- [ ] Customer list + detail
- [ ] Blog CRUD (rich text)
- [ ] Inventory management
- [ ] Promotions/Coupons
- [ ] Delivery management
- [ ] Analytics
- [x] Settings (site settings key-value — address, phone, email, WhatsApp, banner)
- [x] AI Assistant tools in admin (product content, business plan, blog, sales insights, chat)

### Phase 5 — Integrations
- [x] Paystack payment (full flow: init → hosted page → webhook verify → order update)
- [x] Flutterwave payment (full flow)
- [x] Email notifications (order confirm with PDF attached, dispatch, delivery, welcome, new product alert)
- [x] SMS notifications (Termii — Nigerian number normalization, wired to order status changes)
- [x] PDF invoice generation (dompdf — customer download + admin download + auto-attached to confirmation email)
- [ ] Excel export (orders, products)
- [ ] Sitemap + SEO meta

### Completed Beyond Original Phases
- [x] Premium dark theme homepage (mobile-first, 100svh hero, animated stats, "How It Works" dual layout)
- [x] Public AI chat widget (GROQ primary + Gemini fallback, product suggestion cards)
- [x] Admin AI service (generateProductContent, generateBusinessPlan, analyzeSalesInsights, generateBlogPost)
- [x] Light/dark theme toggle (persisted via localStorage, instant switch, logo filter fix)
- [x] Logo `.logo-img` class — CSS filter darkens SVG in light mode (`brightness(0.42) saturate(1.8)`)
- [x] Favicon + apple-touch-icon + theme-color across all layouts
- [x] Wishlist navbar icon (desktop + mobile bottom nav) — shows only when user has saved items
- [x] Cart icon green tint in navbar (desktop + mobile)
- [x] Auth redirect fix — all Breeze controllers now redirect to `account.dashboard` (not undefined `dashboard`)
- [x] Farm location corrected everywhere: Oke-Ido Road, Ido Ekiti, Ekiti State, Nigeria
- [x] Product edit form — fixed field mapping (tags_input, weight, nutrition_facts, slug)
- [x] Nutrition facts PHP 8 null-safety fix (`$nf = $product->nutrition_facts ?? []`)
- [x] Admin panel premium redesign (glassmorphism header, gradient sidebar, animated active indicator, section labels)
- [x] Guest layout (login/register) — premium split-panel design with brand panel
- [x] PWA — manifest.json, service worker (cache-first assets, network-first pages, offline fallback), generated icons via `php artisan pwa:icons`
- [x] Welcome email on registration, new product alert to subscribers + customers
- [x] Staff/Customer separation in admin — `/admin/users` = staff only, `/admin/customers` = customers
- [x] AI chat widget replaced WhatsApp floating button — public AI assistant (GROQ + Gemini) handles all customer queries
- [x] Farm Financial Control System — Wallet ledger, expense request/approval/rejection workflow, direct expense logging, budget allocation, category breakdowns
- [x] Worker management — CRUD, attendance tracking, payroll generation + approval + payment flow, salary payment records
- [x] Internal mail system — 9 automatic transactional mails + 2 admin broadcast interfaces:
  - Auto: expense request alert to all admins, expense approved/rejected to manager, direct expense alert to admins
  - Auto: salary paid notification to worker (email), worker welcome onboard email
  - Auto: all existing customer mails (order confirm, dispatch, delivery, payment failed, welcome, new product, low stock)
  - Broadcast: Mail Workers/Staff page (`/admin/mail/workers`) — target all workers, all staff, or specific individual
  - Broadcast: Mail Customers page (`/admin/mail/customers`) — target all customers or hand-pick from searchable list
  - Workers table has `email` field (nullable) — welcome + salary mails only send if email is set
- [x] Security hardening — SecurityHeaders middleware, receipt files on private disk, blog slug preserved on update, checkout success IDOR fix, ProfileController null-password fix, MySQL-compatible revenue chart, AddressController::update() added, payment secrets never stored in DB

## Key Commands
```bash
# Development
php artisan serve            # start Laravel server
npm run dev                  # start Vite (CSS/JS hot reload)

# Database
php artisan migrate          # run migrations
php artisan migrate:fresh --seed  # fresh DB with seed data
php artisan db:seed          # seed only

# Storage
php artisan storage:link     # symlink storage to public

# Tinker
php artisan tinker
```

## Environment Variables Needed (beyond defaults)
```
APP_NAME="T-Akomz Agro Estates"
APP_URL=http://localhost:8000

PAYSTACK_PUBLIC_KEY=
PAYSTACK_SECRET_KEY=
PAYSTACK_PAYMENT_URL=https://api.paystack.co

FLUTTERWAVE_PUBLIC_KEY=
FLUTTERWAVE_SECRET_KEY=
FLUTTERWAVE_SECRET_HASH=

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=noreply@takomzagro.com
MAIL_FROM_NAME="T-Akomz Agro Estates"

TERMII_API_KEY=
TERMII_SENDER_ID=TAKOMZ
```

## Critical Rules (always follow)
1. **Dark theme everywhere** — background #050505, never white/light backgrounds on public pages
2. **Alpine.js for interactivity** — no jQuery, no Vue unless explicitly decided
3. **No admin panel template** — build custom admin UI with Tailwind
4. **Currency**: always render as `₦X,XXX` format
5. **Images**: always use `<img>` with lazy loading, or Blade components
6. **CSRF**: always include `@csrf` in forms
7. **Admin auth**: every admin controller must check `auth()->user()->hasRole(['ADMIN','SUPER_ADMIN'])`
8. **Input validation**: always validate in Form Requests (app/Http/Requests/)
9. **Paystack webhook**: verify `x-paystack-signature` HMAC-SHA512 before processing
10. **Order number**: generate as `TAK-` + year + `-` + zero-padded 5-digit ID
11. **Slugs**: auto-generate from name on create, never overwrite on update unless explicitly changed
12. **Low stock threshold**: warn when stock ≤ `low_stock_threshold` (default 10)
13. **Session cart**: key = `cart`, stored as array of `[product_id => quantity]`
14. **Merge carts on login**: guest session cart merges into DB cart on login

## Sample Product Categories
1. Poultry (chicken, turkey, duck)
2. Eggs (table eggs, fertilized, quail)
3. Livestock (goat, sheep, pig)
4. Crop Produce (maize, cassava, yam, vegetables)
5. Dairy (milk, yogurt)
6. Organic Inputs (compost, seedlings)
7. Farm Subscription Boxes (weekly delivery)

## Contact & Business Info
- Business: T-Akomz Agro Estates Ltd
- Suggested domain: takomzagro.com / takomzagro.ng
- Admin email: admin@takomzagro.com
- WhatsApp: configured via site_settings
