# DocsPopular - WordPress Documentation Plugin

A beautiful and minimal documentation system for WordPress themes and plugins.

## Features

- 📚 Custom Post Type for documentation pages
- 🗂️ Category system for organizing docs by theme/plugin
- 🎨 Gorgeous minimal design
- 📱 Fully responsive layout
- 🔍 Built-in search functionality
- ⌨️ Keyboard navigation support
- 🎯 Shortcode for displaying category grid
- 📄 Custom page template
- ⚡ Performance optimized - Assets only load on documentation pages

## Installation

1. Upload the `docspopular` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You'll see a new "DocsPopular" menu item in your admin panel

## Usage

### Creating Documentation

1. Go to **DocsPopular** > **Add New** in your WordPress admin
2. Create your documentation page with title and content
3. Assign it to a **Doc Category** (create categories under **DocsPopular** > **Categories**)
4. Publish your documentation

### Creating Categories

1. Go to **DocsPopular** > **Categories**
2. Add a new category (e.g., "My Theme", "My Plugin")
3. Optionally add a description
4. Save the category

### Displaying Documentation

#### Method 1: Using Shortcode

Add this shortcode to any page or post to display all documentation categories:

```
[docspopular_categories]
```

#### Method 2: Using Page Template

1. Create a new page (e.g., "Documentation")
2. In the page editor, select **Template** > **DocsPopular** from the page attributes
3. Add your content and shortcode if desired
4. Publish the page

### Category Archive Pages

When users click on a category from the grid, they'll see:
- **Left Sidebar**: List of all documentation pages in that category
- **Right Content Area**: The actual documentation content

The interface includes:
- Smooth navigation between docs
- Search functionality
- Keyboard navigation (↑↓ arrow keys)
- Clean, readable design

## Shortcode Options

The `[docspopular_categories]` shortcode supports these parameters:

- `orderby` - Sort categories by name, count, etc. (default: 'name')
- `order` - ASC or DESC (default: 'ASC')

Example:
```
[docspopular_categories orderby="count" order="DESC"]
```

## Customization

### Styling

All styles are in `/assets/css/docspopular.css`. You can override these in your theme's CSS file:

```css
:root {
    --docspopular-primary: #4F46E5; /* Change primary color */
    --docspopular-text-dark: #1F2937; /* Change text color */
}
```

### Template Customization

You can override the plugin templates by copying them to your theme:

1. Copy `/wp-content/plugins/docspopular/templates/taxonomy-docspopular_category.php`
2. Paste to `/wp-content/themes/your-theme/docspopular/taxonomy-docspopular_category.php`
3. Customize as needed

## Performance Optimization

DocsPopular is built with performance in mind. CSS and JavaScript files are **only loaded** on:
- Single documentation pages
- Documentation category archives  
- Pages using the DocsPopular template
- Pages/posts containing the `[docspopular_categories]` shortcode

This means your other pages remain lightning fast! ⚡

## Tips

- Use the **Order** field in page attributes to control the order of docs in the sidebar
- Add featured images to make your documentation more visual
- Use categories to separate documentation for different products
- The search box in the sidebar helps users find specific docs quickly

## Support

For issues or feature requests, please contact the plugin author.

## License

GPL v2 or later

---

**Version:** 1.0.0  
**Author:** DocsPopular Team

