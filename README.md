# Obuildings - Building Management System

A comprehensive web-based platform designed to streamline building management operations, facilitate resident communication, and automate monthly payment tracking. Built for multi-tenant residential buildings to enhance community engagement and financial transparency.

## Features

### User Management
- **Role-based Access Control** - Separate interfaces for Admins and Residents
- **Secure Authentication** - bcrypt password hashing and session management
- **Resident Registration** - Admin-controlled account creation for security
- **User Status Tracking** - Active residents, admins, and archived accounts

### Payment System
- **Monthly Fee Management** - Track and collect monthly building maintenance fees
- **Stripe Integration** - Secure payment processing with Stripe API
- **Payment History** - Complete transaction records with filtering capabilities
- **Smart Payment Application** - Automatically applies payments to earliest unpaid month
- **Payment Dashboard** - Visual tracking of paid/unpaid/overdue months
- **Transaction Tracking** - Stores Stripe transaction IDs for reconciliation

### Communication & Announcements
- **Building Announcements** - Admin posts with image support
- **Real-time Notifications** - WebSocket-powered live updates
- **Community Engagement** - Like and comment on announcements
- **Announcement Feed** - Chronological display with engagement metrics

### Expense Management
- **Expense Tracking** - Record building maintenance and operational costs
- **Invoice Attachments** - Upload and view expense invoices
- **Monthly Categorization** - Organize expenses by month and year
- **Financial Dashboard** - Overview of total expenses and trends
- **Expense Analytics** - Area charts showing expense patterns over time

### Admin Dashboard
- **Financial Overview** - Total expenses and payment statistics
- **Resident Metrics** - Active resident count and payment status
- **Unpaid Tracking** - Identify residents with outstanding payments
- **Data Visualization** - Charts for expenses and payment trends
- **Quick Actions** - Add residents, expenses, and announcements

### Real-time Features
- **WebSocket Server** - Event-driven architecture for instant updates
- **Live Notifications** - Announcement updates without page refresh
- **Asynchronous Operations** - ReactPHP event loop for performance

## Tech Stack

### Backend
- **PHP 8.1+** - Server-side application logic
- **MySQL/MariaDB** - Relational database management
- **Composer** - PHP dependency management

### Frontend
- **Bootstrap 5** - Responsive UI framework
- **JavaScript (ES6+)** - Client-side interactivity
- **Chart.js** - Data visualization
- **Font Awesome** - Icon library

### Payment Processing
- **Stripe API** - Secure payment gateway integration
- **Stripe Checkout** - PCI-compliant payment forms

### Real-time Communication
- **Ratchet (WebSocket)** - PHP WebSocket library
- **ReactPHP** - Asynchronous event-driven programming

### Key Dependencies
```json
{
  "stripe/stripe-php": "^10.15",
  "cboden/ratchet": "^0.4.4"
}
```

## Installation

### Prerequisites
- PHP 8.1 or higher
- MySQL 5.7+ or MariaDB 10.4+
- Composer
- Apache/Nginx web server
- Stripe account (for payments)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/sandik.git
   cd sandik
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   ```

   Edit `.env` and configure:
   - Database credentials (DB_HOST, DB_NAME, DB_USERNAME, DB_PASSWORD)
   - Stripe API keys (STRIPE_SECRET_KEY, STRIPE_PUBLIC_KEY)
   - Application URL (APP_URL)

4. **Import database**

   **Option A - Via browser:**
   ```
   http://localhost/sandik/import-database.php
   ```

   **Option B - Via MySQL CLI:**
   ```bash
   mysql -u root -p sandik < db/sandik.sql
   ```

5. **Set file permissions**
   ```bash
   chmod 755 includes/uploads
   chmod 644 .env
   ```

6. **Configure web server**

   Point your web server document root to the project directory.

7. **Start WebSocket server** (for real-time features)

   **Linux/Mac:**
   ```bash
   php websocket-server.php
   ```

   **Windows:**
   ```bash
   start-websocket.bat
   ```

8. **Access the application**
   ```
   http://localhost/sandik/
   ```

## Configuration

### Environment Variables

Copy `.env.example` to `.env` and configure:

```ini
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost/sandik/

# Database
DB_HOST=localhost
DB_NAME=sandik
DB_USERNAME=root
DB_PASSWORD=

# Stripe API Keys
STRIPE_SECRET_KEY=sk_test_your_key_here
STRIPE_PUBLIC_KEY=pk_test_your_key_here

# Security
CSRF_SECRET=your_random_secret
SESSION_SECRET=your_session_secret

