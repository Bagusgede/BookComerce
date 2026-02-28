# 🎨 Admin System Visual Architecture

## System Component Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                    BOOKCOMERCE ADMIN SYSTEM                      │
│                          (90% Complete)                          │
└─────────────────────────────────────────────────────────────────┘

                           ┌──────────────┐
                           │ Admin Portal │
                           └──────────────┘
                                  │
                    ┌─────────────┼─────────────┐
                    │             │             │
            ┌───────▼────┐ ┌──────▼──────┐ ┌──▼──────────┐
            │  Dashboard │ │  Sidebar    │ │  Auth Check │
            │  Analytics │ │  Navigation │ │   Middleware│
            └────────────┘ └─────────────┘ └─────────────┘
                    │             │             │
         ┌──────────┴─────────────┼─────────────┴──────────┐
         │                        │                        │
    ┌────▼────────┐      ┌────────▼────────┐      ┌───────▼────┐
    │   RESOURCES │      │    MANAGEMENT   │      │ ANALYTICS  │
    └─────────────┘      └─────────────────┘      └────────────┘
         │                        │                        │
    ┌────┴──────────────────┬─────┴────────────────────┬──┴────────┐
    │                       │                          │           │
┌───▼────┐ ┌────────┐  ┌────▼─────┐ ┌────────┐  ┌────▼───┐ ┌───▼──┐
│ Books  │ │Authors │  │Categories│ │Orders  │  │Revenue │ │Stats │
│ CRUD   │ │CRUD    │  │CRUD      │ │Status  │  │Reports │ │KPIs  │
└────────┘ └────────┘  └──────────┘ │Tracking│  └────────┘ └──────┘
                                     └────────┘

    ┌──────────────┬──────────────┬──────────────┬──────────────┐
    │              │              │              │              │
┌───▼───┐   ┌─────▼──┐   ┌───────▼────┐   ┌────▼─────┐  ┌────▼────┐
│Blog   │   │Customers│  │Ebook       │   │Payments  │  │Settings │
│Posts  │   │Guest    │  │Deliveries  │   │Verify    │  │Config   │
│CMS    │   │List     │  │Tracking    │   │Refund    │  │Backup   │
└───────┘   └────────┘   └────────────┘   └──────────┘  └─────────┘
```

---

## Database Schema

```
┌──────────────────────────────────────────────────────────────┐
│                      DATABASE LAYER                          │
└──────────────────────────────────────────────────────────────┘

    ┌─────────────┐
    │   users     │
    ├─────────────┤
    │ id (PK)     │
    │ email       │
    │ password    │
    │ is_admin    │
    └─────────────┘
           │
           │ 1:N
           ▼
    ┌─────────────┐
    │   authors   │◄─────┐
    ├─────────────┤      │ N:M
    │ id (PK)     │      │
    │ name        │      │
    │ slug        │      │
    │ bio         │      │
    │ social_media├──────┤
    │ (JSON)      │      │
    └─────────────┘      │
           │            │
           │ 1:N        │
           │        ┌───┴─────────┐
           │        │   books     │
           │        ├─────────────┤
           ▼        │ id (PK)     │
    ┌─────────────┐ │ title       │
    │ blog_posts  │ │ format      │
    ├─────────────┤ │ price       │
    │ id (PK)     │ │ stock       │
    │ title       │ │ category_id │
    │ slug        │ └─────────────┘
    │ content     │        │
    │ is_published│        │ 1:N
    │ view_count  │        │
    └─────────────┘        ▼
                    ┌─────────────┐
                    │  categories │
                    ├─────────────┤
                    │ id (PK)     │
                    │ name (UK)   │
                    │ slug        │
                    │ is_active   │
                    └─────────────┘

    ┌──────────────┐      ┌────────────────┐      ┌──────────────┐
    │   orders     │◄─────│ order_items    │      │ ebook_       │
    ├──────────────┤ 1:N  ├────────────────┤ 1:1  │ deliveries   │
    │ id (PK)      │      │ id (PK)        │◄─────├──────────────┤
    │ order_number │      │ order_id (FK)  │      │ id (PK)      │
    │ guest_email  │      │ book_id (FK)   │      │ token        │
    │ guest_name   │      │ quantity       │      │ expired_at   │
    │ total_amount │      │ price          │      │ download_cnt │
    │ status       │      │ format         │      │ last_dl_at   │
    │ tx_id        │      └────────────────┘      └──────────────┘
    │ pm_method    │
    │ pm_details   │
    │ (JSON)       │
    └──────────────┘
           │
           │ 1:1
           ▼
    ┌──────────────┐
    │  payments    │
    ├──────────────┤
    │ id (PK)      │
    │ order_id (FK)│
    │ tx_id        │
    │ amount       │
    │ status       │
    │ pm_method    │
    └──────────────┘
