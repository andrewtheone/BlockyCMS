# BlockyCMS
Blocky CMS is a flexible, extendable content management system.
It uses RedBeanPHP as a database manager, we decided to use it because it dynamically creates and alters tables.

In BCMS everything is an extension, except for the core.. :P

# Extensions
Extensions are capable of:
- Register front and backend routes
- Create/Extend configuration and other extensions' config file
- Provide ContentTypes
- Provide FieldTypes
- Provide Services
- Provide Twig view namespaces
- Provide Twig functions and filters
- Register event listeners

Every extension may extends Blocky\Extension\SimpleExtension class.
To have an extension loaded you may add it in the app/config/config.yml file, under 'extensions' key.
If an extension is loaded for the first time, the ExtensionService calls $extension->install() method, in this method you may create or extend a config file, and/or perform some ONE-TIME duties.

Extensions are stored under /extensions folder. And the folder stucture of an extension is "/extensions/Author/ExtensionName" and must provide a "ExtensionNameExtension.php" which holds class its respected name, like "Author\ExtensionName\ExtensionNameExtension".

An extension has to have a getName method, which returns its name in the form "Author::NameExtension".

# ContentTypes
ContentTypes are the hearts of BCMS. As its name suggests, a ContentType describes a certain content, in every possible aspects (name, backend and default frontend views, fields, permissions, etc)

There are two (+1) ways to create a content type.
- You may add your content type to app/config/contenttypes.
- You may implement Blocky\Extension\ContentTypeProvider interface in your extension
- Your extension may $this->extendConfig(..) at install.
