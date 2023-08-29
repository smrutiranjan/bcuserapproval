=== Bicommerce user approval plugin=========
Contributors: SMRUTIRNAJN MISHRA,MIKE PEACE 
Requires at least: 1.0
Tested up to: 6.2
Stable tag: 1.0
Author URI:https://github.com/smrutiranjan/
License: GPLv3 or later

== Description ==
BCuserapproval plugin helps clients to big commerce users once it is approved from the wp site so that you will avoid spam users not directly entered into the big commerce site. 
Process: Create Typeform register form like first name, last name, email, phone, invitation code, etc. then add web hook user like said in the setting section of this plugin. then installed this plugin and activated it.
Once the user fills out the form, it will create a bcusers at the wp site and you need to set big commerce API keys to connect with it. se the API keys in the settings section of this plugin. then once the user's status mark approved, it will be auto-created as a bigcommerce user at the BC site.

TYPEFORM WebHook Url
https://xxxxxx.com/wp-content/plugins/bcuserapproval/webhook.php

In case curl is enabled at php but not work instead of terminal check /etc/SELINUX/conf to modify to SELINUX=disabled, it will work.
