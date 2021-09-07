=== Show Hooks ===
Contributors: rafiq91
Tags: hooks, debug, action, filter, hooksequence
Requires at least: 4
Tested up to: 5.8
Stable tag: 0.1.6
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Show the origin of a action or filter hook sequentially which helps a developer to debug quickly.

== Description ==

A sequential and visual representation of WordPess action and filter hooks. Up until now the way theme and plugin developers find where to hook into with their add_action() and add_filter() functions is either to search through the WordPress code base, or find find a reference to the hook in the codex.

What Show Hooks does is give you a simpler way to see these hooks by displaying them in-line on the page that you are on. All that's left to do then is copy the hook you need, and paste it in to your add_action and add_filter functions like this:
add_action( 'wp_enqueue_scripts', 'my_enqueue_scripts_action' );
add_filter( 'the_title', 'my_the_title_filter' );

I made this plugin so I could find WordPress hooks quicker and I use it all the time, so I thought I'd share it and see if it could help others.

Enjoy!

Please note that in odd cases, due to the nature of actions and filters, the plugin may display a hook in place that disrupts the display of your site - in which case we always display the 'Hide Hooks' button so you can switch it back off. This won't disrupt the look of you site to anyone but you so don't worry.

== Contribute to Show Hooks ==

If you want to contribute to the project, you’re most welcome to make it happen. The full source code is available on [GitHub](https://github.com/vairafiq/show-hooks). If you find anything improbable, feel free to shoot a bug report or create a pull request.

== Installation ==

1. Install either via the WordPress.org plugin directory, or by uploading the files to your server.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Once the plugin is activated you will see 'Show Hooks' in the Admin Bar at the top of your site or admin pages.
1. Clicking the 'Show Hooks' button will start showing you all the action hooks in the page you're on.
1. You can continue to navigate around until you've found the action hook you're looking for, then switch off by clicking 'Hide Hooks'.
1. Simple but effective. Enjoy!

== Screenshots ==

1. Here you can see how you can quickly find any action hook by using Show Hooks while you're on any WordPress wp-admin page.
1. Here you can see Show Hooks doing it's thing on any page of your website.

== Changelog ==

= 0.1.6 =
* New - Show/Hide link
* Fix - Style issues

= 0.1.5 =
* Check - WordPess 5.8 compatibility

= 0.1.4 =
* Check - WordPess 5.7 compatibility

= 0.1.3 =
* Check - WordPess 5.6 compatibility
* Remove - Show Showing Hooks notification bar from top-right corner

= 0.1.2 =
* Add - Hooks linked to WordPress Codex

= 0.1.1 =
* Improve - Visual element mapping on page

= 0.1.0 =
* Beta release.
