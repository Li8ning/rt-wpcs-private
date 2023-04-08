# WP Beautiful Slideshow

WP Beautiful Slideshow is a lightweight WordPress plugin that allows users to easily create and customize beautiful image slideshows on their WordPress websites. With this plugin, users can add multiple images, rearrange them as per their choice, and display the slider on any page or post using the `[wpbfslideshow]` shortcode. The plugin is mobile-friendly and includes the following features:

- Add multiple images through plugin settings page in WordPress admin panel
- Rearrange images as per your choice
- Display the slider using the `[wpbfslideshow]` shortcode on any page or post
- Mobile-friendly slider design
- Use the `[wpbfslideshow]` shortcode multiple times on the same page using the `id` attribute, e.g. `[wpbfslideshow id="1"]`, `[wpbfslideshow id="2"]`, etc.

## Libraries

WP Beautiful Slideshow uses the following libraries:

- [jQuery UI Sortable](https://jqueryui.com/sortable/)
- [Swiper Slideshow Library](https://swiperjs.com/)

## Installation

To install the WP Beautiful Slideshow plugin, follow these steps:

1. Download the plugin from the WordPress Plugin Directory or from the GitHub repository.
2. Upload the `wp-beautiful-slideshow` folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Go to the WP Beautiful Slideshow settings page at `/wp-admin/options-general.php?page=wpbf-slideshow` and start adding your images.

## Usage

1. Go to the WPBF Slideshow settings page in the WordPress admin dashboard
2. Upload images and change their order as per your choice
3. Add the `[wpbfslideshow id="1"]` shortcode to any page or post to display the slideshow
4. To display another slideshow on the same page with a different set of images, use a different `id` value: `[wpbfslideshow id="2"]`

## WordPress Coding Standards

WP Beautiful Slideshow follows WordPress coding standards to ensure high-quality, secure, and stable code.

## Data Storage

WP Beautiful Slideshow registers the `wpbf_slideshow_image_ids` option in WordPress to store image data in the database. All the plugin data will be permanently removed on uninstalling the plugin.

## Demo

Check out the [WP Beautiful Slideshow demo](https://dharmrajsinhjadeja.com/wpbf-slideshow-demo) to see the plugin in action.
