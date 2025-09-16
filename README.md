# Finance & Accounting System

Sistem Finance & Accounting sederhana berbasis Laravel (backend REST API) dan frontend HTML statis dengan Bootstrap 3, jQuery, dan DataTables.

## Features

- **Chart of Accounts Management** - Kelola akun-akun dalam chart of accounts
- **Journal Entries** - Buat dan kelola jurnal dengan validasi balance (debit = credit)
- **Invoice Management** - Kelola invoice pelanggan
- **Payment Tracking** - Catat pembayaran dengan update status invoice otomatis
- **Trial Balance** - Laporan neraca saldo dengan export ke Excel
- **AJAX Interface** - Semua operasi CRUD tanpa reload halaman

## Technical Stack

- **Backend**: Laravel 12
- **Frontend**: HTML, Bootstrap 3, jQuery, DataTables
- **Database**: MySQL
- **Export**: Laravel Excel (maatwebsite/excel)

## Installation

### Prerequisites

- PHP 8.1 atau lebih tinggi
- Composer
- Node.js & npm (optional)
- MySQL/MariaDB
- Web server (Apache/Nginx) atau Laravel's built-in server

### Steps

1. **Clone Repository**
   ```bash
   git clone https://github.com/nexuzz14/finance-accounting-mini-project.git
   cd finance-accounting-mini-project
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   
   Edit `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=finance_accounting
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Create Database**
   ```sql
   CREATE DATABASE finance_accounting;
   ```

6. **Run Migrations & Seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Install Laravel Excel (for Trial Balance export)**
   ```bash
   composer require maatwebsite/excel:^3.1 --with-all-dependencies
   ```

8. **Start Server**
   ```bash
   php artisan serve
   ```

9. **Access Application**
   
   Open your browser and navigate to:
   - API: `http://localhost:8000/api`
   - Frontend: Place the HTML file in `resources/views` or serve it as static file

## Project Structure

```
├── app/
│   ├── Models/
│   │   ├── ChartOfAccount.php
│   │   ├── Journal.php
│   │   ├── JournalLine.php
│   │   ├── Invoice.php
│   │   ├── Payment.php
│   │   └── ClosingPeriod.php
│   ├── Http/Controllers/Api/
│   │   ├── ChartOfAccountController.php
│   │   ├── JournalController.php
│   │   ├── InvoiceController.php
│   │   ├── PaymentController.php
│   │   └── TrialBalanceController.php
│   ├── Exports/
│   |    └── TrialBalanceExport.php
|   └── Services/
|       └── TrialBalanceService.php
├── database/
│   ├── migrations/
│   │   ├── create_chart_of_accounts_table.php
│   │   ├── create_journals_table.php
│   │   ├── create_journal_lines_table.php
│   │   ├── create_invoices_table.php
│   │   ├── create_payments_table.php
│   │   └── create_closing_periods_table.php
│   └── seeders/
│       ├── ChartOfAccountSeeder.php
│       ├── JournalSeeder.php
│       ├── InvoiceSeeder.php
│       └── PaymentSeeder.php
├── routes/
│   ├──  api.php
|   └──  web.php
└── resources/views/
    └── index.blade.php (frontend file)
```

## API Endpoints

### Chart of Accounts
- `GET /api/chart-of-accounts` - List accounts
- `GET /api/chart-of-accounts/{id}` - Get account detail
- `POST /api/chart-of-accounts` - Create account
- `PUT /api/chart-of-accounts/{id}` - Update account
- `DELETE /api/chart-of-accounts/{id}` - Delete account

### Journals
- `GET /api/journals` - List journals
- `GET /api/journals/{id}` - Get journal with lines
- `POST /api/journals` - Create journal
- `PUT /api/journals/{id}` - Update journal
- `DELETE /api/journals/{id}` - Delete journal

### Invoices
- `GET /api/invoices` - List invoices
- `GET /api/invoices/{id}` - Get invoice detail
- `POST /api/invoices` - Create invoice
- `PUT /api/invoices/{id}` - Update invoice
- `DELETE /api/invoices/{id}` - Delete invoice

### Payments
- `GET /api/payments` - List payments
- `GET /api/payments/{id}` - Get payment detail
- `POST /api/payments` - Create payment
- `DELETE /api/payments/{id}` - Delete payment

### Trial Balance
- `GET /api/trial-balance?start=YYYY-MM-DD&end=YYYY-MM-DD` - Get trial balance
- `GET /api/trial-balance/export?start=YYYY-MM-DD&end=YYYY-MM-DD&format=xlsx` - Export to Excel

## Sample Data

The seeders will create sample data including:

**Chart of Accounts:**
- 1101 - Cash (DR)
- 1201 - Accounts Receivable (DR)
- 2101 - Accounts Payable (CR)
- 4101 - Revenue (CR)
- 5101 - Expense (DR)
- 6101 - Accrued Expense (CR)

**Sample Journals:**
- Opening accrual entries
- Revenue transactions
- Expense transactions

**Sample Invoices:**
- Multiple invoices with different statuses
- Associated payments

## Usage

### Frontend Features

1. **Dashboard** - Overview dengan statistik dasar
2. **Chart of Accounts** - CRUD operations dengan DataTables
3. **Journals** - Entry jurnal dengan multiple lines dan validasi balance
4. **Invoices** - Management invoice dengan status tracking
5. **Payments** - Recording pembayaran dengan auto-update status invoice
6. **Trial Balance** - Generate laporan dengan date range dan export Excel

### Key Validations

1. **Journal Balance** - Total debit harus sama dengan total credit
2. **Journal Lines** - Setiap line harus memiliki debit atau credit > 0
3. **Invoice Status** - Otomatis update berdasarkan total payments:
   - `open`: Belum ada pembayaran
   - `partial`: Sudah ada pembayaran sebagian
   - `paid`: Sudah lunas

### AJAX Operations

Semua operasi dilakukan via AJAX:
- Create/Edit menggunakan modal forms
- Delete dengan konfirmasi dialog
- Auto-refresh DataTables setelah operasi
- Real-time validation feedback

## Testing

### Using cURL

```bash
# Get Chart of Accounts
curl -X GET http://localhost:8000/api/chart-of-accounts

# Create New Account
curl -X POST http://localhost:8000/api/chart-of-accounts \
  -H "Content-Type: application/json" \
  -d '{"code":"1301","name":"Inventory","normal_balance":"DR","is_active":1}'

# Create Journal Entry
curl -X POST http://localhost:8000/api/journals \
  -H "Content-Type: application/json" \
  -d '{
    "ref_no":"JV-2025-0004",
    "posting_date":"2025-07-30",
    "memo":"Rent expense",
    "status":"posted",
    "lines":[
      {"account_id":5,"debit":1000000,"credit":0},
      {"account_id":1,"debit":0,"credit":1000000}
    ]
  }'
```

### Using Postman

Import the API collection dengan endpoints yang sudah didefinisikan di atas.

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check database credentials in `.env`
   - Ensure MySQL service is running
   - Create database manually if not exists

2. **Migration Errors**
   - Run `php artisan migrate:fresh` untuk reset database
   - Check database user permissions

3. **CORS Issues (jika frontend terpisah)**
   - Install `laravel-cors` package
   - Configure CORS settings

4. **Excel Export Not Working**
   - Ensure `maatwebsite/excel` package is installed
   - Check file permissions for storage directory

5. **Frontend AJAX Errors**
   - Check API_BASE_URL in JavaScript
   - Ensure Laravel server is running
   - Check browser console for detailed errors

## Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
