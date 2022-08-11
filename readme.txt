=== Another Show Hooks ===
Contributors: exlac
Donate link: https://exlac.com/product/show-hooks-pro/
Tags: hooks, debug, action, filter, hook sequence
Requires at least: 5.2
Requires PHP: 7.2
Tested up to: 6.0
Stable tag: 1.0.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Show the origin of an action or filter hook sequentially which helps a developer to debug quickly.

== Description ==

A sequential and visual representation of WordPress action and filter hooks. This is a Developer tool so if you are a developer then it might be a life-saver plugin.

== How? ==

Let's have a question for you! 

Which hook fire first?

1. `admin_init`
2. `init`

A bit confusing, right? `init` runs first and after firing almost 100 hooks `admin_init` runs. Before developing the plugin I had to jump over google or wp handbook which kills my development time and makes my project delayed.

To get rid of this problem, Another Show Hooks present you with a graphical overview right from your web screen. 

== Some cool features ==

👉 Graphical Presentation
👉 Count the total number of uses
👉 List out all the callback functions with **priority**
👉 Get core code reference
👉 Draggable hook window for flexible debugging

🔥🔥🔥 **[Pro Features](https://exlac.com/product/show-hooks-pro/)**🔥🔥🔥 

>*Hook search with the prefix
>*Advanced debugging with selective user roles
>*Disable callback function directly from pages
>*Automation

== Contribute to Show Hooks ==

If you want to contribute to the project, you’re most welcome to make it happen. The full source code is available on [GitHub](https://github.com/vairafiq/another-show-hooks). If you find anything improbable, feel free to shoot a bug report or create a pull request.

== Origin ==

We are proud the original base of the plugin was a fork of [Simply Show Hooks](https://wordpress.org/plugins/simply-show-hooks/) as it has not been maintained for the last 6 years and has lots of compatibility issues. We have improved the code security and made it compatible with the latest WordPress.

== Installation ==

Using the WordPress Plugin Search

1. Navigate to the `Add New` sub-page under the Plugins admin page.
2. Search for `another show hooks`.
3. The plugin should be listed first in the search results.
4. Click the `Install Now` link.
5. Lastly click the `Activate Plugin` link to activate the plugin.

Uploading in WordPress Admin

1. [Download the plugin zip file](https://wordpress.org/plugins/another-show-hooks/) and save it to your computer.
2. Navigate to the `Add New` sub-page under the Plugins admin page.
3. Click the `Upload` link.
4. Select Another Show Hooks zip file from where you saved the zip file on your computer.
5. Click the `Install Now` button.
6. Lastly click the `Activate Plugin` link to activate the plugin.

Using FTP

1. [Download the plugin zip file](https://wordpress.org/plugins/another-show-hooks/) and save it to your computer.
2. Extract the Another Show Hooks zip file.
3. Create a new directory named `another-show-hooks` directory in the `../wp-content/plugins/` directory.
4. Upload the files from the folder extracted in Step 2.
4. Activate the plugin on the Plugins admin page.

== Screenshots ==

1. Firing sequence of init and admin_init
2. Dragable hook window
3. Uses of init hook

== Changelog ==

= 1.0.0 =

* Initial release