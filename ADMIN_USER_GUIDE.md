# BookComerce Admin User Guide

**Quick Reference for Admin Dashboard Operations**

---

## 🔑 Logging In

1. Navigate to: `http://localhost:8000/admin/login` or `yourdomain.com/admin`
2. Enter email and password
3. Click "Login"

Default admin credentials (after seeder):
- Email: `admin@example.com`
- Password: `password`

⚠️ **Change password immediately after first login!**

---

## 📊 Dashboard Overview

**Home page showing:**
- Total Orders (last 30 days)
- Revenue (Rp format)
- Pending Orders
- Registered Customers
- Ebook Downloads
- Book inventory stats
- Top 5 selling books
- Recent orders table
- Quick action buttons

---

## 📚 Managing Books

### Adding a New Book
1. Click "Tambah Buku" button
2. Fill in required fields:
   - Judul (Title) *required
   - Kategori (Category) *required
   - Penulis (Authors) - select multiple with Ctrl+Click
   - Format (Fisik/Ebook/Keduanya)
   - Harga (Price)
3. Optional: Add discount price
4. Add stock quantity
5. Upload cover image (2MB max)
6. If Ebook format: Upload ebook file (PDF/EPUB, 50MB max)
7. Write description
8. Toggle "Aktif" to make visible
9. Click "Simpan Buku"

### Editing a Book
1. Go to "Buku" → find book in list
2. Click the edit icon (✏️) or click book title
3. Update fields as needed
4. Click "Simpan Perubahan"

### Viewing Book Details
1. Click on book title
2. See all details including:
   - Cover image
   - Pricing info
   - Stock level
   - Author list
   - Description
   - Timestamps

### Searching Books
1. In Books list, enter search term
2. Search across title, ISBN, author name
3. Results update automatically

### Filtering Books
- **By Category:** Select dropdown
- **By Format:** Physical / Ebook / Both
- **By Status:** Active / Inactive
- Multiple filters work together

### Exporting Books
1. Go to Books list
2. Click "Export CSV" button
3. File downloads with all book data
4. Use for reporting/backup

---

## 👤 Managing Authors

### Adding Author
1. Click "Tambah Penulis"
2. Enter name *required
3. Add email (optional)
4. Write bio/description
5. Upload photo
6. Add social media links:
   - Twitter, Instagram, Facebook, Website
7. Check "Aktif" if should be visible
8. Click "Simpan Penulis"

### Editing Author
1. Go to "Penulis" list
2. Click edit icon (✏️)
3. Update information
4. Click "Simpan Perubahan"

### Author Stats
- See number of books published
- See number of blog posts written
- Click on author row to view

---

## 📂 Managing Categories

### Adding Category
1. Click "Tambah Kategori"
2. Enter name *required
3. Add description
4. Check "Aktif"
5. Click "Simpan Kategori"

### Editing Category
1. Select category from list
2. Update name/description
3. Click "Simpan Perubahan"

### Category Features
- Book count shown automatically
- Cannot delete category if books assigned
- Categories used for book filtering

---

## 📦 Managing Orders

### Viewing Orders
1. Go to "Pesanan"
2. See order list with:
   - Order number
   - Customer email
   - Total amount
   - Status badge
   - Order date

### Viewing Order Details
1. Click order number
2. See:
   - Customer information (name, email, phone, address)
   - Items ordered (with quantities & prices)
   - Payment information
   - Order total

### Updating Order Status
1. Open order details
2. Select new status from dropdown:
   - Pending → Dibayar → Diproses → Selesai
   - Can also mark as Dibatalkan
3. Button "Update Status" will change status
4. System records timestamp

### Resending Ebook
1. Open order with ebook items
2. Click "Kirim Ulang Ebook" button
3. System sends download link to customer email
4. Download link valid for 7 days

### Filtering Orders
- **By Status:** Select dropdown
- **By Date Range:** Enter from/to dates
- **Search:** Find by order number or email

### Exporting Orders
1. Click "Export CSV"
2. CSV file includes all order details

---

## ✍️ Managing Blog

### Writing New Post
1. Click "Tulis Post Baru"
2. Enter title *required
3. Write content (supports markdown)
4. Upload featured image
5. Select author
6. Select category
7. Check "Publish Sekarang" if ready
   - Uncheck to save as draft
8. Add SEO meta description
9. Click "Simpan Post"

### Editing Post
1. Go to Blog list
2. Click edit icon (✏️)
3. Update content
4. Can toggle publish status
5. Click "Simpan Perubahan"

### Searching Blog Posts
1. Enter search term
2. Search in title and content

### Filtering Blog
- **By Author:** Select author dropdown
- **By Status:** Published / Draft
- Multiple filters work together

### Publishing Draft
1. Open draft post
2. Check "Published" checkbox
3. Auto-sets publication timestamp
4. Save changes

---

## 🎁 Managing Customers

### Viewing Customers
1. Go to "Pelanggan"
2. See list of guest customers with:
   - Email address
   - Name
   - Orders count
   - Total spent
   - Last purchase date

### Searching Customers
1. Enter email or name in search
2. Results update

### Sending Message
1. Find customer in list
2. Click "Pesan" button
3. Enter message (compose email)
4. Message sent to customer

