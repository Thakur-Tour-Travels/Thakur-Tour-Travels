=== Simontaxi - Vehicle Booking  ===
Author URI: https://digisamaritan.com/
Plugin URI: http://simontaxi.wptaxitheme.com/
Tags: airport booking, cab wordpress, hourly rental, paypal booking, payu booking, stripe booking, rental theme, responsive theme, taxi booking, vehicle booking
Requires at least: 4.4
Tested up to: 4.9
Stable Tag: 2.0.8
License: GNU Version 2 or Any Later Version

The easiest way to book a vehicle with WordPress.

== Description ==

Simontaxi - Vehicle Booking is a complete booking solution for booking a vehicle on WordPress. Learn more at [simontaxi.wptaxitheme.com] (https://simontaxi.wptaxitheme.com/).

= Get Booked effortlessly =

Whether it is Car, Ambulance, Bicycle, Motor Car, Tractor, Sports Car, Motor Scooter, Motor Cycle, Mountain Bike, Lorry, Crane, Buggy, Bulldozer, Van, Taxi, Bus, Auto, Auto Rikshaw, Truck, Diesel Truck, Delivery Truck, Boat, Sailboat, Fishing Boat, Cargo Ship, Ship, Hot-air Balloon etc. Simontaxi - Vehicle Booking provides a complete system for effortlessly book your vehicle.

= Localized for your language =

Simontaxi - Vehicle Booking is ready to translate plugin.

= Payment gateways for everyone =

The internet has made it possible for anyone to see their products to a world-wide audience. No matter where you live, Simontaxi - Vehicle Booking can work for you. We offer integrations for the most common merchant processors and, through 3rd party extensions, support for many, many more as well.

Payment gateways supported in the core, free plugin:

* PayPal Standard
* Payu Payments
* Byhand (Offline)

Payment gateways supported through free or premium extension:

* Stripe

We are coming with many more extensions on demand.

= Built with developers in mind =

Extensible, adaptable -- Simontaxi - Vehicle Booking is created with developers in mind, which is extendable with their own actions.

== Installation ==

1. Activate the plugin
2. Go to Vehicles > Settings and configure the options
3. Create Vehicles from the Vehicles > Add New page
4. For detailed setup instructions, visit the official [Documentation](https://simontaxi.wptaxitheme.com/documentation/) page.

== Frequently Asked Questions ==

= Where can I find complete documentation? =

Full docs with screen shots can be found at [https://simontaxi.wptaxitheme.com/documentation/)

= Where can I ask for help? =

You can submit a support ticket or pre-sale question from our [support page](https://themeforest.net/item/simontaxi-taxi-booking-wordpress-theme/19978212/comments) at anytime.

If you already buy the plugin you can rise support ticket on https://support.wptaxitheme.com/

= Is an SSL certificate required? =

Simontaxi - Vehicle Booking can function without one just fine, making it easy to set up in a testing or development environment.  We still strongly recommend you have an SSL certificate for your production web site, both for security and for the peace of mind of your customers. 

= What themes work with Simontaxi - Vehicle Booking? =

Any properly written theme will work with Simontaxi - Vehicle Booking with minor CSS modifications.


= Is there a sample import file I can use to setup my site? =

Yes! Simply 'One Click Demo' Plugin, then navigate to 'Appearance > Import Demo Data' and click on 'Simontaxi Demo Data + Vehicles'. This will create several sample vehicles and plugin pages for you.


= My bookings are being marked as "new" =

There are several reasons this happens. Please see the settings (Booking Status when payment success)  [here](https://simontaxi.wptaxitheme.com/settings-payment-gateways/).

= Getting a 404 error? =

To get rid of the 404 error when viewing a vehicle details, you need to resave your permalink structure. Go to Settings > Permalinks and click "Save Changes".

= How do I show the user’s booking history? =

Users can login to the system and can find their booking history, profile settings, support tickets.

= How do I display my vehicle? =

Simply Create your vehicle https://simontaxi.wptaxitheme.com/vehicle-add/ and it will show front end for booking.


= Can customers book a vehicle without using PayPal? =

Yes. Simontaxi - Vehicle Booking also includes default support for OFFLINE if admin enable it.


== Changelog ==
= 2.0.9 - Sept 30, 2018 =
* Feature: Minimum fare for vehicle
* Feature: Option to restore default email/SMS templates, if they deleted in any way.
* Feature: Neatly prepared invoice header (Settings -> Billing -> User for invoice header). Admin can upload his company logo so that invoice prepared automatically
* Feature: To download PDF invoice for booking (Settings -> Billing -> Show Invoice to User?)
* Feature: To display arrival time (Settings -> Optional Fields -> Display arrival on)
* Feature: To display vehicle how far from pickup location
* Feature: Added new Email settings for 'start ride' and 'ride completed'
* Feature: Added an option to book a vehicle in a different time slots in same day
* Feature: Added an option to send different email templates for different users, booking type and particular vehicle. suppose if template is "booking-confirmed" if you want to send different email template to driver rather than regular template, 
	* if it is (Settings -> Email Settings -> Mail Content (Bookings Confirm) ) "file"
	then you need to create a file with name "wp-content/plugins/vehicle-booking/templates/booking-confirmed-driver.php"
	* if it is (Settings -> Email Settings -> Mail Content (Bookings Confirm) ) "post"
	then you need to create a post with name "booking-confirmed-driver"
	* Hierarchy (Post)
	* 1. template-user_type-booking_type-vehicle_id (Eg: booking-success-driver-p2p-968)
	* 2. template-user_type-booking_type (Eg: booking-success-driver-p2p)
	* 3. template-user_type (Eg: booking-success-driver)
	* 4. template-booking_type (Eg: booking-success-p2p)
	* 5. template (Eg: booking-success)
	
	* Hierarchy (File) - "wp-content/plugins/vehicle-booking/templates"
	* 1. template-user_type-booking_type-vehicle_id (Eg: booking-confirmed-driver-p2p-365.php)
	* 2. template-user_type-booking_type (Eg: booking-confirmed-driver-p2p.php)
	* 3. template-user_type (Eg: booking-confirmed-driver.php)
	* 4. template-booking_type (Eg: booking-confirmed-p2p)
	* 5. template (Eg: booking-confirmed.php)
	*
* Feature: Added an option to send different SMS templates for different users. suppose if template is "sms-booking-confirmed" if you want to send different SMS template to driver rather than regular template, then you need to create a file/post with name "sms-booking-confirmed-driver.php"
* Feature: Admin can allow users to select more than one country.
* Feature: Option to select particular package (By using $_GET['package'] variable. Here 'package' is package slug)
* Feature: Option to select particular airport (By using $_GET['airport'] variable. Here 'airport' is airport ID)
* Tweak: Fare calculation criteria can be changed at vehicle level so the global value will override.
* Feature: Short code to create multiple registraiton pages for different roles (Eg: Driver, Customer etc.)
* Fix: Few minor issues

= 2.0.8 - June 1, 2018 =
* Feature: Registration activation with email
* Feature: Sending promotion code in registraiton email to encourage users to book vehicle (For this admin need to create a coupon with name 'promotion')
* Feature: Dashboard widget for quick summary
* Feature: Template customization for developers
* Feature: Option to land in particular booking type (By using $_GET['booking_type'] variable)
* Feature: Admin can create separate booking page as per his needs with different booking types by using short codes
* Feature: Option to choose the position of sidebar at front end (Left OR Right)
* Feature: Option to procede booking from a vehicle to reduce booking steps (By using $_GET['selected_vehicle'] variable)
* Feature: Added time restriction optional feature for admin. Eg: If some one book particular vehicle on particular time, Let us say vehicle1 is booked for 12/04/2018 at 12pm, Other customer should not be able to book same vehicle at same time to avoid clashes.
* Feature: Added number of seats restriction on individual vehicle. Eg: If a customer booking a vehicle for 5 person and the selected vehicle contains 4 seats only, we need to restrict based on admin settings
* Feature: Option to choose time display format (Standard - 12 Hrs Format OR Military - 24 Hrs Format)
* Feature: Option to prefix or postfix booking reference
* Feature: Option to select the vehicle to book for different types of bookings. Admin can specify the particular vehicle available for booking type.
* Feature: Option to enable Garage to Garage Fare Calculation
* Feature: Option to choose between Email OR SMS template between post OR File to support multi lingual Email OR SMS Templates
* Feature: special fare is for selected vehicles only, not for all. People are opting to charge extra fare for selected vehicles only
* Feature: Option to set Minimum Distance for  a vehicle to handle booking. Eg: If the booking is 400KM away from garage address (base address) then admin can set wheather the vehicle is available for booking
* Feature: to display the how far the vehicle is available from user pickup location so that user can know the info.
* Fix: Settings page freeze in admin
* Fix: Few minor issues

= 2.0.7 - January 23, 2018 =
* Feature: To send user registration email
* Feature: Send additional information in booking success email
* Feature: Option for admin to alert the user for each change in admin

= 2.0.6 - December 30, 2017 =
* Feature: Option to add additional Luggage (ex: 2 Large+1 Small)
* Feature: Minimum distance applicable
* Feature: Quick Settings Link on Plugin Page
* Feature: Option to get Flight Arrival time from customer
* Feature: Option to work the system based on number of persons
* Feature: Option to edit payment details in admin
* Feature: Option to edit booking details in admin
* Tweak: Predefined Charges issue fixed. If customer travelled more than what admin specifies then we are calculating the additional price based on unit price
* Tweak: Option to enter distance in float numbers
* Tweak: No. of vehicles in a category in featured vehicles
* Tweak: mobile field required validations not showing (*)
* Fix: Sidebar options Error
* Fix: Wrong calculation if the system is in 'miles'
* Fix: Luggage Symbol Issue(Symbol Not Showing on Front End, Showing Text (Small, Large, Kilogramme))
* Fix: If disable the P2P transfer, it is Still showing on the front end. It is Not going away.
* Fix: String Translation issues in Booking Form (Tabs & breadcrumbs)
* Fix: Fixed float amount issue
* Fix: Few minor issues

= 2.0.5 - October 26, 2017 =
* Feature: Sidebar options Enable OR Disable
* Fix: Local variable issue fixed

= 2.0.4 - October 21, 2017 =
* Feature: Option to change admin logo
* Feature: Option not to display fare to user in step2
* Feature: Option Enable OR disable Default Login Menu Item 
* Feature: Option To change Payment Success OR Failed Messages from admin
* Feature: Bank Transfer Payment
* Tweak: Added missing page for vehicle booking
* Tweak: Visual Composer components organized display
* Fix: Warning in admin settings Page

= 2.0.3 - September 26, 2017 =
* Fix: Warning in admin settings Page

= 2.0.2 - September 22, 2017 =
* Fix: Vehicles -> Settings -> Currency -> The settings are not saved correctly...always jump back to left currency position...
* Tweak: Vehicles -> Settings -> Currency -> add an option for how many decimal places...now there are 4 decimal places here in Europe standard is 2 decimal places and also an option if there is comma or dot as separator...
* Tweak: Improved additional charges
* Tweak: Change the way of displaying currency in admin
* Feature: Vehicles -> Settings -> Optional Fields -> Booking Step3 -> add an option for field "company name" (no display;required;optional)
* Feature: Vehicles -> Settings -> Billing -> add an option to hide or show "invoice" column at the "Booking History" on frontend
* Feature: Google maps to select Region
* Tweak: Change the system to use for different vehicle transport like Airport, Railway Station, Bus Station etc.
* Feature: Improved Drivers module
* Feature: Option to change main loader and Ajax loader

= 2.0.1 - September 08, 2017 =
* Tweak: Terms and Conditions Check Box issue(Its showing as Question Mark(?).
* Tweak: Simontaxi Loading and Optimization.
* Fix: When you select two way option in the 2nd tab which is selecting the vehicle in which the cost shows only one-way option, where as it should show two-way option which is bug.
* Fix: In Manage Bookings Message to customer (Text is not going with mail).
* Fix: PayU Amount issues fixed (When user trying to pay amount greater than 50000 it is not taking so put restriction that if amount greater than 50000 we are not displaying PayU in Payment gateways!).

= 2.0.0 - August 08, 2017 =

* Feature: Support for external payment gateways - Stripe Payment Gateway,PayPal and PayuIndia.
* Feature: Catch the powerful Sessions.
* Feature: Added the support for pickup and drop-off locations Google or predefined.
* Feature: Added new feature to restrict the region
* Feature: Extensions for the Vehicle Booking Plugin (Vehicles->Manage Extensions) (Stripe gateway as an extension to Plugin, Drivers Plugin, Auto updater Plugin).
* Feature: Region Restriction.
* Feature: Number of vehicles restriction.
* Feature: Pickup and Drop-off Restriction.
* Feature: Permissions extended for each role.
* Tweak: Implementation of actions for developers in mind.
* Tweak: Provision for future expansion

= 1.0.0 - July 24, 2017 =

* Simontaxi - Vehicle Booking is born. This release was only available via download at https://themeforest.net/item/simontaxi-taxi-booking-wordpress-theme/19978212. We launched on themeforest.