# File Upload
MAX_UPLOAD_SIZE=5242880
UPLOAD_PATH=uploads/
```

### Application Settings

Default configurations in `config/app-config.php`:
- **Monthly Fee:** 300 MAD
- **Currency:** MAD (Moroccan Dirham)
- **Session Timeout:** 1 hour
- **Max File Upload:** 5MB
- **WebSocket Port:** 8080

## Database Schema

### Tables

| Table | Description |
|-------|-------------|
| `residents` | User accounts (admins and residents) |
| `payments` | Monthly payment records |
| `purchases` | Building expenses and maintenance costs |
| `announcements` | Community announcements |
| `comments` | Comments on announcements |
| `likes` | Announcement likes |

### Key Relationships
- Payments linked to residents via `resident_id`
- Comments and likes linked to both `announcement_id` and `resident_id`
- Unique constraint on likes to prevent duplicate likes

## Usage

### Default Login Credentials

After importing the database, use these credentials:

**Admin Account:**
- Navigate to `/login.php`
- Login with admin credentials from database
- Access admin dashboard at `/index.php`

**Resident Account:**
- Navigate to `/login.php`
- Login with resident credentials
- Access resident dashboard at `/homepage.php`

### Admin Workflows

1. **Add New Resident**
   - Dashboard → "Add Resident" button
   - Enter resident details (name, email, username, password)
   - Resident can now login with provided credentials

2. **Record Expense**
   - Navigate to "Add Expense"
   - Enter expense details (name, description, amount, date)
   - Upload invoice (optional)
   - Expense appears in dashboard and analytics

3. **Post Announcement**
   - Navigate to "Add Announce"
   - Enter title and description
   - Upload image (optional)
   - Announcement published to all residents

### Resident Workflows

1. **View Payments**
   - Dashboard shows payment status
   - Click "Payments" to view detailed history
   - See paid/unpaid/overdue months

2. **Make Payment**
   - Navigate to payment dashboard
   - Click "Pay" on unpaid month
   - Complete Stripe checkout
   - Payment automatically recorded

3. **Engage with Announcements**
   - View announcements on homepage
   - Like and comment on posts
   - Receive real-time notifications

## Project Structure

```
/sandik/
├── classes/              # Core PHP classes (MVC pattern)
│   ├── admin.class.php
│   ├── payments.class.php
│   ├── user.class.php
│   └── ...
├── config/               # Configuration files
│   ├── db-config.php
│   └── app-config.php
├── db/                   # Database schemas
│   └── sandik.sql
├── includes/             # Request handlers
│   ├── login.inc.php
│   ├── add-resident.inc.php
│   └── ...
├── js/                   # JavaScript files
├── css/                  # Stylesheets
├── assets/               # Static assets
├── vendor/               # Composer dependencies
├── uploads/              # User uploads
├── index.php             # Admin dashboard
├── login.php             # Authentication
├── payments.php          # Payment management
├── announces.php         # Announcements
├── websocket-server.php  # WebSocket server
└── composer.json         # Dependencies
```

## Development

### Running Locally

1. Start your web server (XAMPP, WAMP, MAMP, etc.)
2. Start the WebSocket server: `php websocket-server.php`
3. Access via `http://localhost/sandik/`

### Code Style

- Follow PSR-12 PHP coding standards
- Use meaningful variable and function names
- Document complex logic with comments
- Keep functions small and focused

### Security Best Practices

- All passwords hashed with bcrypt
- Prepared statements for SQL queries
- Environment variables for sensitive data
- Session-based authentication
- Role-based access control

## Deployment

### Manual Deployment

1. Upload files to server via FTP/SFTP
2. Configure `.env` with production credentials
3. Import database
4. Set file permissions
5. Configure web server (Apache/Nginx)
6. Start WebSocket server as background process

### Automated Deployment (GitHub Actions)

The project includes a GitHub Actions workflow for automated deployment:

- **Trigger:** Manual (workflow_dispatch)
- **Target:** EC2 instance via SSH
- **Process:** Git pull, Composer install, permission fixes, health checks

To use:
1. Configure GitHub Secrets (EC2_SSH_KEY, EC2_HOST, EC2_USER)
2. Navigate to Actions tab in GitHub
3. Run "Deploy Sandik to EC2" workflow

## API Integration

### Stripe Webhooks (Future Enhancement)

Configure Stripe webhooks for:
- Payment confirmations
- Failed payment notifications
- Refund processing

### WebSocket Events

Current WebSocket events:
- New announcement notifications
- Real-time updates

## Troubleshooting

### Common Issues

**Database Connection Error**
- Verify `.env` credentials are correct
- Ensure MySQL service is running
- Check database exists and user has permissions

**Stripe Payment Failures**
- Verify API keys in `.env`
- Check Stripe dashboard for error logs
- Ensure test mode keys for development

**WebSocket Not Connecting**
- Verify WebSocket server is running
- Check firewall allows port 8080
- Confirm hostname in `config/app-config.php`

**Upload Errors**
- Check `uploads/` directory permissions (755)
- Verify `MAX_UPLOAD_SIZE` in `.env`
- Ensure PHP `upload_max_filesize` is sufficient

## Contributing

This is a portfolio project. Feedback and suggestions are welcome!

## License

This project is developed as an educational/portfolio project.

## Acknowledgments

- Developed as Final Year Project at SOLICODE
- Built with modern PHP and web technologies
- Designed for real-world building management needs

## Contact

For questions or inquiries about this project, please reach out through GitHub.

---

**Note:** This is a demonstration project. For production use, additional security hardening, testing, and compliance measures are recommended.
