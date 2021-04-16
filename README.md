# Overdose Core module

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

## Functionality

### Check/Money Order allowed IPs
In order to use CMO in test purposes when it's disabled

**Usage**  
App your IP on production and "Check money order" will be available for you on. For testing orders.


### Content Security Policy Management
**Purpose: To cover W3C CSP recommendation**  
In order to add some sources to Content-Security-Policy / Content-Security-Policy-Report-Only header:

- `Stores > Settings > Configuration > Security > Content Security Policy > Custom CSP` section.
- `Source Url` field : to add URL, comma separated if same directives will be using for few sources.
- `Directives` field : multiselect for pick up proper restriction directives per URL

## Configurations

- `payment/checkmo/allowed_ips`: Appears if CMO status NO. Listed client IPs would see CMO on checkout. Configuration -> Sales -> Payment Methods -> Check / Money Order section -> 'Enable payment for IPs' field.

## Change log

- v1.2.4: Check / Money Order - support Magento Cloud