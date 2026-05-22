# T-Akomz Agro Estates Ltd — Complete Website Guide
### Written in plain English for the business owner

---

## What Is This Website?

This is your complete online business system. It is **two things in one**:

1. **A public website** — where customers visit, browse your farm products, and place orders
2. **An admin panel** — a private back-office where you and your team manage everything

Think of it like a shop. Customers walk through the front door and see the shelves. You and your staff go through the back door and manage stock, orders, finances, and workers.

---

## The Website Address

- Public website: `https://takomzagro.com` (or `takomzagro.ng`)
- Admin panel: `https://takomzagro.com/admin`

---

## PART 1 — THE PUBLIC WEBSITE (What Customers See)

---

### 1. The Homepage

The first page a visitor sees when they open your website. It has:

- **Your logo and brand** at the top
- **A big hero section** — a full-screen welcome with your farm's message and a button to start shopping
- **Stats section** — shows numbers like years in business, products available, happy customers
- **How It Works** — explains to a new visitor how to order from you in simple steps
- **Featured products** — a preview of your best-selling or newest farm products
- **A newsletter signup box** — visitors can drop their email to receive updates from you

---

### 2. The Shop Page

This is your online market. Customers can:

- See all your farm products listed with photos, names, and prices in Nigerian Naira (₦)
- **Filter by category** — e.g. show only Poultry, or only Eggs, or only Crop Produce
- **Search** for a specific product by name
- Click any product to see the full details

**Your product categories are:**
- Poultry (chicken, turkey, duck)
- Eggs (table eggs, fertilized eggs, quail eggs)
- Livestock (goat, sheep, pig)
- Crop Produce (maize, cassava, yam, vegetables)
- Dairy (milk, yogurt)
- Organic Inputs (compost, seedlings)
- Farm Subscription Boxes (weekly delivery)

---

### 3. The Product Detail Page

When a customer clicks on a product, they see:

- Large product photos (multiple photos supported)
- Full description, weight, price
- Nutrition facts (if entered)
- A button to **Add to Cart**
- Product reviews from previous buyers
- Related products

---

### 4. The Cart

When a customer adds products to their cart:

- A **sliding drawer** appears from the right showing what's in the cart
- They can change quantities or remove items
- The total updates automatically
- There is a **cart icon in the top navigation bar** showing how many items are in the cart

**Important:** Even if a visitor is not logged in (a guest), their cart is saved temporarily. When they log in, their items are kept — nothing is lost.

---

### 5. The Checkout

When a customer is ready to pay, they go through a simple checkout:

1. **Enter delivery address** — where they want the product delivered
2. **Choose delivery method** — standard, express, or pickup
3. **Choose payment method** — Paystack, Flutterwave, or Cash on Delivery
4. **Pay** — they are taken to a secure payment page

After payment, they get an **order confirmation email** with a PDF receipt attached. They also receive an **SMS confirmation** on their phone number.

---

### 6. How Payments Work

Your website accepts **three ways to pay:**

**A. Paystack (Main method)**
The customer clicks Pay → they go to Paystack's secure website → they enter their card or bank details → Paystack confirms to your website automatically → the order is marked as paid. You do not touch the money process — Paystack handles it safely and sends the money to your account.

**B. Flutterwave (Backup method)**
Same process as Paystack but using a different payment company. Having two options means if one has a problem, customers can still pay with the other.

**C. Cash on Delivery (COD)**
The customer places the order but pays cash when your delivery person arrives. The order stays as "Pending" until your admin staff manually confirms it was paid.

---

### 7. Order Tracking

Customers can track their order without logging in. They go to the tracking page, type their order number (format: **TAK-2024-00001**) and their email or phone number, and they can see exactly where their order is:

- Pending → Confirmed → Processing → Dispatched → Delivered

---

### 8. The Blog

Your website has a full blog/news section where you can publish:

- Farm updates
- Farming tips and advice
- News about your products
- Any information you want to share with customers

Each blog post has a title, cover photo, category, and full article body. Posts are written through the admin panel.

---

### 9. The About Page

Tells your business story — who you are, your farm history, your values, and your team. This builds trust with visitors.

---

### 10. The Contact Page

Has a contact form where visitors can send you a message directly from the website. The message lands in your admin panel as a "Contact Message" and you also get notified by email.

