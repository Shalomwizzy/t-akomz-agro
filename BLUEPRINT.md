# T-Akomz Agro Estates Ltd — Full Website Blueprint
> Reference document. Tech stack: Laravel 13 + Blade + Alpine.js + Tailwind CSS.

---

## 0. Brand Identity

### Color Palette
| Token | Hex | Usage |
|-------|-----|-------|
| Primary Green | `#B8F397` | Main brand color, buttons, badges, accents |
| Dark Green | `#6FAE4B` | Hover states, active CTAs, deep accents |
| Background | `#050505` | Primary page background |
| Dark Surface | `#111111` | Cards, panels, product cards |
| Dark Elevated | `#1A1A1A` | Modals, dropdowns, sidebars |
| Border | `#2A2A2A` | Dividers, card borders |
| Text Primary | `#F5F5F5` | Headings, important text |
| Text Secondary | `#CFCFCF` | Body copy, descriptions |
| Text Muted | `#666666` | Placeholders, disabled |
| Warning | `#F5A623` | Low stock, alerts |
| Error | `#E53935` | Errors, cancelled |
| Info | `#29B6F6` | Info badges |

### Typography
- **Playfair Display** — hero headings, section titles, product names
- **DM Sans** — body text, labels, UI elements
- **JetBrains Mono** — order IDs, codes, prices (optional)

---

## 1. Tech Stack
- Laravel 13 + PHP 8.3
- Blade templating + Alpine.js (x-data, x-show, x-transition)
- Tailwind CSS v3 (custom config with brand tokens)
- Vite for asset bundling
- Spatie Laravel Permission (roles: CUSTOMER, ADMIN, SUPER_ADMIN)
- Paystack (primary payment) + Flutterwave (secondary)
- Laravel Mail + Queue for emails
- Intervention Image for uploads
- DomPDF for invoice PDFs
- Maatwebsite Excel for exports

---

## 2. Database Schema

### users
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| name | string | |
| email | string unique | |
| phone | string nullable | |
| avatar | string nullable | image path |
| password | string | hashed |
| email_verified_at | timestamp nullable | |
| remember_token | string | |
| created_at / updated_at | timestamps | |

Roles via Spatie: CUSTOMER, ADMIN, SUPER_ADMIN

### categories
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| name | string unique | |
| slug | string unique | auto-generated |
| description | text nullable | |
| image | string nullable | storage path |
| is_active | boolean | default true |
| sort_order | integer | default 0 |
| created_at / updated_at | timestamps | |

### products
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| category_id | FK → categories | |
| name | string | |
| slug | string unique | |
| description | longtext | |
| short_description | string nullable | max 250 chars |
| price | decimal(10,2) | current price |
| compare_price | decimal(10,2) nullable | original (crossed out) |
| wholesale_price | decimal(10,2) nullable | bulk price |
| unit | string | "per kg", "per crate", "each", etc. |
| sku | string unique nullable | |
| stock | integer | default 0 |
| low_stock_threshold | integer | default 10 |
| is_active | boolean | default true |
| is_featured | boolean | default false |
| is_organic | boolean | default false |
| weight | decimal nullable | grams |
| tags | json nullable | array of tags |
| nutrition_facts | json nullable | |
| meta_title | string nullable | |
| meta_description | string nullable | |
| created_at / updated_at | timestamps | |

### product_images
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| product_id | FK → products CASCADE | |
| url | string | storage path |
| alt_text | string nullable | |
| is_primary | boolean | default false |
| sort_order | integer | default 0 |

### orders
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| order_number | string unique | TAK-YYYY-NNNNN |
| user_id | FK → users nullable | null = guest |
| customer_name | string | |
| customer_email | string | |
| customer_phone | string | |
| delivery_address | string | |
| delivery_city | string | |
| delivery_state | string | |
| delivery_notes | text nullable | |
| delivery_type | enum | PICKUP, STANDARD, EXPRESS |
| delivery_fee | decimal(10,2) | |
| subtotal | decimal(10,2) | |
| discount | decimal(10,2) | default 0 |
| total | decimal(10,2) | |
| coupon_code | string nullable | |
| status | enum | PENDING, CONFIRMED, PROCESSING, PACKED, DISPATCHED, OUT_FOR_DELIVERY, DELIVERED, CANCELLED, REFUNDED |
| payment_status | enum | UNPAID, PAID, PARTIALLY_PAID, REFUNDED |
| payment_method | string nullable | paystack, flutterwave, cod, bank_transfer |
| payment_reference | string nullable | gateway ref |
| notes | text nullable | admin notes |
| created_at / updated_at | timestamps | |