```

---

## Controller Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    9 ADMIN CONTROLLERS                          │
└─────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────┐
│ DashboardController                                          │
├──────────────────────────────────────────────────────────────┤
│ • index() → Stats & analytics                               │
│ • chartData() → Revenue trends                              │
│ • stats() → Return JSON for API                             │
└──────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────┐
│ BookController (ResourceController)                          │
├──────────────────────────────────────────────────────────────┤
│ • index() → List with search/filter/export                  │
│ • create() → Show form                                      │
│ • store() → Save new book + file upload                     │
│ • show() → View details                                     │
│ • edit() → Show edit form                                   │
│ • update() → Update book + file handling                    │
│ • destroy() → Delete book                                   │
│ + bulkUpdate() → Bulk operations                            │
└──────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────┐
│ AuthorController (ResourceController)                        │
├──────────────────────────────────────────────────────────────┤
│ • index() → Authors list                                    │
│ • create() → Author form                                    │
│ • store() → Save author                                     │
│ • edit() → Edit form                                        │
│ • update() → Update author                                  │
│ • destroy() → Delete author                                 │
└──────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────┐
│ CategoryController (ResourceController)  │ OrderController  │
├──────────────────────────────────────────┼──────────────────┤
│ • index() → Categories list              │ • index() → Orders │
│ • create() → Add form                    │ • show() → Details │
│ • store() → Save category                │ • update() → Status│
│ • edit() → Edit form                     │ • export() → CSV  │
│ • update() → Update category             │ • resendEbook()   │
│ • destroy() → With constraints           │ • analytics()     │
└──────────────────────────────────────────┴──────────────────┘

┌──────────────────────────────────────────────────────────────┐
│ BlogPostController (ResourceController)                      │
├──────────────────────────────────────────────────────────────┤
│ • index() → Blog list with filters                          │
│ • create() → Post form                                      │
│ • store() → Save post + image                               │
│ • edit() → Edit form                                        │
│ • update() → Update post                                    │
│ • destroy() → Delete post                                   │
│ • publish()/unpublish() → Toggle status                     │
└──────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────┐
│ CustomerController                                           │
├──────────────────────────────────────────────────────────────┤
│ • index() → Guest customers aggregated                      │
│ • message() → Send email to customer                        │
│ • export() → CSV download                                   │
│ • analytics() → Customer metrics                            │
└──────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────┐
│ EbookDeliveryController                  │ PaymentController │
├──────────────────────────────────────────┼──────────────────┤
│ • index() → Deliveries list              │ • index() → Payments│
│ • resend() → Send link again             │ • verify() → Check │
│ • regenerateToken() → New link           │ • refund() → Process│
│ • logs() → Download tracking             │ • analytics() → Data│
│ • destroy() → Delete delivery            │ • export() → CSV  │
└──────────────────────────────────────────┴──────────────────┘

┌──────────────────────────────────────────────────────────────┐
│ SettingsController                                           │
├──────────────────────────────────────────────────────────────┤
│ • index() → Config form                                     │
│ • update() → Save settings                                  │
│ • clearCache() → Clear app cache                            │
│ • backup() → Download DB backup                             │
│ • migrate() → Run pending migrations                        │
│ • logs() → View activity logs                               │
└──────────────────────────────────────────────────────────────┘
```

