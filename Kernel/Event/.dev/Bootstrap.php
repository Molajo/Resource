<?php
/**
 * Event
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */


/**
 * $base = substr(__DIR__, 0, strlen(__DIR__) - 5);
 * $folder = $base . '/' . 'Plugins' . '/*';
 * foreach (glob($folder) as $filename) {
 * echo 'Molajo\\Event\\Plugins\\' . basename($filename) . '\\' . basename($filename) . 'Plugin'
 * . ' => ' . $filename . '/' . basename($filename) . '/' . basename($filename) . '.php' . '<br />';
 * }
 */

if (substr($_SERVER['DOCUMENT_ROOT'], - 1) == '/') {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT']);
} else {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT'] . '/');
}

$base = substr(__DIR__, 0, strlen(__DIR__) - 5);
define('BASE_FOLDER', $base);

$classMap = array(
    'Molajo\\Event\\Adapter'                                                     => BASE_FOLDER . '/Adapter.php',
    'Molajo\\Event\\Api\\AuthenticateEventInterface'                            => BASE_FOLDER . '/Api/AuthenticateEventInterface.php',
    'Molajo\\Event\\Api\\CreatePluginInterface'                                  => BASE_FOLDER . '/Api/CreatePluginInterface.php',
    'Molajo\\Event\\Api\\DeletePluginInterface'                                  => BASE_FOLDER . '/Api/DeletePluginInterface.php',
    'Molajo\\Event\\Api\\DisplayPluginInterface'                                 => BASE_FOLDER . '/Api/DisplayPluginInterface.php',
    'Molajo\\Event\\Api\\EventInterface'                                         => BASE_FOLDER . '/Api/EventInterface.php',
    'Molajo\\Event\\Api\\ExceptionInterface'                                     => BASE_FOLDER . '/Api/ExceptionInterface.php',
    'Molajo\\Event\\Api\\EventDataInterface'                                     => BASE_FOLDER . '/Api/EventDataInterface.php',
    'Molajo\\Event\\Api\\PluginInterface'                                        => BASE_FOLDER . '/Api/PluginInterface.php',
    'Molajo\\Event\\Api\\ReadPluginInterface'                                    => BASE_FOLDER . '/Api/ReadPluginInterface.php',
    'Molajo\\Event\\Api\\UpdatePluginInterface'                                  => BASE_FOLDER . '/Api/UpdatePluginInterface.php',
    'Molajo\\Event\\Exception\\EventException'                                   => BASE_FOLDER . '/Exception/EventException.php',
    'Molajo\\Event\\Exception\\PluginDataException'                              => BASE_FOLDER . '/Exception/PluginDataException.php',
    'Molajo\\Event\\Exception\\PluginException'                                  => BASE_FOLDER . '/Exception/PluginException.php',
    'Molajo\\Event\\Plugins\\Ajax\\AjaxPlugin'                                   => BASE_FOLDER . '/Plugins/Ajax/Ajax/Ajax.php',
    'Molajo\\Event\\Plugins\\Alias\\AliasPlugin'                                 => BASE_FOLDER . '/Plugins/Alias/Alias/Alias.php',
    'Molajo\\Event\\Plugins\\Application\\ApplicationPlugin'                     => BASE_FOLDER . '/Plugins/Application/Application/Application.php',
    'Molajo\\Event\\Plugins\\Author\\AuthorPlugin'                               => BASE_FOLDER . '/Plugins/Author/Author/Author.php',
    'Molajo\\Event\\Plugins\\Blockquote\\BlockquotePlugin'                       => BASE_FOLDER . '/Plugins/Blockquote/Blockquote/Blockquote.php',
    'Molajo\\Event\\Plugins\\Catalog\\CatalogPlugin'                             => BASE_FOLDER . '/Plugins/Catalog/Catalog/Catalog.php',
    'Molajo\\Event\\Plugins\\Checkin\\CheckinPlugin'                             => BASE_FOLDER . '/Plugins/Checkin/Checkin/Checkin.php',
    'Molajo\\Event\\Plugins\\Checkout\\CheckoutPlugin'                           => BASE_FOLDER . '/Plugins/Checkout/Checkout/Checkout.php',
    'Molajo\\Event\\Plugins\\Comment\\CommentPlugin'                             => BASE_FOLDER . '/Plugins/Comment/Comment/Comment.php',
    'Molajo\\Event\\Plugins\\Contentlist\\ContentlistPlugin'                     => BASE_FOLDER . '/Plugins/Contentlist/Contentlist/Contentlist.php',
    'Molajo\\Event\\Plugins\\Copyright\\CopyrightPlugin'                         => BASE_FOLDER . '/Plugins/Copyright/Copyright/Copyright.php',
    'Molajo\\Event\\Plugins\\Create\\CreatePlugin'                               => BASE_FOLDER . '/Plugins/Create/Create/Create.php',
    'Molajo\\Event\\Plugins\\Csrftoken\\CsrftokenPlugin'                         => BASE_FOLDER . '/Plugins/Csrftoken/Csrftoken/Csrftoken.php',
    'Molajo\\Event\\Plugins\\Cssclassandids\\CssclassandidsPlugin'               => BASE_FOLDER . '/Plugins/Cssclassandids/Cssclassandids/Cssclassandids.php',
    'Molajo\\Event\\Plugins\\Datalist\\DatalistPlugin'                           => BASE_FOLDER . '/Plugins/Datalist/Datalist/Datalist.php',
    'Molajo\\Event\\Plugins\\Dataobject\\DataobjectPlugin'                       => BASE_FOLDER . '/Plugins/Dataobject/Dataobject/Dataobject.php',
    'Molajo\\Event\\Plugins\\Dateformats\\DateformatsPlugin'                     => BASE_FOLDER . '/Plugins/Dateformats/Dateformats/Dateformats.php',
    'Molajo\\Event\\Plugins\\Defer\\DeferPlugin'                                 => BASE_FOLDER . '/Plugins/Defer/Defer/Defer.php',
    'Molajo\\Event\\Plugins\\Email\\EmailPlugin'                                 => BASE_FOLDER . '/Plugins/Email/Email/Email.php',
    'Molajo\\Event\\Plugins\\Event\\EventPlugin'                                 => BASE_FOLDER . '/Plugins/Event/Event/Event.php',
    'Molajo\\Event\\Plugins\\Extensioninstance\\ExtensioninstancePlugin'         => BASE_FOLDER . '/Plugins/Extensioninstance/Extensioninstance/Extensioninstance.php',
    'Molajo\\Event\\Plugins\\Featured\\FeaturedPlugin'                           => BASE_FOLDER . '/Plugins/Featured/Featured/Featured.php',
    'Molajo\\Event\\Plugins\\Feed\\FeedPlugin'                                   => BASE_FOLDER . '/Plugins/Feed/Feed/Feed.php',
    'Molajo\\Event\\Plugins\\Fields\\FieldsPlugin'                               => BASE_FOLDER . '/Plugins/Fields/Fields/Fields.php',
    'Molajo\\Event\\Plugins\\Formbegin\\FormbeginPlugin'                         => BASE_FOLDER . '/Plugins/Formbegin/Formbegin/Formbegin.php',
    'Molajo\\Event\\Plugins\\Formselectlist\\FormselectlistPlugin'               => BASE_FOLDER . '/Plugins/Formselectlist/Formselectlist/Formselectlist.php',
    'Molajo\\Event\\Plugins\\Fullname\\FullnamePlugin'                           => BASE_FOLDER . '/Plugins/Fullname/Fullname/Fullname.php',
    'Molajo\\Event\\Plugins\\Googleanalytics\\GoogleanalyticsPlugin'             => BASE_FOLDER . '/Plugins/Googleanalytics/Googleanalytics/Googleanalytics.php',
    'Molajo\\Event\\Plugins\\Gravatar\\GravatarPlugin'                           => BASE_FOLDER . '/Plugins/Gravatar/Gravatar/Gravatar.php',
    'Molajo\\Event\\Plugins\\Head\\HeadPlugin'                                   => BASE_FOLDER . '/Plugins/Head/Head/Head.php',
    'Molajo\\Event\\Plugins\\IFrame\\IFramePlugin'                               => BASE_FOLDER . '/Plugins/IFrame/IFrame/IFrame.php',
    'Molajo\\Event\\Plugins\\Images\\ImagesPlugin'                               => BASE_FOLDER . '/Plugins/Images/Images/Images.php',
    'Molajo\\Event\\Plugins\\Ipaddress\\IpaddressPlugin'                         => BASE_FOLDER . '/Plugins/Ipaddress/Ipaddress/Ipaddress.php',
    'Molajo\\Event\\Plugins\\Itemurl\\ItemurlPlugin'                             => BASE_FOLDER . '/Plugins/Itemurl/Itemurl/Itemurl.php',
    'Molajo\\Event\\Plugins\\Itemuserpermissions\\ItemuserpermissionsPlugin'     => BASE_FOLDER . '/Plugins/Itemuserpermissions/Itemuserpermissions/Itemuserpermissions.php',
    'Molajo\\Event\\Plugins\\Linebreaks\\LinebreaksPlugin'                       => BASE_FOLDER . '/Plugins/Linebreaks/Linebreaks/Linebreaks.php',
    'Molajo\\Event\\Plugins\\Login\\LoginPlugin'                                 => BASE_FOLDER . '/Plugins/Login/Login/Login.php',
    'Molajo\\Event\\Plugins\\Logout\\LogoutPlugin'                               => BASE_FOLDER . '/Plugins/Logout/Logout/Logout.php',
    'Molajo\\Event\\Plugins\\Menuitems\\MenuitemsPlugin'                         => BASE_FOLDER . '/Plugins/Menuitems/Menuitems/Menuitems.php',
    'Molajo\\Event\\Plugins\\Messages\\MessagesPlugin'                           => BASE_FOLDER . '/Plugins/Messages/Messages/Messages.php',
    'Molajo\\Event\\Plugins\\Mockdata\\MockdataPlugin'                           => BASE_FOLDER . '/Plugins/Mockdata/Mockdata/Mockdata.php',
    'Molajo\\Event\\Plugins\\Mockimage\\MockimagePlugin'                         => BASE_FOLDER . '/Plugins/Mockimage/Mockimage/Mockimage.php',
    'Molajo\\Event\\Plugins\\Ordering\\OrderingPlugin'                           => BASE_FOLDER . '/Plugins/Ordering/Ordering/Ordering.php',
    'Molajo\\Event\\Plugins\\Pagetypeapplication\\PagetypeapplicationPlugin'     => BASE_FOLDER . '/Plugins/Pagetypeapplication/Pagetypeapplication/Pagetypeapplication.php',
    'Molajo\\Event\\Plugins\\Pagetypeconfiguration\\PagetypeconfigurationPlugin' => BASE_FOLDER . '/Plugins/Pagetypeconfiguration/Pagetypeconfiguration/Pagetypeconfiguration.php',
    'Molajo\\Event\\Plugins\\Pagetypedashboard\\PagetypedashboardPlugin'         => BASE_FOLDER . '/Plugins/Pagetypedashboard/Pagetypedashboard/Pagetypedashboard.php',
    'Molajo\\Event\\Plugins\\Pagetypeedit\\PagetypeeditPlugin'                   => BASE_FOLDER . '/Plugins/Pagetypeedit/Pagetypeedit/Pagetypeedit.php',
    'Molajo\\Event\\Plugins\\Pagetypegrid\\PagetypegridPlugin'                   => BASE_FOLDER . '/Plugins/Pagetypegrid/Pagetypegrid/Pagetypegrid.php',
    'Molajo\\Event\\Plugins\\Pagetypeitem\\PagetypeitemPlugin'                   => BASE_FOLDER . '/Plugins/Pagetypeitem/Pagetypeitem/Pagetypeitem.php',
    'Molajo\\Event\\Plugins\\Pagetypelist\\PagetypelistPlugin'                   => BASE_FOLDER . '/Plugins/Pagetypelist/Pagetypelist/Pagetypelist.php',
    'Molajo\\Event\\Plugins\\Pagetypes\\PagetypesPlugin'                         => BASE_FOLDER . '/Plugins/Pagetypes/Pagetypes/Pagetypes.php',
    'Molajo\\Event\\Plugins\\Pagination\\PaginationPlugin'                       => BASE_FOLDER . '/Plugins/Pagination/Pagination/Pagination.php',
    'Molajo\\Event\\Plugins\\Paging\\PagingPlugin'                               => BASE_FOLDER . '/Plugins/Paging/Paging/Paging.php',
    'Molajo\\Event\\Plugins\\Password\\PasswordPlugin'                           => BASE_FOLDER . '/Plugins/Password/Password/Password.php',
    'Molajo\\Event\\Plugins\\Publishedstatus\\PublishedstatusPlugin'             => BASE_FOLDER . '/Plugins/Publishedstatus/Publishedstatus/Publishedstatus.php',
    'Molajo\\Event\\Plugins\\Readmore\\ReadmorePlugin'                           => BASE_FOLDER . '/Plugins/Readmore/Readmore/Readmore.php',
    'Molajo\\Event\\Plugins\\Referencedata\\ReferencedataPlugin'                 => BASE_FOLDER . '/Plugins/Referencedata/Referencedata/Referencedata.php',
    'Molajo\\Event\\Plugins\\Sites\\SitesPlugin'                                 => BASE_FOLDER . '/Plugins/Sites/Sites/Sites.php',
    'Molajo\\Event\\Plugins\\Smilies\\SmiliesPlugin'                             => BASE_FOLDER . '/Plugins/Smilies/Smilies/Smilies.php',
    'Molajo\\Event\\Plugins\\Snippet\\SnippetPlugin'                             => BASE_FOLDER . '/Plugins/Snippet/Snippet/Snippet.php',
    'Molajo\\Event\\Plugins\\Status\\StatusPlugin'                               => BASE_FOLDER . '/Plugins/Status/Status/Status.php',
    'Molajo\\Event\\Plugins\\Templatelist\\TemplatelistPlugin'                   => BASE_FOLDER . '/Plugins/Templatelist/Templatelist/Templatelist.php',
    'Molajo\\Event\\Plugins\\UIFoundation\\UIFoundationPlugin'                   => BASE_FOLDER . '/Plugins/UIFoundation/UIFoundation/UIFoundation.php',
    'Molajo\\Event\\Plugins\\Useractivity\\UseractivityPlugin'                   => BASE_FOLDER . '/Plugins/Useractivity/Useractivity/Useractivity.php',
    'Molajo\\Event\\Plugins\\Username\\UsernamePlugin'                           => BASE_FOLDER . '/Plugins/Username/Username/Username.php',
    'Molajo\\Event\\Plugins\\Version\\VersionPlugin'                             => BASE_FOLDER . '/Plugins/Version/Version/Version.php',
    'Molajo\\Event\\Plugins\\Weather\\WeatherPlugin'                             => BASE_FOLDER . '/Plugins/Weather/Weather/Weather.php'
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);
