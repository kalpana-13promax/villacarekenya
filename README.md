# VillaCare Kenya - Property Rental & Sales Platform

A modern, responsive web application for browsing and managing property listings in Kenya. Built with PHP, Bootstrap 5, and contemporary web technologies.

## 📋 Overview

VillaCare Kenya is a comprehensive real estate platform that allows users to explore properties for sale and rent with detailed information, image galleries, and property comparisons. The platform features an intuitive interface with smooth animations and responsive design.

## ✨ Features

- **Property Listings**: Browse properties for sale and rent
- **Detailed Property Pages**: Comprehensive property information with:
  - High-quality image galleries
  - Location information with Google Maps integration
  - Property amenities and specifications
  - Pricing details with installment options
  - Property video tours
  - Architecture/floor plans
- **Similar Properties**: Recommended properties based on current listing
- **Responsive Design**: Mobile-friendly interface for all devices
- **Modern UI**: Elegant design with Playfair Display and Inter fonts
- **Smooth Animations**: AOS (Animate On Scroll) integration
- **Interactive Components**: 
  - Image carousel with thumbnails
  - Tab switching for different property views
  - Hover effects and transitions

## 📁 Project Structure

```
villacare/
├── index.php                    # Home page
├── property.php                 # Properties listing page
├── property-details.php         # Individual property details
├── blog.php                     # Blog listing
├── blog-details.php             # Individual blog post
├── about.php                    # About page
├── services.php                 # Services page
├── contact.php                  # Contact page
├── for-sale.php                 # Properties for sale
├── for-rent.php                 # Properties for rent
├── 404.php                      # Error page
├── assets/
│   ├── css/
│   │   └── style.css            # Custom styles
│   ├── images/                  # Image assets
│   └── js/
│       └── main.js              # JavaScript functionality
└── layout/
    ├── header.php               # Header component
    ├── footer.php               # Footer component
    ├── link.php                 # Link resources
    ├── slider.php               # Slider component
    ├── testimonials.php         # Testimonials section
    └── property-featured.php    # Featured properties
```

## 🎨 Design Features

### Color Scheme
- **Primary Accent**: #d4a85f (Gold)
- **Dark Text**: #1e2a2e (Dark Blue-Gray)
- **Light Gray**: #546e7a (Secondary Text)
- **Background**: #f8f6f4 (Warm White)

### Typography
- **Headings**: Playfair Display (serif)
- **Body Text**: Inter (sans-serif)

### Components
- Custom property cards with hover effects
- Styled amenity badges
- Price boxes with installation plans
- Interactive tabs for property information
- Breadcrumb navigation
- Similar properties carousel

## 🚀 Getting Started

### Requirements
- PHP 7.4 or higher
- Web server (Apache/Nginx)
- Modern web browser with JavaScript enabled

### Installation

1. **Clone or extract the project** to your web server directory:
   ```
   C:\xampp\htdocs\villacare
   ```

2. **Ensure proper folder permissions** for asset access

3. **Access the application** through your browser:
   ```
   http://localhost/villacare
   ```

### File Structure Setup
Ensure all directories exist:
- `assets/css/` - Stylesheet files
- `assets/images/` - Image assets
- `assets/js/` - JavaScript files
- `layout/` - Reusable PHP components

## 📦 Dependencies

### External Libraries (via CDN)
- **Bootstrap 5.3.2** - CSS Framework
- **Bootstrap Icons 1.11.3** - Icon library
- **AOS (Animate On Scroll) 2.3.1** - Animation library
- **Google Fonts** - Playfair Display & Inter fonts

### Languages & Technologies
- **PHP** - Server-side logic
- **HTML5** - Markup
- **CSS3** - Styling
- **JavaScript** - Client-side interactivity

## 🔧 Configuration

### Includes & Layout
The application uses modular PHP includes:

```php
<?php include 'layout/header.php'; ?>
<?php include 'layout/footer.php'; ?>
<?php include 'layout/link.php'; ?>
```

Update these files to maintain consistent header, footer, and resource links across all pages.

## 🎯 Key Pages

### Home (index.php)
Landing page with featured properties and testimonials

### Properties (property.php)
Full property listings with filtering options

### Property Details (property-details.php)
Detailed view with:
- Full image gallery
- Property specifications
- Location map
- Video tour
- Similar properties

### Blog (blog.php)
Real estate articles and insights

### Services (services.php)
Platform services and information

### Contact (contact.php)
Contact form and information

## 📱 Responsive Breakpoints

- **Desktop**: > 992px
- **Tablet**: 768px - 992px
- **Mobile**: < 768px
- **Small Mobile**: < 576px

## 🎬 JavaScript Functions

### Property Details Page (`property-details.php`)
- `changeImage(element)` - Switch main gallery image
- `showTab(tabName)` - Toggle between Map, Video, and Architecture tabs
- `AOS.init()` - Initialize scroll animations

## 🌐 Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## 📝 Content Management

Property information is currently static. To integrate with a backend:
1. Update PHP files to fetch data from database
2. Modify property-details.php to use dynamic content
3. Update similar properties section with database queries

## 🔐 Security Notes

- Sanitize all user inputs
- Use prepared statements for database queries
- Validate contact form submissions
- Implement CSRF protection for forms

## 🚀 Future Enhancements

- Database integration for dynamic content
- User authentication system
- Property management dashboard
- Advanced search and filtering
- Inquiry/booking system
- Payment gateway integration
- Admin panel

## 📧 Support

For support or inquiries, contact through the contact form or email provided on the contact.php page.

## 📄 License

This project is for VillaCare Kenya. All rights reserved.

---

**Last Updated**: February 2026  
**Version**: 1.0.0
