Public
======

1. For security purposes, every file possible is stored outside of the public web area. When
    you configure your webserver to access the site, the disk location is Disk location
    is [/from/the/root]/Sites/Public/Sites/N


Multisite
=========

1. Site 1 is the master.
    - extensions are installed in Site 1
    - extensions are authorised for use by other Sites in the Extensions Manager.
    - once authorised, other sites can configure and use the extension normally.

2. Install new site:
    - creates a new Site/N folder where N is the next numeric value by copying the Site/Default folder
    - add Site entry in Site/Sites.xml
    - navigate browser to the URL
    - install kicks off when the folder is there, but there is no Sites/N/Dataobject/Database.xml file
    - installer works normally, creating the database and Database.xml file

3. Media and files:
    - Site-specific files are stored within the Site/N/ subfolders.
    - Site/Default is copied to each new Site. Additional files and folders can be added, as desired.
    - Files and folders to be shared between sites can be stored in the Site/Media folder.

4. Site-specific Themes:
    - Themes can be shared between sites.
    - To reserve a Theme for use by one site, install in Site 1 and only enable the appropriate site to use.

5. Localhost Development:
    - Use Apache's VirtualHost feature to create unique non-routable host names for each site needed.
    - Disk location is [/from/the/root]/Sites/Public/Sites/N

6. Use htaccess to lock people out of other sites, if necessary.

