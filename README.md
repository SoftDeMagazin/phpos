# phpos - Point of Sale System

A comprehensive Point of Sale (POS) system developed in PHP for Romanian retail and restaurant businesses.

## Overview

**phpos** is a production-ready, business-critical application that handles the complete retail workflow from product reception to fiscal compliance. It features fiscal printer integration, multi-register support, extensive inventory management, and a touch-optimized interface.

## Key Features

### Sales & Orders
- Order management system
- Consumption receipts (bonuri consum)
- Sales records and tracking

### Inventory Management
- Product reception with touch keyboard
- Batch/lot tracking
- Stock valuation
- Inventory records
- Product transformations

### Financial Operations
- Cash register management
- Day-end closing procedures
- Price modifications
- Management reports
- Sales reports

### Partner Management
- Customer database
- Supplier management
- Supplier product catalog

### Document Management
- NIR documents (receipt documents)
- Receipt records
- Returns processing
- Invoice generation

### Romanian Fiscal Compliance
- Integration with fiscal printers (Datecs, Zeka, Elka, Sapel)
- Electronic Cash Register (ECR) support
- VAT (TVA) handling
- Fiscal receipt generation

### Touch-Screen Interface
- Custom on-screen QWERTY keyboard
- Numeric keypad for quantity/price entry
- Optimized for POS terminal operation

## Technology Stack

### Backend
- **PHP 7.2**
- **MySQL Database** (database name: `retail`)
- **Active Record Pattern** via AbstractDB base class

### Frontend
- **HTML 4.01 Transitional**
- **jQuery 1.2.6**
- **jQuery UI** (full suite)
- **xajax** - PHP AJAX framework
- **ISO-8859-1** encoding (Romanian characters)

### Third-Party Libraries
- **PHPExcel** - Excel file generation and export
- **libchart** - Chart and graph generation
- **Barcode generation library**

### Deployment
- **Google App Engine** (PHP flexible environment)

## Project Structure

```
phpos/
├── config/           # Configuration files
│   ├── config.db.sample.php
│   ├── config.fiscal.php
│   ├── config.imprimante.php
│   └── date_firma.php
├── css/              # Stylesheets
├── db/               # Database schemas and migrations
│   ├── retail_empty.sql
│   ├── retail.sql
│   └── views.sql
├── include/          # Core application code
│   ├── db/          # Database abstraction layer
│   ├── helpers/     # Helper classes (forms, GUI, keyboards)
│   ├── models/      # Business logic models (33+ classes)
│   ├── libchart/    # Chart generation library
│   ├── rapoarte/    # Reports
│   └── xajax_global/ # Global AJAX functions
├── js/              # JavaScript libraries
├── thirdparty/      # Third-party libraries
│   ├── PHPExcel/
│   ├── barcode/
│   └── xajax/
├── views/           # View templates
│   ├── comanda/
│   ├── config.produse/
│   ├── intrari/
│   ├── login/
│   └── receptie/
└── *.php            # Module entry points (80+ files)
```

## Architecture

### Design Pattern
The project follows a custom MVC-like architecture:
- **Models**: Active Record pattern with 33+ business entity classes
- **Views**: Separate PHP templates in `/views/` directory
- **Controllers**: Server-side AJAX handlers in `*.server.php` files

### File Naming Convention
Each module follows a three-file pattern:
- **`module.php`** - Main view/UI file (HTML + JavaScript)
- **`module.common.php`** - Shared includes, AJAX function registration
- **`module.server.php`** - Server-side AJAX handlers and business logic

### Database Abstraction
The `AbstractDB` base class provides:
- Automatic CRUD operations (insert, update, delete)
- Dynamic object building from database schema
- Form generation from model definitions
- Query builder with options arrays
- Support for relationships

## Core Modules

### Main Entry Points
- **`index.php`** - Redirects to login
- **`login.php`** - Authentication system
- **`comanda.php`** - Main order/sales interface
- **`receptie.tastatura.php`** - Reception/intake with keyboard

### Business Modules (27+)
- Product configuration (`config.produse`)
- Inventory management (`inventar`)
- Cash register (`registru.casa`)
- Day closing (`inchidere.zi`)
- Reports (`rapoarte`, `raportgestiune`)
- Customer management (`clienti`)
- Supplier management (`furnizori`)
- And many more...

## Database

### Main Schema
- **Primary Database**: `retail`
- **Schema File**: `db/retail_empty.sql`
- **Full Data**: `db/retail.sql`
- **Views**: `db/views.sql`

### Key Tables
- `bonuri` - Receipts/tickets
- `bonuri_continut` - Receipt line items
- `bonuri_plata` - Payment methods
- `comenzi` - Orders
- `produse` - Products
- `clienti` - Customers
- `furnizori` - Suppliers
- `inventar` - Inventory
- `niruri` - NIR documents
- `users` - System users
- `case_fiscale` - Fiscal cash registers
- `zile_economice` - Economic/business days

## Configuration

### Database Setup
1. Copy `config/config.db.sample.php` to `config/config.db.php`
2. Update database credentials:
   - Server: 127.0.0.1
   - Database: `retail`
   - Username: your_username
   - Password: your_password
3. Import database schema: `db/retail_empty.sql`

### Fiscal Printer Configuration
Edit `config/config.fiscal.php` to configure:
- Fiscal printer brand (Datecs, Zeka, Elka, Sapel)
- File path for fiscal receipts (default: `C:\xampp\bonuri\`)

### Company Information
Update `config/date_firma.php` with your company details.

## Multi-User & Multi-Register Support

- User authentication and permissions system
- Multiple cash register support
- Economic day tracking and business day closure
- User role management (`drepturi.users.php`)

## Reporting & Export

- Management reports
- Sales reports
- Inventory reports
- Excel export capabilities (via PHPExcel)
- Print functionality for all documents

## Project Statistics

- **Total PHP Files**: 430+
- **Model Classes**: 33+
- **Functional Modules**: 27+
- **Lines in Largest File**: 924 (comanda.server.php)
- **Database Tables**: 20+ main tables
- **Third-Party Libraries**: 3 major (xajax, PHPExcel, libchart)

## Requirements

- PHP 7.2 or higher
- MySQL 5.x or higher
- Web server (Apache/Nginx)
- Modern web browser with JavaScript enabled
- (Optional) Fiscal printer for Romanian compliance

## Special Features

- Touch-screen optimized interface
- On-screen keyboards for easy data entry
- Real-time AJAX updates (no page reloads)
- Fiscal printer integration for Romanian market
- Comprehensive reporting system
- Batch/lot tracking for inventory
- Product transformation capabilities
- Multi-payment method support

## Language

The application is designed for Romanian businesses:
- Interface in Romanian language
- ISO-8859-1 encoding for Romanian characters (ă, â, î, ș, ț)
- Romanian fiscal compliance
- Romanian business document formats (NIR, factură, etc.)

## License

Proprietary software developed for Romanian retail businesses.

---

**phpos** - Sistemul complet de gestiune pentru magazine și restaurante
