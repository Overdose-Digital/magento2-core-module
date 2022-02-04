# Overdose Core module M2
Module "for other OD modules".  
Creates config tab and contain couple geatures.

## Install instructions:
  - If NOT packagist: `composer config repositories.overdose/module-core-repo vcs git@bitbucket.org:overdosedigital/modules-core.git`
  - Always: `composer require overdose/module-core:1.2.7` (DISCLAYMER: check version before run this command)

## Functionality

Create tab `od_core` in Configurations. If your module has configurations, than add core module to `require` section and put configurations in `od_core` tab.
`overdose/module-core`  

Example:
```
# system.xml of some module
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Config:etc/system_file.xsd">
    <system>
        <section id="od_blog" translate="label" type="text" sortOrder="1" showInDefault="1" showInWebsite="1" showInStore="1">
            <label>Blog</label>
            <tab>od_core</tab> <!-- Bingo -->
            <group id="general" translate="label" type="text" sortOrder="10" showInDefault="1" showInWebsite="1" showInStore="1">
                <label>Additional Blog configurations</label>
                <field id="cms_content" translate="label comment" type="text" sortOrder="10" showInDefault="1" showInWebsite="1" showInStore="1">
                    <label>Blog landing cms block content</label>
                </field>
            </group>
        </section>
    </system>
</config>
```
![Example](https://i.imgur.com/WTmJE00.png "Logo Title Text 1")

### Check/Money Order allowed IPs
In order to use CMO in test purposes when it's disabled

**Usage**  
App your IP on production and "Check money order" will be available for you on. For testing orders.


### Admin Notification
Sometimes tech leads and client need to be updated with critical processes failures.  
This function allows implementing email notifications send in any part of custom logic.

Admin configuration: Stores > Configuration > Overdose > Admin Notification > Email Notification
![Admin Notification](https://i.imgur.com/pYsNe4O.png "Admin notification configuration")
To use module functionality need to add `Overdose\Core\Model\Email\Sender` class and execute `send()` method.  
Example:

    $sender->send([
        'errorSubject' => 'Custom product import failure.',
        'errorMessage' => 'Error: [message]'
    ]);

The default email template is `app/code/Overdose/Core/view/frontend/email/admin_notification.html`, it can be modified if needed.

### Content Security Policy Management
**Purpose: To cover W3C CSP recommendation**  
In order to add some sources to Content-Security-Policy / Content-Security-Policy-Report-Only header:

- `Stores > Settings > Configuration > Security > Content Security Policy > Custom CSP` section.
- `Source Url` field : to add URL, comma separated or from new line if same directives will be using for few sources.
- `Directives` field : multiselect for pick up proper restriction directives per URL

## Configurations
- `od_general_config/api_keys/google_maps`. Google Maps API key for global usage.
- `admin_notification/email/identity`. Admin Notification sender email.
- `admin_notification/email/template`. Admin Notification email template.
- `admin_notification/email/receiver`. Admin Notification Receiver email.
- `payment/checkmo/allowed_ips`: Appears if CMO status NO. Listed client IPs would see CMO on checkout. Configuration -> Sales -> Payment Methods -> Check / Money Order section -> 'Enable payment for IPs' field. Default: NZ VPN.
- `od_csp/custom_policy/rules`. Custom rules for Magento_CSP.
- `environment/general/header_title_prefix`. Custom prefix for Admin Pages TItles.
- `environment/general/header_background`. Custom css background for Admin Header.
- `environment/general/header_text_colour`. Custom css text colour for Admin Header.

