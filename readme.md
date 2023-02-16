# Hmdqr AudioPress Plugin

The Hmdqr AudioPress plugin is an example project that demonstrates how to create a WordPress plugin for managing and displaying audio content. The plugin allows you to upload audio files and create posts that display the audio content, along with a title, description, and other metadata.

Note: This project was created as an example plugin and is not completed. Due to time constraints, some features were not fully implemented, and there may be issues with the functionality of the plugin. Use this plugin at your own risk, and be sure to thoroughly test it before implementing it on a production site. Contributions to the project are welcome and encouraged, and any help in completing the plugin would be greatly appreciated.

## Features

* Custom post type for audio posts
* Audio file upload and processing
* User page to view uploaded audio posts
* Shortcode to display audio post list
* Like and dislike functionality for audio posts (not fully implemented)
* Comment section for audio posts

## Requirements

* WordPress 5.0 or higher
* PHP 7.0 or higher (I test it with 8.0)

## Installation

1. Download the plugin files and upload them to your WordPress plugins directory (usually `wp-content/plugins`).
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Use the plugin settings to configure the plugin options and upload directory.
4. Use the `[audiopress_user_audio]` shortcode to display a list of audio posts.

## Usage

1. Navigate to the AudioPress settings page to configure the plugin options and upload directory.
2. Use the 'Add New Audio' page to upload new audio files and create new audio posts.
3. Use the shortcode `[audiopress_user_audio]` to display a list of audio posts on any page or post.
4. View the user page at `yoursite.com/my-audio` to see a list of your uploaded audio posts.

## Todo List

* Improve like and dislike functionality
* Add pagination to the audio post list
* Improve front-end design of audio post list and user page
* Add search functionality to the audio post list
* Add support for tags and categories
* Improve handling of audio metadata
* Improve error handling and user feedback

## Contributing

Contributions to this plugin are welcome and encouraged. If you find a bug, have a feature request, or would like to contribute to the plugin, please create a new issue or pull request on the [project's GitHub repository](https://github.com/hmdqr/hmdqr-audiopress-plugin).

## License

This plugin is licensed under the [MIT License](LICENSE).