### order_items
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| order_id | FK → orders CASCADE | |
| product_id | FK → products | |
| product_name | string | snapshot |
| product_unit | string | snapshot |
| quantity | integer | |
| unit_price | decimal(10,2) | snapshot |
| total_price | decimal(10,2) | |

### order_timelines
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| order_id | FK → orders CASCADE | |
| status | string | |
| note | text nullable | |
| created_at | timestamp | |

### cart_items
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| user_id | FK → users CASCADE | |
| product_id | FK → products CASCADE | |
| quantity | integer | |
| created_at / updated_at | timestamps | |
| UNIQUE | user_id + product_id | |

### addresses
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| user_id | FK → users CASCADE | |
| label | string | Home, Office, etc. |
| full_name | string | |
| phone | string | |
| address_line1 | string | |
| address_line2 | string nullable | |
| city | string | |
| state | string | |
| is_default | boolean | default false |

### reviews
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| product_id | FK → products CASCADE | |
| user_id | FK → users CASCADE | |
| rating | tinyint | 1-5 |
| title | string nullable | |
| body | text | |
| is_approved | boolean | default false |
| created_at / updated_at | timestamps | |

### wishlist_items
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| user_id | FK → users CASCADE | |
| product_id | FK → products CASCADE | |
| created_at | timestamp | |
| UNIQUE | user_id + product_id | |

### blog_posts
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| title | string | |
| slug | string unique | |
| excerpt | text | |
| content | longtext | HTML/rich text |
| cover_image | string nullable | |
| author_name | string | |
| category | string nullable | Farming Tips, Nutrition, News, etc. |
| tags | json nullable | |
| is_published | boolean | default false |
| published_at | timestamp nullable | |
| meta_title | string nullable | |
| meta_description | string nullable | |
| views | integer | default 0 |
| created_at / updated_at | timestamps | |

### coupons
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| code | string unique | |
| type | enum | PERCENTAGE, FIXED, FREE_DELIVERY |
| value | decimal(10,2) | |
| min_order_value | decimal(10,2) nullable | |
| max_uses | integer nullable | null = unlimited |
| used_count | integer | default 0 |
| valid_from | timestamp | |
| valid_until | timestamp | |
| is_active | boolean | default true |
| created_at / updated_at | timestamps | |

### newsletter_subscribers
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| email | string unique | |
| is_active | boolean | default true |
| subscribed_at | timestamp | |

### site_settings
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| key | string unique | |
| value | longtext | |
| updated_at | timestamp | |

**Default setting keys:**
- `delivery_fee_standard` → 1500
- `delivery_fee_express` → 3500
- `delivery_fee_pickup` → 0
- `min_order_amount` → 2000
- `contact_phone` → phone number
- `contact_email` → email
- `contact_address` → farm address
- `whatsapp_number` → WhatsApp number
- `social_instagram` → Instagram URL
- `social_facebook` → Facebook URL
- `social_twitter` → Twitter URL
- `social_youtube` → YouTube URL
- `banner_text` → announcement text
- `banner_active` → 0|1
- `maintenance_mode` → 0|1
- `cod_allowed_states` → JSON array of states
- `bank_account_name` → bank name
- `bank_account_number` → account number
- `bank_name` → bank name

---

## 3. All Routes

