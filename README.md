# Course-Platform-Survey

A WordPress template that collects BCIT course platform preferences from users and visualizes the results using Highcharts.  
This is an educational project created to demonstrate full-stack integration in a WordPress environment.

## Features
- Custom WordPress template
- Collects user input via a form
- Stores submissions in the WordPress database (`wp_options`)
- Displays aggregated results as an interactive pie chart using Highcharts
- Secure form handling with nonce verification and input sanitization

## Technologies Used
- PHP (WordPress Template)
- JavaScript (Highcharts for chart visualization)
- MySQL (via WordPress database)

## Installation
1. Clone or download this repository into your WordPress theme folder.
2. Place the template file (`course-platform-survey.php`) in your active theme directory.
3. Ensure `pie.js` is also in the theme directory.
4. Open WordPress Admin, create a page, and assign the "Course Platform Survey" template to it.

## Usage
1. Visit the page using the template.
2. Submit your name and preferred BCIT course platform (MySQL, Android, or Javascript).
3. Results will be stored in the database and displayed as a pie chart.

## Notes
- Data is intentionally stored in `wp_options` for simplicity in an educational context.
- Input is sanitized and verified using WordPress nonces for security.
- This is not a production-ready plugin, but an educational example.

