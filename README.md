
# Timetable WordPress Theme

**Timetable** is a custom WordPress theme designed for scheduling and course structures. This theme is perfect for educational institutions, coaching centers, or any organization that requires a timetable and course structure management.

## Table of Contents
1. [Features](#features)
2. [Installation](#installation)
3. [Usage](#usage)
4. [Customization](#customization)
5. [Changelog](#changelog)
6. [License](#license)

## Features

- **Timetable Section**: A customizable timetable to display location-specific schedules.
- **Course Structure**: Display term-based course information with lessons for each term.
- **Fully Responsive**: Optimized for all screen sizes, from mobile to desktop.
- **Modern Design**: Clean, minimalistic, and professional layout.
- **Customizable Styles**: Modify colors, fonts, and layout via CSS or WordPress Customizer.
- **Cross-browser Compatible**: Works seamlessly in all modern browsers.
- **SCSS and Webpack Integration**: Built with modern tools for efficient asset management.

## Installation

### Via WordPress Dashboard

1. Download the theme as a `.zip` file.
2. Log in to your WordPress dashboard.
3. Navigate to **Appearance → Themes**.
4. Click **Add New** and then **Upload Theme**.
5. Select the `timetable-theme.zip` file from your computer.
6. Click **Install Now**, then **Activate** the theme.

### Manual Installation

1. Download the theme as a `.zip` file.
2. Unzip the file and upload the `timetable-theme` folder to your WordPress themes directory:
   - `/wp-content/themes/`
3. Log in to your WordPress dashboard.
4. Navigate to **Appearance → Themes**.
5. Locate the **Timetable** theme and click **Activate**.

## Usage

### Timetable Section
The theme provides a timetable section where you can easily input and display schedules. To add or modify the timetable:
1. Open the `template-timetable.php` file and modify the HTML structure to add your schedules.
2. Use WordPress's page builder or theme editor to further customize the page if needed.

### Course Structure
This theme includes a section to display term-based courses and their lessons. The course structure can be modified by editing the HTML in the `template-timetable.php` file or adding new course data directly within the WordPress admin area.

### Customization
The theme uses **SCSS** and **Webpack** for asset management. To customize styles and scripts, follow the steps below:

#### SCSS Compilation and Asset Management
1. Make sure you have **Node.js** installed on your system.
2. Navigate to the theme's directory and run the following commands:
   ```bash
   npm install
   npx webpack
   ```
3. This will compile the SCSS and JavaScript files into `dist/bundle.css` and `dist/bundle.js`.

### Enqueuing Assets
The theme already enqueues the compiled assets. However, if you add new files or want to modify the asset pipeline, you can adjust the enqueue functions in the `functions.php` file:
```php
function enqueue_custom_assets() {
    wp_enqueue_style('theme-styles', get_template_directory_uri() . '/dist/bundle.css');
    wp_enqueue_script('theme-scripts', get_template_directory_uri() . '/dist/bundle.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_assets');
```

### Page Templates
This theme includes a custom template for displaying timetables and course structures. You can use this template when creating a new page by selecting **Timetable Template** in the **Page Attributes** section.

## Customization

### Style Customization
To modify the style, you can directly edit the SCSS files located in the `src/` folder:
- `src/styles.scss` – Main stylesheet.

After making changes to the SCSS file, compile the CSS using Webpack:
```bash
npx webpack
```

### JavaScript Customization
To modify or add JavaScript functionality, you can edit the `src/index.js` file. All custom JavaScript is bundled into `dist/bundle.js`.

## Changelog

### Version 1.0.0
- Initial release with the following features:
  - Timetable section for location-specific schedules.
  - Course structure section with term-based lessons.
  - Responsive layout and modern design.
  - Webpack integration for asset management.

## License

This theme is licensed under the MIT License. You are free to use, modify, and distribute this theme as long as the original license and copyright information are retained.
# timetable_theme