---

## View File Structure

```
resources/views/admin/
│
├── layout.blade.php ............................ Base template
│   └── Contains: Sidebar, header, footer, styles
│
├── dashboard.blade.php ......................... Dashboard view
│   └── Contains: Stat cards, charts, recent orders
│
├── books/
│   ├── index.blade.php ........................ List view
│   ├── create.blade.php ....................... Create form
│   ├── edit.blade.php ......................... Edit form
│   └── show.blade.php ......................... Detail view
│
├── authors/
│   ├── index.blade.php ........................ List view
│   ├── create.blade.php ....................... Create form
│   └── edit.blade.php ......................... Edit form
│
├── categories/
│   ├── index.blade.php ........................ List view
│   ├── create.blade.php ....................... Create form
│   └── edit.blade.php ......................... Edit form
│
├── orders/
│   ├── index.blade.php ........................ List view
│   └── show.blade.php ......................... Detail view
│
├── blog-posts/
│   ├── index.blade.php ........................ List view
│   ├── create.blade.php ....................... Create form
│   └── edit.blade.php ......................... Edit form
│
├── customers/
│   └── index.blade.php ........................ List view
│
├── ebook-deliveries/
│   └── index.blade.php ........................ Tracking view
│
├── payments/
│   └── index.blade.php ........................ List view
│
└── settings/
    └── index.blade.php ........................ Config panel
```

---

## Request/Response Flow

```
User Request
    │
    ├─→ Route Matching (routes/admin.php)
    │
    ├─→ Middleware Stack
    │   ├─→ Auth::check() - User logged in?
    │   ├─→ Admin Middleware - Is admin?
    │   └─→ CSRF Token - Valid?
    │
    ├─→ Controller Method
    │   ├─→ Input Validation
    │   ├─→ Database Query/Update
    │   ├─→ File Handling (if any)
    │   └─→ Relationship Management
    │
    ├─→ Response
    │   ├─→ Return View (Blade template)
    │   ├─→ Pass data via compact()
    │   └─→ Handle errors
    │
    └─→ Blade Template Rendering
        ├─→ Extends admin/layout.blade.php
        ├─→ Displays data with Blade syntax
        ├─→ Form submission ready
        └─→ HTML to Browser
```

---

## Data Flow Example: Creating a Book

```
1. USER CLICKS "TAMBAH BUKU"
   │
   ├─→ GET /admin/books/create
   │
   └─→ BookController@create
       └─→ Load categories & authors from DB
           └─→ Return admin/books/create.blade.php

2. USER FILLS FORM & CLICKS "SIMPAN"
   │
   ├─→ POST /admin/books
   │
   └─→ BookController@store
       ├─→ Validate input (FormRequest)
       ├─→ Create Book model
       ├─→ Upload cover image → Storage
       ├─→ Upload ebook file → Storage
       ├─→ Sync authors → Pivot table
       ├─→ Save to database
       └─→ Redirect to list with success message

3. BOOK NOW VISIBLE IN LIST
   │
   ├─→ GET /admin/books
   │
   └─→ BookController@index
       ├─→ Query books with pagination
       ├─→ Apply search/filter
       ├─→ Load relationships (authors, category)
       └─→ Return admin/books/index.blade.php
           └─→ Render table with book data
```

---

## Feature Implementation Map

