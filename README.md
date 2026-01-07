# Course Platform Survey

A WordPress template that collects user preferences for BCIT courses and displays results in a pie chart.

## Files
- `course-survey.php` - Main WordPress template file with form and data handling
- `pie.js` - JavaScript file for creating the pie chart visualization

## Features
- Collects user name and course preference (MySQL, Android, Javascript)
- Stores data in WordPress options table
- Displays results in an interactive pie chart
- Pie chart shows percentages inside slices
- Responsive design
- Secure form handling with nonce verification

## Installation
1. Copy both files to your WordPress theme directory
2. Create a new page in WordPress
3. Select "Course Platform Survey" as the template
4. The page will automatically load the form and chart

## Output Example
The application displays:
- "BCIT" header
- "Here is BCIT course app" description
- Form with name input and radio buttons
- Pie chart showing percentages:
  - Javascript: 0.0 %
  - Android: 66.7 %
  - MySQL: 33.3 %

## Technologies
- PHP 7.0+
- WordPress 5.0+
- MySQL 5.6+
- Highcharts.js (loaded from CDN)
- JavaScript (ES6)
- HTML5/CSS3

## Security
- WordPress

