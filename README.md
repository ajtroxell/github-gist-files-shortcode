#GitHub Gist Files Shortcode

A Wordpress plugin which will insert a specific file from a Gist using the shortcode [gist id="xxxxxx" file="name"].

##Usage

Three ways are provided to insert the shortcode. The value "xxxxxx" represents your Gist ID, and "name" is the filename of the file within the Gist.

*	Insert [gist id="xxxxxx" file="name"] manually.
*	By using the plain text editor shortcode button.
*	By pressing ctrl+alt+g.

You can place these shortcodes in pages, posts or any custom content.

##Installation

1.	Unzip gist-file-shortcode.zip and upload gist-file-shortcode folder to wp-content/plugins.
2.	On Wordpress admin panel, activate plugin.

##Screenshots
![Text Editor Button](http://ajtroxell.com/wp-content/uploads/2013/06/gist-file-shortcode-screenshot-1.jpg "Text Editor Button")

![Inserted Shortcode](http://ajtroxell.com/wp-content/uploads/2013/06/gist-file-shortcode-screenshot-2.jpg "Inserted Shortcode")

![Inserted Gist](http://ajtroxell.com/wp-content/uploads/2013/06/gist-file-shortcode-screenshot-3.jpg "Inserted Gist")

##Changelog
###2.0.1
*	Shortcode missing quotation mark from previous update.

###2.0
*	Using the shortcode button now triggers user prompts to enter Gist ID and filename instead of relying on manual insertion. Making the plugin more user friendly.

###1.1.1
*	Added automatic plugin updating for non-wordpress.org downloads.