Also shows your:
- Phone numbers
- Email address
- Farm address: Oke-Ido Road, Ido Ekiti, Ekiti State, Nigeria
- Google Maps embed so people can find you

---

### 11. The Farm Tour Page

Visitors can book a guided tour of your farm through this page. They fill in their name, contact details, preferred date, and number of guests. You get notified and can approve or reject the booking from the admin panel. The visitor gets an email confirming or declining their booking.

---

### 12. The AI Chat Widget

There is a small **chat button** at the bottom-right corner of every page on the public website. When a visitor clicks it, an AI assistant pops up and can:

- Answer questions about your products
- Suggest what to buy based on what the visitor is looking for
- Give information about your farm, delivery, and payment
- Handle basic customer service 24/7 automatically — even when you are asleep

This AI uses two systems (GROQ and Google Gemini) — if one is busy, the other takes over automatically.

---

### 13. Customer Accounts

Visitors can **register an account** on your website. Once registered, a customer can:

- See all their past orders and their current status
- Download PDF invoices for any order
- Save multiple delivery addresses (home, office, etc.)
- Maintain a **wishlist** — save products they like but haven't bought yet
- Update their profile and change their password

---

### 14. The Website Can Be Installed Like an App (PWA)

Your website is built as a **Progressive Web App**. This means when a customer visits your website on their phone, they will see a prompt saying "Add to Home Screen." If they do this, your website gets an icon on their phone's home screen and opens like a real app — even without internet for pages they have visited before.

---

### 15. Light Mode / Dark Mode

The website has a toggle button in the top navigation bar. Visitors can switch between:
- **Dark mode** (default) — black background, green accents — premium and modern
- **Light mode** — white background — for users who prefer bright screens

Their choice is remembered the next time they visit.

---

## PART 2 — THE ADMIN PANEL (What You and Your Team See)

The admin panel is only accessible to your team. Customers cannot see it. You log in at `/admin`.

---

### Who Can Log In and What They Can Do

Your website has different **roles** — like different job titles with different access levels:

| Role | Who | What They Can Do |
|------|-----|-----------------|
| **SUPER_ADMIN** | You (the owner) | Everything — full control of the entire system |
| **ADMIN** | Senior staff | Everything except system settings and user management |
| **MANAGER** | Departmental managers | Can submit expense requests, manage their area, view finance dashboard |
| **SALES** | Sales staff | Can view and manage orders and customers |
| **CUSTOMER** | Registered shoppers | Can only use the public website — cannot enter the admin panel |

---

### The Admin Dashboard

The first thing you see after logging in. It shows you a live snapshot of your business:

- **Total revenue** earned so far
- **Number of orders** (total, pending, processing, delivered)
- **Total products** in your shop
- **Total registered customers**
- **Revenue chart** — a graph showing your earnings month by month for the last 12 months
- **Top 5 best-selling products** — which products make you the most money
- **Recent orders** — the last 10 orders placed, with status and amounts
- **Low stock warnings** — products that are running out

---

### Products Management

You can **add, edit, and delete** your farm products from here.

When adding a product, you fill in:
- Name, category, description
- Price (in ₦)
- Stock quantity and a low-stock warning level
- Weight / unit
- Multiple photos
- Nutrition facts (optional)
- Tags for searching
- Whether it's active (visible to customers) or hidden
- Whether it's featured (shown prominently on the homepage)

**AI Content Generation:** When adding or editing a product, there is an "AI Generate" button. You click it, the AI writes a professional product description for you automatically. You can accept or edit it.

---

### Categories Management

You manage the product categories (Poultry, Eggs, Livestock, etc.) from here. You can add new categories, rename them, add a description and image, and change their order.

---

### Orders Management

Every order placed on your website appears here. You can:

- See all orders with their status, customer name, total, and date
- Click an order to see full details — what was ordered, quantity, price, delivery address
- **Update the order status** — move it from Pending → Confirmed → Processing → Dispatched → Delivered
- Each time you change the status, the **customer automatically receives an email** telling them their order has been updated
- Download the PDF invoice for any order
- See the full timeline of every status change for an order

---

### Customers Management

Shows you a list of all registered customers with:
- Their name, email, phone
- How many orders they have placed
- Total amount they have spent with you

You can click any customer to see their full profile, order history, and saved addresses.

---

### Blog Management

Write and publish blog posts from here. You fill in:
- Title and article body (with rich text formatting)
- A cover image
- Category and tags
- Whether to publish immediately or save as a draft

