=== Unofficial CForms II table display ===
Contributors: mpntod
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9XCNM4QSVHYT8
Tags: cformsii,form,table,count,shortcode,cforms,cforms2
Requires at least: 2.5
Tested up to: 4.9
Stable tag: 1.1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Replaces a shortcode such as [cformstable form='nameofyourform'] with a table or a count of data collected via the CForms II plugin

== Description ==
Replaces a shortcode such as `[cformstable form='nameofyourform']` with a table of data or a count of information collected via the excellent [CForms II](https://wordpress.org/plugins/cforms2/).

Examples would be:

* `[cformstable form='nameofyourform']` to display how many entries have been collected by your form
* `[cformstable form='nameofyourform' display='table' vars='Name,Date,Comment']` to display a table of entries collected by your form

Possible variables for your shortcode include:

= General Variables =
* `display = 'number'` or `'table'` - (the default is `'number'`)
* `form = 'the name of your form'` - if you don't know the names of possible forms, just use `[cformstable]` to get a list of options
* `from =` DATETIME string (format: `Y-m-d H:i:s`). Date & time defining the start of a target period, e.g. `2008-09-17 15:00:00` *(optional)*
* `to` =` DATETIME string (format: `Y-m-d H:i:s`). Date & time defining the end of a target period, e.g. `2014-09-17 15:00:00` *(optional)*
* `limit =` 'the maximum lines to show in the table or count' *(optional)*


= Table Variables =

*Either*

* `vars =` list of variable names to use as column headers separated by commas e.g. `'Name,Date,Comment'`. To change the way the name is displayed, add your revised text after `|`, e.g. `vars='Name|Your Name,Date,Comment'`

*Or*

* `cols =` list of variable numbers to use as column headers separated by commas e.g. `'1,2,3'`. You can get variable numbers by posting `[cformstable display='table' form='Name of your form']`.  As with the vars setting, to change the way the name is displayed, add your revised text after `|`, e.g. `cols='1|Your Name,2,3'`.

*Optional Variables*

* `limit = 'the maximum lines to show in the table'` *(optional)*
* `sortdir = 'asc'` or `'desc'` *(optional)*
* `sort  = 'form', 'id', 'date', 'ip'` or `'email'` or any other form input field, e.g. `'Your Name'` *(optional)*


= Count Variables =
* `deduped = TRUE` or `FALSE` - remove duplicate IP/email addresses from count *(optional)*
* `adjust = 'n'` where `n` is a numerical value (+10, -20 etc.) to adjust the count displayed up or down *(optional)*

== Installation ==
1. Upload `cformstable.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place a shortcode such `[cformstable display='table']` in your post or page

== Frequently Asked Questions ==

= Does the plug-in work without CForms II installed? =

No. You need to [install it](https://wordpress.org/plugins/cforms2).

For versions of CForms II after 15.0, you may also need to install [Old Tracking DB for cformsII](https://wordpress.org/plugins/cforms2-old-tracking-db/)

== Upgrade notice ==
= 1.1.3 =
* Adds variables that weren't in the original version of the form to the `cols` list

== Changelog ==
= 1.1.3 =
* Solves a potential problem where variable numbers would depend on sort values

= 1.1.2 =
* Resolves an empty table cell problem.

= 1.1.1 =
* Resolves a 'hidden column' problem with variables added to the form after the first question was answered.

= 1.1.0 =
* Adds variables that weren't in the original version of the form to the `cols` list

= 1.0.4 =
* Fixing italicisation problem

= 1.0.3 =
* Further fix to sort setting

= 1.0.2 =
* Fixed sort setting

= 1.0.1 =
* Fixing sort problem with `cols` variable.
* Fixing slashes problem with column names

= 1.0 =
* Addition of `cols` variable to shortcode to allow people to select variables for display by number e.g. `cols='1,2,3,4'`

= 0.25 =
Fixes bug with permissions.

= 0.24 =
Add two new variables to cformstable: `adjust` and `deduped`

= 0.23 =
Fixed bug where selected table is empty.

= 0.221 =
Fully fixed bug where count is zero

= 0.22 =
Fixed bug where count is zero

= 0.21 =
Partial fix of bug where count is zero

= 0.2 =
First published version

== Upgrade Notice ==
= 0.25 =
Fixes bug with permissions

== Screenshots ==
It's a pure text replacement plugin, so the screenshots will have to wait I'm afraid