### Public Routes
```
GET  /                         → HomeController@index
GET  /about                    → AboutController@index
GET  /contact                  → ContactController@index
POST /contact                  → ContactController@send
GET  /farm-tour                → FarmTourController@index
GET  /blog                     → BlogController@index
GET  /blog/{slug}              → BlogController@show
GET  /shop                     → ShopController@index
GET  /shop/{category}          → ShopController@category
GET  /shop/{category}/{slug}   → ShopController@product
GET  /cart                     → CartController@index
POST /cart/add                 → CartController@add
POST /cart/update              → CartController@update
POST /cart/remove              → CartController@remove
POST /cart/clear               → CartController@clear
POST /coupon/apply             → CartController@applyCoupon
GET  /checkout                 → CheckoutController@index
POST /checkout                 → CheckoutController@store
GET  /checkout/success         → CheckoutController@success
GET  /orders/track             → OrderTrackingController@index
POST /orders/track             → OrderTrackingController@track
POST /newsletter/subscribe     → NewsletterController@subscribe
GET  /faq                      → PageController@faq
GET  /services                 → PageController@services
GET  /gallery                  → PageController@gallery
GET  /privacy-policy           → PageController@privacy
GET  /terms                    → PageController@terms
GET  /refund-policy            → PageController@refund
GET  /shipping-policy          → PageController@shipping
```

### Payment Routes (API)
```
POST /api/payment/paystack/initiate  → PaystackController@initiate
GET  /api/payment/paystack/verify    → PaystackController@verify
POST /api/payment/paystack/webhook   → PaystackController@webhook
POST /api/payment/flutterwave/initiate  → FlutterwaveController@initiate
GET  /api/payment/flutterwave/verify    → FlutterwaveController@verify
POST /api/payment/flutterwave/webhook   → FlutterwaveController@webhook
```

### Customer Account Routes (auth middleware)
```
GET  /account                  → Account\DashboardController@index
GET  /account/orders           → Account\OrderController@index
GET  /account/orders/{id}      → Account\OrderController@show
GET  /account/wishlist         → Account\WishlistController@index
POST /account/wishlist/toggle  → Account\WishlistController@toggle
GET  /account/addresses        → Account\AddressController@index
POST /account/addresses        → Account\AddressController@store
PUT  /account/addresses/{id}   → Account\AddressController@update
DELETE /account/addresses/{id} → Account\AddressController@destroy
GET  /account/profile          → Account\ProfileController@edit
PUT  /account/profile          → Account\ProfileController@update
POST /reviews                  → ReviewController@store
```

### Admin Routes (admin middleware)
```
GET  /admin                            → Admin\DashboardController@index
GET  /admin/products                   → Admin\ProductController@index
GET  /admin/products/create            → Admin\ProductController@create
POST /admin/products                   → Admin\ProductController@store
GET  /admin/products/{id}/edit         → Admin\ProductController@edit
PUT  /admin/products/{id}              → Admin\ProductController@update
DELETE /admin/products/{id}            → Admin\ProductController@destroy

GET  /admin/categories                 → Admin\CategoryController@index
GET  /admin/categories/create          → Admin\CategoryController@create
POST /admin/categories                 → Admin\CategoryController@store
PUT  /admin/categories/{id}            → Admin\CategoryController@update
DELETE /admin/categories/{id}          → Admin\CategoryController@destroy

GET  /admin/orders                     → Admin\OrderController@index
GET  /admin/orders/{id}                → Admin\OrderController@show
PUT  /admin/orders/{id}/status         → Admin\OrderController@updateStatus

GET  /admin/customers                  → Admin\CustomerController@index
GET  /admin/customers/{id}             → Admin\CustomerController@show

GET  /admin/blog                       → Admin\BlogController@index
GET  /admin/blog/create                → Admin\BlogController@create
POST /admin/blog                       → Admin\BlogController@store
GET  /admin/blog/{id}/edit             → Admin\BlogController@edit
PUT  /admin/blog/{id}                  → Admin\BlogController@update
DELETE /admin/blog/{id}                → Admin\BlogController@destroy

GET  /admin/promotions                 → Admin\PromotionController@index
POST /admin/promotions                 → Admin\PromotionController@store
PUT  /admin/promotions/{id}            → Admin\PromotionController@update
DELETE /admin/promotions/{id}          → Admin\PromotionController@destroy

GET  /admin/inventory                  → Admin\InventoryController@index
PUT  /admin/inventory/{id}             → Admin\InventoryController@update

GET  /admin/analytics                  → Admin\AnalyticsController@index
GET  /admin/deliveries                 → Admin\DeliveryController@index
GET  /admin/settings                   → Admin\SettingsController@index
PUT  /admin/settings                   → Admin\SettingsController@update

POST /admin/upload                     → Admin\UploadController@store
```

---

## 4. Page Specifications

### PAGE 1: Homepage (/)