**AI Blog Writing:** There is an AI button that can write a complete blog post for you on any farming topic. You just give it a topic and it generates a full professional article.

---

### Inventory Management

A dedicated page for monitoring your stock levels. Shows you which products are low on stock and lets you update quantities in bulk without having to edit each product one by one.

---

### Coupons / Promotions

You can create **discount codes** for your customers. For example:
- `WELCOME10` — gives 10% off the first order
- `HARVEST50` — gives ₦500 off any order above ₦5,000

You set the code, discount type (percentage or fixed amount), minimum order requirement, expiry date, and how many times it can be used.

---

### Analytics

A section showing you detailed statistics about your business — sales trends, popular products, customer behaviour, revenue by category, etc.

---

### Delivery Management

Manage your delivery zones, fees, and delivery staff from here. Set different delivery fees for different delivery speeds (standard, express, pickup).

---

### Farm Tour Bookings

Shows all the farm tour requests that visitors submitted through the website. You can:
- See the visitor's name, contact, date requested, and group size
- Approve the booking (visitor gets a confirmation email)
- Reject the booking (visitor gets a polite rejection email with a reason)

---

### Contact Messages

Every message sent through your website's Contact page lands here. You can read messages and mark them as read. You also receive an email alert when a new message comes in.

---

### Reviews

When customers leave product reviews on the website, they don't appear publicly until **you approve them** from here. This protects you from fake or abusive reviews.

---

### Settings

This is where you configure your entire website. From here you can change:

- **Contact information** — phone numbers, email, WhatsApp number, address
- **Social media links** — Instagram, Facebook, Twitter, YouTube
- **Announcement banner** — a message strip at the top of your website (e.g. "Free delivery on orders above ₦5,000!")
- **Delivery fees** — standard, express, and pickup prices
- **Minimum order amount** — the smallest order you will accept
- **Bank details** — for customers paying by bank transfer
- **Payment methods** — enable or disable Paystack, Flutterwave, Cash on Delivery
- **Product watermark** — automatically add your logo or text to all product photos
- **Maintenance mode** — temporarily take the website offline (e.g. during updates)
- **Payment gateway keys** — your Paystack and Flutterwave public keys
- **Google Maps** — paste your Google Maps embed code so your location shows on the Contact page
- **Gallery** — customise the gallery section title and Instagram call-to-action

---

### Staff Management (Users)

Only the SUPER_ADMIN (you) can access this. You can:
- Add new staff members to the admin panel
- Assign them a role (ADMIN, MANAGER, SALES)
- When you add a staff member, they automatically receive a **welcome email** with their login credentials
- Change or deactivate staff accounts

---

## PART 3 — THE FINANCIAL CONTROL SYSTEM

This is a complete internal money management system for your farm — separate from the customer shop. It tracks every naira that comes in and goes out.

---

### The Farm Wallet

Think of this as your farm's piggy bank inside the system. You fund it with money (like adding money to a float). All farm expenses are deducted from this wallet automatically.

The wallet shows you:
- Current balance
- Total money ever put in
- Total money ever spent
- How many expense requests are waiting for approval

---

### Expense Requests (For Managers)

When a manager needs money for something (e.g. buying feed, fuel, or equipment), they do NOT just take it. They **submit an expense request** through the system:

1. Manager fills in: what it's for, category (feed/fuel/labor/etc.), amount, date, vendor name, and a description
2. **The system automatically emails all admins** saying "New expense request submitted"
3. An admin reviews it and either **Approves** or **Rejects** it
4. **The manager automatically receives an email** saying whether it was approved or declined, with the reason if declined
5. If approved, the manager goes and makes the purchase, then comes back and **confirms the spending** — optionally uploading a photo of the receipt
6. The money is deducted from the wallet

This creates a clear audit trail — you always know who requested what, who approved it, and what it was spent on.

---

### Direct Expense Logging (For Admins)

Sometimes an admin or senior staff member spends money directly without needing approval. They can log it straight into the system. The money is immediately deducted from the wallet. **All other admins receive an email notification** so everyone is aware.

---

### Finance Dashboard

Shows you:
- Your current wallet balance
- All recent transactions (funding + expenses)
- **Spending breakdown by category** — how much was spent on feed, fuel, labor, veterinary, equipment, etc.
- **Manager budget stats** — how much each manager has spent