### Exporting Customers
1. Click "Export CSV"
2. Download customer list with metrics

---

## 📥 Managing Ebook Deliveries

### Viewing Deliveries
1. Go to "Pengiriman E-book"
2. See stats dashboard:
   - Total deliveries
   - Active links
   - Expired links
   - Total downloads

### Checking Delivery Status
1. Find delivery in table
2. See:
   - Customer email
   - Book title
   - Download count
   - Expiry date
   - Status (Active/Expired)

### Resending Ebook
1. If link still active, click "Kirim Ulang"
2. System generates new 7-day link
3. Email sent to customer

### Regenerating Token
1. Click on delivery
2. Option to regenerate token
3. Creates new secure download link
4. Old link becomes invalid

### Searching/Filtering
- Search by email or book title
- Filter by Active/Expired status

---

## 💳 Managing Payments

### Viewing Payments
1. Go to "Pembayaran"
2. See stats:
   - Total transactions
   - Successful payments
   - Failed payments
   - Total revenue

### Payment Details
1. Click payment row
2. See:
   - Transaction ID
   - Order number
   - Amount
   - Payment method
   - Status

### Verifying Payment
1. Find pending payment
2. Click "Verifikasi" button
3. System checks with Midtrans
4. Status updates

### Processing Refund
1. On successful payment
2. Click "Refund" button
3. Specify amount (or full amount)
4. Enter reason
5. System processes with Midtrans

### Filtering Payments
- **By Status:** Success / Failed / Pending
- **Search:** By Transaction ID

---

## ⚙️ System Settings

### Configuration
1. Go to Settings
2. Update:
   - Support email
   - Support phone
   - Ebook expiry days (default 7)
   - Download rate limit (default 3/hour)
3. Click "Simpan Pengaturan"

### Maintenance
1. **Clear Cache:** Removes cached data
2. **Run Migration:** Updates database schema
3. **Download Backup:** Exports database (mysqldump)

### Activity Logs
1. Click "Lihat Semua Log"
2. See system activity history
3. Useful for auditing

---

## 🔍 Search & Filter Tips

### Universal Search
- Works across most pages
- Searches by multiple fields
- Case-insensitive
- Partial matches work

### Filtering
- Multiple filters combine with AND logic
- Filter and search work together
- Pagination works with filters

### Exporting
- Exports current filtered/searched results
- CSV format
- Can open in Excel/Google Sheets

---

## 💡 Common Tasks

### Task: Create and List a New Book
1. Products → Books
2. "Tambah Buku" button
3. Fill form (Title, Category, Price, etc.)
4. Upload images
5. "Simpan Buku"
6. Refreshes back to list - new book visible

### Task: Process An Order
1. Orders → Click order
2. See items ordered
3. Verify customer address
4. Update status to "Diproses"
5. If ebook included, resend link
6. Update to "Selesai" when complete

### Task: Monitor Payments
1. Payments → See list
2. Look for "Pending" status
3. Click "Verifikasi" on each
4. Status updates to Success/Failed
5. Monitor revenue stats

### Task: Publish a Blog Post
1. Blog → Click "Tulis Post Baru"
2. Write content with markdown
3. Add featured image
4. Check "Publish Sekarang"
5. Click "Simpan Post"
6. Post now live and searchable

---

## ⚠️ Important Rules

### Stock Management
- Stock decreases when order placed
- For "physical" and "both" formats
- Ebook format doesn't reduce stock
- Can manually update stock

### Pricing
- Discount price must be LESS than regular price
- Both prices required for valid book
- Can change anytime

### Author Assignment
- Multiple authors per book supported
- Authors display in order selected
- With Ctrl+Click select multiple

### Download Links
- Generated automatically with purchase
- Valid for 7 days by default
- Limited to 3 downloads/hour
- Email includes secure link

### Order Status Flow
```
Pending → Dibayar → Diproses → Selesai
                              ↓
                           Dibatalkan
```

---

## 🆘 Troubleshooting

### Can't upload file?
- Check file size (images 2MB, ebooks 50MB)
- Check file type (PNG/JPG for images, PDF/EPUB for ebooks)
- Check file permissions on server

### Search not working?
- Check if field corresponds to searchable columns
- Try refreshing page
- Check browser console for errors

### Export not working?
- May need to clear cache first
- Try right-click → "Save As"
- Check popup blocker

### Form won't submit?
- Fill all required fields (marked with *)
- Check validation error messages
- May need to scroll up to see errors

---

## 📞 Support

For technical issues:
- Check error message on screen
- Check recent logs in Settings
- Contact system administrator
- Check IMPLEMENTATION_CHECKLIST.md for status

---

## 🔐 Security Tips

✅ **DO:**
- Change default password
- Use strong password
- Logout after sessions
- Review logs regularly
- Keep sensitive data secure

❌ **DON'T:**
- Share admin login credentials
- Use public computers for admin access
- Click suspicious links
- Store passwords in plain text

---

**Last Updated:** {{ now()->format('Y-m-d') }}  
**Admin Version:** 1.0  
**Framework:** Laravel 11

For detailed technical docs, see project documentation files.