**Hero Section**
- Full viewport height
- Background: #050505 with animated particle overlay OR farm video (muted, looped)
- Logo centered top
- Headline: "Farm Fresh. Delivered to Your Door." — Playfair Display 72px
- Subheadline: "Premium poultry, eggs, livestock & organic produce"
- CTAs: [Shop Now] (green filled) + [Explore Our Farm] (green outline)
- Scroll indicator arrow (animated)

**Stats Bar**
- 4 stats strip (dark green bg):
  🐔 500+ Chickens | 🥚 1,000 Eggs Daily | 🌿 50 Acres Farm | ⭐ 200+ Happy Customers

**Category Grid**
- "What Are You Looking For?"
- 6–8 category cards, 3-col (desktop), 2-col (mobile)
- Each: full image bg, category name, product count, hover zoom + green overlay

**Featured Products**
- "Fresh From The Farm Today"
- 4-col grid (desktop), horizontal scroll (mobile)
- Product cards: image, name, price, unit, Add to Cart, stock badge
- "View All Products" link

**Why Choose Us**
- 4 USP cards: 🌱 100% Organic | 🚚 Same-Day Delivery | 🐄 Farm-to-Table | 📞 24/7 Support

**Farm Story**
- Split: image left, text right
- Founding year, mission, CTA: "Learn About Our Farm"

**Testimonials**
- Carousel, 3 visible (desktop)
- Star ratings, quote, customer name, product bought

**Farm Gallery**
- Masonry grid 8 photos, lightbox on click

**Blog Preview**
- Latest 3 posts: cover, date, title, excerpt, Read More

**Newsletter Banner**
- Dark green bg, email form, privacy note

**Footer**
- Logo + tagline
- Quick Links | Shop | Contact columns
- Phone, email, address, WhatsApp button
- Social icons
- Payment logos (Paystack, Visa, Mastercard)
- Copyright | Privacy Policy | Terms

---

### PAGE 2: Shop (/shop)
- Sidebar filters: Category, Price Range, In Stock, Organic Only, Rating
- Product grid: 3-col desktop, 2-col tablet, 1-col mobile
- Sort: Newest | Price ↑↓ | Popular | Top Rated
- View: Grid / List toggle
- Breadcrumb + results count
- Load More / Pagination

### PAGE 3: Category Page (/shop/{category})
- Same as Shop but pre-filtered
- Category hero banner: large image, name, description

### PAGE 4: Product Detail (/shop/{category}/{slug})
- Image gallery (main + 5 thumbnails, zoom, mobile swipe)
- Breadcrumb, category badge, product name (Playfair 36px)
- Rating, price, compare price, unit selector, quantity [−][n][+]
- Add to Cart (full width green) + Buy Now (outline)
- Wishlist heart, availability badge, delivery estimate
- Share: WhatsApp, Facebook, Copy Link
- Tabs: Description | Nutrition/Details | Reviews | Delivery & Returns
- Related Products (4 cards, same category)

### PAGE 5: Cart (/cart)
- Items list: image, name, price, qty selector, remove
- Order summary: subtotal, delivery, coupon, total
- Proceed to Checkout CTA
- Empty state with Browse Products CTA

### PAGE 6: Checkout (/checkout)
- 3-step: Contact → Delivery → Payment
- Step 1: Name, Email, Phone
- Step 2: Address, City, State (Nigerian states dropdown), Notes, Delivery type
- Step 3: Paystack / Flutterwave / Bank Transfer / COD
- Order summary sidebar throughout

### PAGE 7: Order Success (/checkout/success)
- Animated green checkmark
- Order number, summary, delivery estimate
- Track Order + Continue Shopping buttons
- WhatsApp chat button

### PAGE 8: Order Tracking (/orders/track)
- Public, no login required
- Input: Order Number + Email/Phone
- Timeline display with all statuses

### PAGE 9: Customer Account (/account)
- Sidebar nav: Dashboard | Orders | Wishlist | Addresses | Profile | Logout
- Dashboard: welcome, stats, recent orders, saved addresses
- Orders: filterable table, view detail, track
- Order detail: items, timeline, invoice download, reorder, cancel
- Wishlist: saved products
- Addresses: add/edit/delete, set default
- Profile: name, email, phone, avatar, change password

