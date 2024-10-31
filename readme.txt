=== PausAR - 3D and AR for Elementor ===
Contributors: pausarstudio
Tags: Augmented Reality, AR, Model Viewer, 3D Viewer, Web AR
Requires at least: 5.9.0
Tested up to: 6.6.2
Stable tag: 1.3.1
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html


PausAR is a user-friendly and web-based 3D & augmented reality viewer that can be easily integrated into any Elementor page.

== Description ==

Expand the functionality of your Elementor pages with PausAR and create virtual augmented reality content and interactive 3D model viewers directly on your website in just a few steps. Add 3D models to any Elementor page as quickly and easily as adding images or videos. Simply drag and drop to create virtual WebAR scenes that can be launched and displayed directly from the web browser on Android and iOS smartphones or tablets without installing an app (also works on Apple Vision Pro). PausAR expands Elementor's website building tools with a widget that makes adding 3D & AR content a breeze. See our PausAR <a href="https://www.pausarstudio.de/wordpress-elementor/">demos</a> in action.

[youtube https://www.youtube.com/watch?v=PuB3fu2KoxI&t=4s]
  
= Features (Free Version): =
* **WebAR** - Display AR models on Android and iOS devices with SLAM tracking and 6DoF. It can be started directly from the browser and does not require downloading an additional app.
* **Choose between horizontal (default) or vertical surface detection** - You can pin your AR scenes to vertical or horizontal real-world surfaces on Android devices. Apple devices automatically change the placement method when a model is dragged onto a wall or floor.
* **Easy to use 3D viewer** - Display 3D (GLTF) models in an interactive viewer directly on your website. Models can be rotated by the user and viewed from all angles.
* **AR support detection** - Browsers on mobile and desktop devices are automatically checked for AR support when accessing websites with PausAR content. If support is missing, AR buttons are automatically hidden when the page is opened or a redirection to other browsers/devices will be recommended.
* **Redirection via QR code** - Quickly switch to a supported AR device using auto-generated QR codes, such as when trying to launch an AR scene in a desktop browser.
* **Suitable for social media** - Thanks to automatic support detection, links from AR websites can be shared on social media without hesitation. Every user can view the AR content or receive a notification about the lack of AR support and get the possibility to switch to a supported browser/device.
* **Simple drag n' drop design** - PausAR is compatible with every Elementor theme we know of. All the tools you need can be found directly in the new widget. Simply pull it to the webpage and get started.
* **Easy customization** - 3D and AR content can be quickly added and customized, similar to other Elementor widgets. More complex settings are also possible using the extensive widget menu. Copyright information, trademarks, logos, names and company designations may not be changed, removed or otherwise made invisible or unrecognizable in any way.
* **Multiple AR scenes per page** - The number of AR scenes and 3D viewers are not limited. Add as many PausAR widgets to your Elementor page as you like.
* **Hiding the loading time** - The asynchronous loading behavior of the model preview can be freely selected and the loading time can be hidden with the automatic creation of placeholder images.
* **Independent (self-) hosting of 3D files** - All 3D models are hosted independently in your Wordpress database and do not require an external hosting service from PausAR.
* **Apple Vision Pro ready** - Support for Apple Vision Pro has been confirmed (tested with *VisionOS 1.0*)
* **Full screen mode** - Users can unlock an optional full-screen mode that can display the 3D preview across the entire screen when the mode is switched in the frontend.

= Features (Pro Version): =
* **All basic features included** - All functions of the free version are also included in the Pro version.
* **Optional brand removal** - Watermarks and logos of PausAR can optionally be hidden and fully removed.
* **True-Scale AR option** - Use PausAR to display virtual products or other models in AR and optionally prevent the user from scaling your AR scenes. When activated, users can no longer scale models and the realistic size of the model is enforced.
* **UI customization** - Extended options for customizing the user interface inside and outside the AR scene are added. Headlines and custom linked "Call to action" buttons can be displayed in AR mode.
* **Extended preview** - More options for previewing AR content on the website. In addition to deactivating the preview, you can choose between a 2D or 3D mode.
* **Extended interactions** - The 3D preview is supplemented by further options in the form of zoom, animation and motion effects.
* **More visual customization** - Further options for the visual design of buttons and preview elements. Shadow effects, custom icons, 360° backgrounds and more...
* **Reality Support** - Expanded possibilities for creating AR scenes for Apple devices by adding support for *Reality* files.
* **Sound in the AR scene** - Option to upload an .mp3 or .wav file that will be played in the AR scene on Android devices. Apple devices must include the sound file in the .usdz or .reality file using Apple's Reality Composer.
* **Custom anchor links** - A user-defined anchor link can be added to the redirect that occurs when AR support is missing. By default, the anchor is taken from the button to start the AR scenes.
* **Autostart** - Users can specify whether scanning the QR code automatically starts the AR scene when it is opened.
* **Show annotations** - Optionally, the dimensions of the 3D models can be displayed within the preview. These dimensions are automatically calculated from the size of the model.
* **Host your own iframes (optional)** - Enable the optional mode to easily stream your PausAR widgets as iframes on other sites, making your website the host.

Check out Pricing and full features of <a href="https://www.pausarstudio.de/wordpress-elementor/">PausAR Pro</a> today!

= Known Issues =
* Problems when using the plugin **WP-Optimize** and the following features:<br><br>
 * **JavaScript minification**: JavaScript code will be compressed to shorten loading times. This makes PausAR's code unusable. If you want to use WP-Optimize with PausAR, you must add the PausAR plugin file path as an exception within the WP-Optimize settings. To do this, add the following file paths:<br>`/wp-content/plugins/pausar-3d-ar-for-elementor`<br>`/wp-content/plugins/pausar-3d-ar-for-elementor-premium`
 * **JavaScript processing (since v.3.7)**: This feature causes ES6 modules to no longer load correctly, which makes many modern plugins unusable, including PausAR. It is recommended to disable this feature, otherwise the 3D preview and AR mode will not work

* If the plugin **LiteSpeed ​​Cache** is used with the activated "UCSS" function, visual problems may occur. The CSS classes of the plugin can then only be partially loaded.

* 3D models cannot be loaded in AR mode if the paths are not specified with the global path. This only affects models whose files were not inserted directly via the Wordpress media library.

* Conflicts may arise when using Google's Model-Viewer on the same website page. If PausAR is used, further imports of the Model-Viewer should be avoided and are not needed even for custom coding.

= Development =
This plugin uses external libraries. The corresponding development files can be found here:
[https://github.com/google/model-viewer/tree/master/packages/model-viewer](https://github.com/google/model-viewer/tree/master/packages/model-viewer)
[https://github.com/englishextra/qrjs2](https://github.com/englishextra/qrjs2)

Depending on the structure of the content, some frameworks used in this plugin can load required scripts from the Internet. The following URLs can be loaded via cross-origin:

* https://www.gstatic.com/draco/versioned/decoders/1.5.6/
* https://www.gstatic.com/basis-universal/versioned/2021-04-15-ba1c3e4/
* https://cdn.jsdelivr.net/npm/three@0.149.0/examples/jsm/loaders/LottieLoader.js

== FAQ ==

= Which file types are required for the 3D models/scenes? =
GLTF (*.glb) files are required for the 3D preview in the browser and the AR scenes on Android devices. Apple devices, such as iPhone, iPad or Apple Vision Pro, on the other hand, require **USDZ** (*.usdz) files to display AR content. PausAR Pro also adds support for **Reality** (*.reality) files for Apple devices.

= Do I have to add models for Android and iOS? =
To ensure that all visitors to your website can see the AR scenes, each scene must have a dedicated model in both formats (.glb, .usdz) for Android and iOS/Apple devices. However, only a GLTF file is required for the 3D preview in the browser.

= Do I need Elementor Pro? =
No, our plugin already works with the free version of Elementor.

= Where are this plugin’s Settings located? =
You can find all available settings directly in the Elementor widget. There is no central “general” settings menu.

= I have activated PausAR. Where can I find the plugin in Wordpress? =
PausAR is an Elementor widget and can be found in the Elementor element list under the name *PausAR Viewer*. The new widget will be listed in the *PausAR* category

= Can I use PausAR without Elementor? =
No. Without the free Elementor plugin you cannot use our PausAR tool.

= Having trouble? =
Please contact us via email for Bug reporting or feature requests:
[support@pausarstudio.de](mailto:support@pausarstudio.de)

== Installation ==
1. Go to the plugin menu on the WordPress admin page.
2. Click on “Add Plugin”.
3. Search for “PausAR” or manually add the plugin ZIP file.
4. Install the **[Elementor Page Builder](https://wordpress.org/plugins/elementor/)** plugin if not already added.
5. Activate the plugin in the plugin menu of the admin page.
6. After activation, a new widget can be found in the Elementor widgets.

== Screenshots ==

1. PausAR Viewer supports AR on over 2.8 billion devices
2. PausAR Viewer is fully integrated into the Elementor page builder
3. PausAR Viewer is highly customizable and meets all your styling needs

== Changelog  ==

= 1.3.1 =

* Bugfixes
    * Fixed 500 server error that occurred when refreshing Elementor pages when using a PausAR widget and the Yoast SEO Elementor integration together
    * Improved design options for the start button and icon, especially when using "RTL" languages
    * Global colors (Elementor) are now adopted again by the start button and other elements
* New Admin Menu
    * New dashboard added for PausAR
    * New Beginner's Guide for PausAR added

= 1.3.0 =

* Bugfixes
    * Fixed error when entering empty slider values
    * Missing CallToAction links are now displayed again on Android
* Small changes
    * Revised Popup Generator
    * New option to block camera reset within the preview
    * Revision of the Iframe mode (Pro)
    * New features for configuring skyboxes (Pro)
* Optional full screen mode added
* Added option to show dimensions in preview (Pro)
* Added option to enable autostart via QR code (Pro)

= 1.2.2 =

* Bugfixes
    * File path repair (for HTTP paths) no longer produces errors when preview is disabled.

= 1.2.1 =

* Automatic repair of file paths for 3D models has been added. Only used by hosts with 'https' protocols.
* Additional settings regarding camera panning in the 3D preview
* Bugfixes
    * Improved validation of Camera Orbit settings
* Style changes
    * Improved protection of popup text direction
    * Improved support for text written from right to left

= 1.2.0 =

* Added vertical surface placement for GLTF files
* Added options to add sound files to AR scenes
* Bugfixes
* Style changes
    * Improved protection of popup color settings

= 1.1.6 =

* Changes for initial release on wordpress.org
* Bugfixes

= 1.1.5 =

* Improved iframe mode
* Bugfixes

= 1.1.4 =

* Security updates
* Improvements to the server validator
* Changes to text domains and plugin names

= 1.1.3 =

* MimeType notice
* Iframe-support
* Bugfixes

A list of all change logs can be found [here](https://www.pausarstudio.de/wordpress-elementor/changelog)
