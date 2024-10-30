=== iCafe VPP Manager ===
Contributors: chrisdnilsson@gmail.com
Tags: VPP, Volume Purchase Program, Apple, Apps, Voucher, Vouchers, itunes, app store, icafe
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

iCafe VPP Manager automates many of the tasks associated with managing Apple's Volume Purchase Program.

== Description ==

iCafe VPP Manager takes much of the labor out of managing Apple's Volume Purchase Program.


Apple’s VPP process allows schools and businesses to purchase multiple copies of apps at a discount (and tax exempt for schools). Without VPP, users can only pay for the first copy of an app. 
 
 
[Learn more about VPP here] [vpp]


The problem is that Apple’s solution to volume purchases is difficult to manage for large enterprises. Apple allows only one Program Manager account used to create individual Program Facilitator Accounts. These Facilitator accounts can redeem VPP Vouchers and purchase app redemption codes but can’t actually install any apps!
Redemption codes can then be distributed to end users who redeem the codes to install the app. At this point the app becomes the property of the owner of the iTunes account used to redeem the code.

Confused yet?!?!


**iCafe VPP Manager is designed to solve many of the problems with Apple’s VPP Program:**

* Provide a trackable process for creating accounts
* Track all vouchers and remaining balances
* Allows end users to request app purchases
* Track all requests
* Stores and distributes all redemption codes
* Reduce the program demands on any one person


Providing a single platform for all your VPP transactions is only part of the solution offered by iCafe VPP Manager.


App ownership is also a struggle of many organizations. Many simply give up and allow users to redeem codes under personal iTunes accounts. In some cases this is desirable, but many organizations cannot afford to give away thousands of apps per year. Apple’s VPP terms allow an organization to maintain ownership of apps provided the redemption codes are redeemed under organization-owned iTunes accounts. While this provides a legal means for maintaining ownership, large organizations will find it difficult to manage.

Apple gives two recommendations; create an iTunes account for every user or group of users using a generic organizational email account (MHS_Science@schooldistrict.edu) or using a single iTunes account for all apps (organization_itunes@organization.com).

The first option requires upkeep of many accounts by one or a few people while the second poses the security concern of sharing the master account credentials among many staff members.


**iCafe VPP Manager solves this issue by creating a secure portal for authenticated staff to retrieve app redemption codes and the current enterprise iTunes credentials.**  
It will also allow you to setup an automated password change schedule. Most organizations change their master iTunes account every four to six hours minimizing the accounts exposer to former employs.


The goal is to take full advantage of the wonderful VPP program while automating much of the process.

Find me on Twitter @chrisnilsson https://twitter.com/ChrisNilsson


[A video demo of what end users experience can be found here] [demo]

[A video explanation of the iCafe Approach to iOS App Management can be found here] [icafevpp]

[vpp]: http://www.apple.com/education/volume-purchase-program/ "Apple VPP Site"
[demo]: http://youtu.be/Po_2URco62M "End User Work Flow"
[icafevpp]: http://youtu.be/DT2EgJZJ864 "iCafe Approach"

== Installation ==

1. Upload `iCafe VPP Manager` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Watch the training videos located in the iCafe VPP Plugin main screen
1. Configure the plugin settings according to the videos and your needs
1. Create a page with the [icafe_VPP] shortcode

== Screenshots ==

1. Track all app purchases and redemption codes
2. Track all Program Facilitator accounts and balances
3. Quick overview of the new Program Administrator layer
4. Simple automated Emails drive workflow
5. Simple automated Emails drive workflow
6. Simple automated Emails drive workflow
7. End users follow a simple set of steps to purchase apps
8. Simple forms for end users
9. Simple forms for end users
10. Simple app code redemption instructions with optional rotating enterprise iTunes account password
11. Automated Password Change Utility and Account Creation for Windows PC's


== Frequently asked questions ==

= How do I get started? =

Activate the plugin and watch the videos located on the plugin's main page.


= Can I watch the videos without installing the plugin? =

Sure, you can visit http://chrisnilsson.com/vpp to view the videos before installing the plugin.


= Does the enterprise password comply with Apple's Terms of Use? =

Yes, Apple allows organizations to maintain the ownership of apps purchased under VPP provide the codes are redeemed under an enterprise owned account. Normally, this is accomplished with the Apple Configurator utility. This is a good solution if you have the ability to physically sync every iOS device with a Mac computer every time an app code needs to be redeemed. The other method is to allow end users to redeem codes under the enterprise account. This involves sharing the enterprise credentials. For large organizations, this may not be a desirable approach. The iCafe VPP Manager allows you to share the enterprise credentials securely with staff members and provides an automated method for changing the password on a schedule


= How do I signup for VPP = 

Please visit the Apple VPP site http://www.apple.com/education/volume-purchase-program/


= Does this plugin allow me to reuse codes? =

No, codes can only be physically redeemed once. But, because they were redeemed under a single account you can reinstall from the purchased apps section of iTunes in the event a device is reset or an app needs to be transferred. As an organization, you are responsible for ensuring that users do not install more copies than they purchased. Apple has "Terms of Use" but does not enforce compliance at a technical level. This plugin takes the same approach.

= How do I make sure only staff have access to app requests or the enterprise password? =

The front end of the iCafe VPP Manager is only available for WordPress users who have subscriber rights or higher.


= What other roles do users need? =

Your Program Administrators will need to be contributors or higher.


= How do I get my entire staff into WordPress as subscribers? =

While we can't make specific recommendations for your environment, we have been successful both with a custom utility to directly upload users into the WordPress Database as well as a direct integration with our Active Directory via a WordPress LDAP plugin (AuthLDAP)


= What do you know about VPP and iOS =

I currently manage over 10,000 iOS devices for a 30,000 student K-12 district. My staff of 10 instructional technology specialists have deployed over $10,000 worth of apps using the iCafe VPP Manager. You can learn more about us from our technology integration site http://icafe.lcisd.org. 


= I'm so confused...can you help us? =

Apple VPP is confusing and managing iOS deployments is worse. While this plugin seeks to simplify the process for end users, the Program Manager must set it up correctly. Reach out to me on twitter @chrisnilsson or via the plugin support page for simple questions about configuration. For help designing a massive iOS deployment including Apps and Mobile Device Management please email me at chrisdnilsson@gmail.com for consulting rates.

= This is close to what we need but I wish it would... =

Please reach out to me on Twitter @chrisnilsson with suggestions. I'm always making improvements!



== Changelog ==

= 1.1 =
* Fixed a compatibility issue with the automated utilities on some 64bit Windows systems. Please redownload the automated utility pack from within the plugin.

= 1.0 =
* Initial Stable Public Release

= 0.2 =
* Bug fixes

= 0.1 =
* Initial Beta Public Release

== Upgrade Notice ==

= 1.1 =
* Fixed a compatibility issue with the automated utilities on some 64bit Windows systems. Please redownload the automated utility pack from within the plugin.

= 1.0 =
* Initial Stable Public Release

= 0.2 =
* Bug fixes

= 0.1 =
* Initial Beta Public Release