### PAGE 10: About (/about)
- Hero with farm image
- Story, Vision, Mission, CEO message, Values
- Farm details (acreage, location, practices)
- Certifications, Team, Sustainability

### PAGE 11: Contact (/contact)
- Contact form (Name, Email, Phone, Subject, Message)
- Phone, Email, Address, WhatsApp button, Google Maps embed, Hours

### PAGE 12: Blog (/blog, /blog/{slug})
- Grid 3-col, category filter, featured hero
- Single: title, author, date, reading time, cover, content, tags, share, related

### PAGE 13: Farm Tour (/farm-tour)
- Photo/video gallery, YouTube embed, book-a-visit form

---

## 5. Admin Panel

### Admin Dashboard (/admin)
- KPI Cards: Revenue (month), Orders, New Customers, Active Products
- Charts: Revenue (12mo line), Order Status (pie), Top Products (bar), Category Sales
- Recent Orders table with quick status update
- Low Stock Alerts panel
- Latest Customers

### Admin Orders (/admin/orders, /admin/orders/{id})
- Filters: status, date range, search (order#/name/phone)
- Table: Order#, Customer, Phone, Items, Total, Payment, Delivery, Status, Date
- Bulk: update status, export CSV
- Detail: full breakdown, contact buttons, address, items, financials, payment ref
- Update status dropdown + notes + timeline
- Print/Invoice download, email/SMS customer

### Admin Products (/admin/products)
- Table: image, name, category, price, stock, status, featured, actions
- Inline stock edit, status toggle
- Create/Edit form:
  - Basic Info (name, slug, category, short desc, full desc rich text)
  - Pricing (price, compare price, wholesale price, unit, SKU)
  - Inventory (stock, threshold, track toggle)
  - Images (multi-upload, drag-drop, set primary, alt text)
  - Options (featured, organic, active)
  - Tags + SEO (meta title, meta desc)
  - Nutrition (JSON or structured form)

### Admin Categories (/admin/categories)
- List with product count, active status, sort order
- Add/Edit: name, slug, description, image, active, drag to reorder

### Admin Blog (/admin/blog)
- Table: title, status, author, date, views
- Create/Edit: title, slug, excerpt, rich text content, cover image, tags, published toggle

### Admin Promotions (/admin/promotions)
- Coupons table + create form
- Type: Percentage | Fixed | Free Delivery
- Code, value, min order, max uses, date range, active toggle

### Admin Inventory (/admin/inventory)
- All products sorted by stock
- Color: 🔴 Out | 🟡 Low | 🟢 In stock
- Inline edit, bulk update, export

### Admin Analytics (/admin/analytics)
- Date range selector
- Revenue chart (line, daily/weekly/monthly)
- Orders volume, avg order value
- Top 10 products (units + revenue)
- Sales by category (pie)
- New vs returning customers
- Delivery + payment method breakdown

### Admin Settings (/admin/settings)
- General: site name, tagline, contact info
- Delivery: fees, min order, zones, COD states
- Payment: Paystack keys, Flutterwave keys, bank transfer details
- Email: SMTP settings, from details
- Social: Instagram, Facebook, Twitter, YouTube
- Banners: text, active toggle, hero image
- Maintenance mode toggle

---

## 6. Email Notifications

| Trigger | Template | To |
|---------|---------|-----|
| Order placed | OrderConfirmation | Customer |
| Payment confirmed | PaymentReceived | Customer |
| Order confirmed by admin | OrderConfirmed | Customer |
| Order dispatched | OrderDispatched | Customer |
| Order delivered | OrderDelivered + review request | Customer |
| Order cancelled | OrderCancelled | Customer |
| New order | NewOrderAlert | Admin |
| Low stock | LowStockAlert | Admin |
| New registration | WelcomeEmail | Customer |
| Password reset | PasswordReset | Customer |

---

## 7. Product Categories + Sample Products

### 1. Poultry
- Live Broiler Chicken (~2.5kg per bird)
- Dressed Broiler Chicken (per kg)
- Point-of-Lay Pullets
- Day-Old Chicks
- Dressed Turkey (per kg)

### 2. Eggs
- Table Eggs Small Crate (30 eggs)
- Table Eggs Large Crate (60 eggs)
- Fertilized Eggs for Hatching (per dozen)
- Quail Eggs (per 30)

### 3. Livestock
- Live Goat (per head, weight ranges)
- Live Ram
- Dressed Pig (per kg)

### 4. Crop Produce
- Fresh Maize (50kg bag)
- Cassava (per bag)
- Yam (per tuber/bag)
- Tomatoes (per basket)
- Pepper (per kg)
- Ugwu, Waterleaf, Ewedu (vegetables, per bunch)

### 5. Dairy
- Fresh Farm Milk (per litre)
- Natural Yogurt (500ml)

### 6. Organic Inputs
- Organic Poultry Compost (per bag)
- Tomato Seedlings (per tray)
- Pepper Seedlings (per tray)
- Vegetable Seedlings (per tray)

### 7. Farm Subscription Boxes
- Weekly Egg Box (2 crates + extras)
- Protein Box (chicken + eggs, weekly)
- Full Farm Box (premium weekly assortment)

---

## 8. Nigerian States (for delivery dropdown)
Abia, Adamawa, Akwa Ibom, Anambra, Bauchi, Bayelsa, Benue, Borno, Cross River,
Delta, Ebonyi, Edo, Ekiti, Enugu, FCT (Abuja), Gombe, Imo, Jigawa, Kaduna,
Kano, Katsina, Kebbi, Kogi, Kwara, Lagos, Nasarawa, Niger, Ogun, Ondo, Osun,
Oyo, Plateau, Rivers, Sokoto, Taraba, Yobe, Zamfara

---

## 9. Key UX Decisions
1. Floating WhatsApp button on all public pages (bottom-right, always visible)
2. Guest checkout allowed — offer account creation post-order
3. Cart drawer (slides from right) — not a separate page
4. Real-time stock badges ("Only 3 left!")
5. Delivery fee calculated dynamically based on selected state + delivery type
6. Product unit always shown with price ("₦2,500 per crate of 30 eggs")
7. Order number format: TAK-YYYY-NNNNN
8. Mobile bottom nav: Home | Shop | Cart (badge) | Account
9. Admin mobile: collapsible sidebar, horizontal-scroll tables
10. Sticky "Add to Cart" bar on product page (appears when button scrolls out of view)

---

## 10. Security Checklist
- [ ] CSRF on all forms (`@csrf`)
- [ ] Role check in every admin controller
- [ ] Paystack webhook signature verification (HMAC-SHA512)
- [ ] Flutterwave secret hash verification
- [ ] Rate limiting on auth routes (5 attempts / 15 min)
- [ ] Input sanitization (Form Requests + Zod-equivalent validation)
- [ ] Image upload validation (type + size)
- [ ] Environment variables never exposed client-side
- [ ] SQL injection prevented by Eloquent (parameterized)
- [ ] XSS prevention: always escape `{{ }}`, use `{!! !!}` only for trusted admin content

---

## 11. SEO Configuration
- Every public page: `<title>`, `<meta description>`, Open Graph tags
- Dynamic pages (products, blog): use product/post data for meta
- `sitemap.xml`: dynamic (products, blog, categories)
- `robots.txt`: block /admin, /account, /api
- Structured data: Product schema, BreadcrumbList, Organization
- Canonical URLs on all pages
- Image alt text on all product images
- Sitemap submitted to Google Search Console post-launch

---

## 12. Deployment
- **Hosting**: VPS (Hetzner/DigitalOcean/Contabo) or shared Laravel hosting
- **Web server**: Nginx + PHP-FPM
- **SSL**: Let's Encrypt (Certbot)
- **DNS + CDN**: Cloudflare
- **Laravel Forge** or **Ploi** for server management
- **Database**: MySQL 8+ in production

### Pre-launch checklist
- [ ] `.env` production values set (APP_ENV=production, APP_DEBUG=false)
- [ ] `php artisan key:generate`
- [ ] `php artisan migrate --force`
- [ ] `php artisan db:seed --force`
- [ ] `php artisan storage:link`
- [ ] `npm run build`
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] Paystack live keys + webhook URL set
- [ ] Custom domain connected + SSL active
- [ ] Test order end-to-end on production
- [ ] Lighthouse score: target 90+ (Performance, SEO, Accessibility)
- [ ] Sitemap at /sitemap.xml
- [ ] Submit sitemap to Google Search Console