```
What Can You Do?                    Where to Do It           Status
───────────────────────────────────────────────────────────────────

📚 BOOKS
├─ Create book                    → /admin/books/create     ✅
├─ Edit book                      → /admin/books/[id]/edit  ✅
├─ View details                   → /admin/books/[id]       ✅
├─ List all books                 → /admin/books            ✅
├─ Search books                   → Filter in /admin/books  ✅
├─ Filter by category/format      → Filter in /admin/books  ✅
├─ Upload cover image             → Create/Edit form        ✅
├─ Upload ebook file              → Create/Edit form        ✅
├─ Manage pricing                 → Create/Edit form        ✅
├─ Track stock                    → Create/Edit form        ✅
├─ Bulk operations                → List actions            ✅
└─ Export to CSV                  → Export button in list   ✅

👤 AUTHORS
├─ Create author                  → /admin/authors/create   ✅
├─ Edit author                    → /admin/authors/[id]/edit ✅
├─ List authors                   → /admin/authors          ✅
├─ Upload photo                   → Create/Edit form        ✅
├─ Add social media               → Create/Edit form        ✅
├─ See books written              → Author list stats       ✅
└─ See posts written              → Author list stats       ✅

📂 CATEGORIES
├─ Create category                → /admin/categories/create ✅
├─ Edit category                  → /admin/categories/[id]/edit ✅
├─ View all categories            → /admin/categories       ✅
├─ See book count                 → Category list           ✅
└─ Delete category                → Category list           ✅

📦 ORDERS
├─ View all orders                → /admin/orders           ✅
├─ View order details             → /admin/orders/[id]      ✅
├─ Update order status            → Order detail page       ✅
├─ Resend ebook link              → Order detail page       ✅
├─ Filter by status/date          → Order list filters      ✅
├─ Export orders                  → Export button           ✅
└─ Track payment status           → Order detail page       ✅

✍️ BLOG POSTS
├─ Write new post                 → /admin/blog-posts/create ✅
├─ Edit post                      → /admin/blog-posts/[id]/edit ✅
├─ Publish/draft toggle           → Create/Edit form        ✅
├─ Upload featured image          → Create/Edit form        ✅
├─ List posts                     → /admin/blog-posts       ✅
├─ Filter by author/status        → Blog list filters       ✅
└─ View count tracking            → Auto tracked            ✅

🎁 CUSTOMERS
├─ View all customers             → /admin/customers       ✅
├─ See spending metrics           → Customer list          ✅
├─ Send message                   → Customer list button   ✅
└─ Export customer data           → Export button          ✅

📥 EBOOK DELIVERIES
├─ Track deliveries               → /admin/ebook-deliveries ✅
├─ View download count            → Delivery list          ✅
├─ Resend delivery                → Delivery list action   ✅
├─ Regenerate token               → Delivery detail        ✅
└─ View download logs             → Delivery detail        ✅

💳 PAYMENTS
├─ View all transactions          → /admin/payments        ✅
├─ Verify payment                 → Payment list action    ✅
├─ See revenue stats              → Payment dashboard      ✅
├─ Filter by status               → Payment list filter    ✅
├─ Process refund                 → Payment detail         ✅
└─ Export payment data            → Export button          ✅

⚙️ SETTINGS
├─ Configure system               → /admin/settings        ✅
├─ Clear cache                    → Settings panel         ✅
├─ Download backup                → Settings panel         ✅
├─ Run migrations                 → Settings panel         ✅
└─ View activity logs             → Settings panel         ✅

📊 DASHBOARD
├─ View analytics                 → /admin                 ✅
├─ See KPI cards                  → Dashboard              ✅
├─ Top selling books              → Dashboard widget       ✅
├─ Recent orders                  → Dashboard widget       ✅
└─ Quick actions                  → Dashboard buttons      ✅
```

---

## Security Layer

```
Request
   │
   ├─→ auth:web middleware
   │   └─→ User logged in? → Redirect to login
   │
   ├─→ admin middleware  
   │   └─→ User is admin? → Deny access
   │
   ├─→ CSRF Token Check
   │   └─→ Valid token? → Reject POST
   │
   ├─→ Input Validation
   │   └─→ Valid data? → Reject with errors
   │
   ├─→ File Upload Validation
   │   └─→ Check type, size, mime
   │
   ├─→ Authorization Policy
   │   └─→ User owns resource? → Deny
   │
   └─→ Sanitization
       └─→ Escape output, prevent XSS
```

---

## Status Legend

| Symbol | Meaning |
|--------|---------|
| ✅ | Complete & Tested |
| ⏳ | In Progress / Ready to Start |
| 📋 | Optional / Nice to Have |
| ⚠️ | Requires Attention |
| 🔴 | Blocked / Not Started |

---

**Admin System Architecture Complete** ✅