---

## PART 4 — WORKER MANAGEMENT

A complete system for managing your farm workers (separate from your admin staff).

---

### Worker Records

For every worker on your farm, you store:
- Full name, phone number, email address
- Role/job title (e.g. Poultry Handler, Farm Supervisor)
- Employment type (daily, weekly, monthly, contract)
- Salary type and amount (fixed monthly, daily rate, or hourly rate)
- Start date and end date
- Bank name and account number (for salary payment)
- ID number, emergency contact
- A photo
- Any notes

---

### Attendance Tracking

You can record daily attendance for each worker — Present, Absent, or Half Day. This is used to automatically calculate what they are owed.

---

### Payroll Generation

At the end of a pay period, you generate a payroll for a worker:
- The system automatically calculates their salary based on their attendance records
- You can add bonuses or deductions
- If the worker has no attendance records at all for that period (i.e. attendance wasn't tracked), the system pays their full salary — it does not assume they were absent
- You can also manually override the calculated amount if needed

---

### Payroll Approval & Payment

1. Generate the payroll (status: **Draft**)
2. An admin reviews and **Approves** it (status: **Approved**)
3. Admin processes the **Payment** — choosing Cash or Bank Transfer and entering a reference number
4. The salary amount is automatically deducted from the farm wallet
5. A salary payment record is created
6. **The worker automatically receives an email** showing exactly how much was paid, for which period, their base salary, any bonuses or deductions, and the payment reference

---

### Worker Welcome Email

When you add a new worker to the system and their email address is provided, the system **automatically sends them a welcome email** explaining:
- Their job title and employment type
- Their salary type
- Their start date
- Their bank details on file
- A friendly welcome message from T-Akomz Agro Estates

---

## PART 5 — THE EMAIL NOTIFICATION SYSTEM

Your website sends emails automatically for many situations — you do not have to do anything manually. Here is every email the system sends:

---

### Emails Sent to Customers

| When | Email Sent |
|------|-----------|
| Customer registers on the website | Welcome email — "Welcome to T-Akomz Agro Estates" |
| Order is placed and payment confirmed | Order confirmation email with PDF invoice attached |
| You mark an order as Dispatched | "Your order is on the way" email |
| You mark an order as Delivered | "Your order has been delivered" email |
| A payment fails | "Payment failed" email with instructions |
| You add a new product to the shop | Alert email to all subscribers and registered customers |

---

### Emails Sent to Admins

| When | Email Sent |
|------|-----------|
| A new customer registers | "New customer registered" alert |
| A manager submits an expense request | "New expense request" alert to all admins |
| A manager logs a direct expense | "Direct expense logged" alert to all admins |
| A new contact form message arrives | "New contact message" alert |
| A new farm tour booking is made | "New farm tour booking" alert |
| A product is running low on stock | "Low stock alert" for that product |

---

### Emails Sent to Managers / Staff

| When | Email Sent |
|------|-----------|
| Their expense request is approved | "Your expense request has been approved" |
| Their expense request is declined | "Your expense request has been declined" with reason |
| A new staff account is created for them | "Your staff account is ready" with login credentials |

---

### Emails Sent to Workers

| When | Email Sent |
|------|-----------|
| They are added to the worker system | "Welcome to T-Akomz Agro Estates" onboarding email |
| Their salary is paid | "Your salary has been paid" with full breakdown |

---

### Broadcast Emails (You Send Manually)

You can also compose and send emails yourself from the admin panel:

**Mail Workers / Staff** (go to Communications → Mail Workers & Staff)
- Send to ALL active workers
- Send to ALL staff members
- Send to ONE specific worker
- Send to ONE specific staff member

**Mail Customers** (go to Communications → Mail Customers)
- Send to ALL customers at once (broadcast)
- Hand-pick specific customers from a dropdown list and send to only them

---

## PART 6 — THE AI TOOLS IN THE ADMIN PANEL

Your admin panel has a built-in AI assistant that can do many things for you:

| Tool | What It Does |
|------|-------------|
| **Product Content Generator** | Give it a product name and it writes a full professional product description |
| **Blog Post Writer** | Give it a topic and it writes a complete blog article ready to publish |
| **Business Plan Generator** | Generates a professional business plan document for T-Akomz |
| **Sales Insights Analyser** | Analyses your sales data and gives you business recommendations |
| **AI Chat** | Have a conversation with the AI — ask it any question about your business |

---

## PART 7 — SECURITY

Your website has many layers of security built in:

- **Passwords are encrypted** — no one, not even the developers, can read a stored password
- **Payment secrets are never stored** in the database — they only live in secure server files
- **Financial receipts are private** — stored in a protected folder, not accessible to the public
- **Every form has CSRF protection** — prevents fake form submissions from other websites
- **Rate limiting** — if someone tries to log in with wrong passwords many times, the system blocks them
- **Security headers** — tells web browsers to apply extra protections against common attacks
- **XSS protection** — AI-generated content is sanitised before display, preventing script injection
- **IDOR protection** — customers can only see their own orders, not anyone else's
- **Admin panel is completely separate** from the public website, protected behind authentication and role checks

---

## PART 8 — DEVELOPER COMMANDS EXPLAINED

These are commands that a developer types in the terminal to manage the website. Here is what each one does in plain English:

```
php artisan serve
```
**Starts the website on your computer for testing.** Like turning on a local copy of the site on your laptop.

---

```
npm run dev
```
**Starts the design tools.** Watches your CSS and JavaScript files and rebuilds them instantly whenever you make a design change.

---

```
php artisan migrate
```
**Sets up or updates the database tables.** The database is where all your data lives (products, orders, customers, etc.). This command creates or updates the structure of those tables.

---

```
php artisan migrate:fresh --seed
```
**Wipes the database and starts fresh with sample data.** Used during development to reset everything. DO NOT run this on a live website — it will delete all your real data.

---

```
php artisan db:seed
```
**Fills the database with sample/starter data** — like adding demo products, categories, and an admin account. Used when setting up the site for the first time.

---

```
php artisan storage:link
```
**Links your uploaded files (photos, documents) so they appear on the website.** Run this once when setting up the website on a new server. Without it, product images and uploaded files will not display.

---

```
php artisan blog:auto-publish
```
**Tells the AI to automatically write and publish a new blog post.** You can also specify a topic:
`php artisan blog:auto-publish --topic="How to raise healthy poultry" --category="Farming Tips"`

---

```
php artisan pwa:icons
```
**Generates all the app icon sizes** needed for your website to be installable on phones and tablets.

---

```
php artisan sitemap:generate
```
**Creates a sitemap file** — a list of all your website's pages submitted to Google so they know what to index and show in search results.

---

```
php artisan config:clear
php artisan view:clear
```
**Clears cached files.** When you make changes to settings or design files, sometimes the website uses old saved copies. These commands delete those old copies so the new changes take effect.

---

## PART 9 — WHERE YOUR DATA LIVES

| Data Type | Where It Is Stored |
|-----------|-------------------|
| Products, orders, customers, settings | The database (SQLite on your computer, MySQL on the live server) |
| Product photos and uploaded images | `storage/app/public/` folder on the server |
| Financial receipts | `storage/app/receipts/` — private, not publicly accessible |
| Email templates | `resources/views/emails/` — these are the designs of your automated emails |
| Website pages | `resources/views/` — these are the HTML templates for every page |
| Business logic | `app/` — PHP code that powers everything |

---

## PART 10 — QUICK REFERENCE: WHERE TO FIND THINGS IN THE ADMIN PANEL

| Task | Where to Go |
|------|------------|
| Add or edit a product | Products → Add Product / Edit |
| See new orders | Orders |
| Change an order status | Orders → click the order → Update Status |
| Add a blog post | Blog → New Post |
| View farm tour bookings | Farm Tours |
| Read contact messages | Contact Messages |
| Add a worker | Workers → Add Worker |
| Generate payroll | Payroll → Generate |
| Pay a worker's salary | Payroll → view the approved payroll → Pay |
| Submit an expense request | Finance → Submit Expense Request |
| Approve/decline an expense | Finance → Pending Expenses |
| Fund the farm wallet | Finance → Fund Wallet |
| Send email to customers | Communications → Mail Customers |
| Send email to workers/staff | Communications → Mail Workers & Staff |
| Change website phone/address | Settings |
| Turn maintenance mode on/off | Settings → Maintenance Mode |
| Add a new staff member | Users → Add Staff |
| Use AI tools | AI Assistant |
| View financial summary | Finance Dashboard |

---

*This document was written for the business owner and client of T-Akomz Agro Estates Ltd.*
*For technical questions, contact your developer.*
