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

Sample contenttype with every possible option:
```yaml
test: # Unique slug of the contentype
  name: Tests # Plugral name of the contenttype
  singular_name: Test # Singular name of the contettype
  middlewares: # Middlewares which will be executed if requested throught Blocky\Frontend\Controller\FrontendController::record
    - '\Blocky\Members\Middleware\Auth::onlyLoggedIn'
  permissions: [read_permission, write_permission] # You can use the same permission name through many contenttypes
  localizable: true # Indicates it is multilingual
  show_menu: false # If true it is shown in the admin under Contents
  syncable: false # If true it can be either displayed on demo or production or both sites (It is nice to think of as a 'preview' option)
  manager: 'Blocky\EXTENSION\Content\TestManager' # It is a content based class, which means each content in a contentlist have their own instance of the manager, the manager's respected method is called every once in a while when a content is being filled, saved, retrieved, and so on
  backend_list_header: '@VIEW/_list_header.twig'
  backend_list_footer: '@VIEW/_list_footer.twig'
  backend_list_item: '@VIEW/_list_item.twig'
  backend_content_edit: '@VIEW/editcontent.twig'
  custom_query:
      list: "order by date desc" # It is prepend to the end of the query when getContents is called from the admin lister
  fields: # Fields and their options
    title:
      label: Title
      type: text
    slug: # Each contenttype always have to have a slug!
      label: Slug
      uses: title
      type: slug
```
# FieldTypes

In BCMS all contenttype's field is handled via a class, called FieldType class. A new FieldType can be registered through an extension, and it has to implement the Blocky\Content\SimpleFieldInterface and extend Blocky\Content\SimpleField class.

Sample of a Text field type:
```yaml
class Text extends SimpleField implements SimpleFieldInterface
{

	public function getTemplate() {
		return "@fields/text.twig";
	}

	public function processInput(Content $content, $input, $options) {
		return $input;
	}

	public function extractValue(Content $content, $value, $options) {
		return $value;
	}

	public function getName() {
		return "text";
	}

} 
```

# Fields
Fields and their options are located under the 'fields' keys in every contenttype. Each field have to have a type, which is the same what a FieldType::getName() would return.

Sample field under fields key in a contenttype with every option:
```yaml
  username:
      type: text
      label: Username
      placeholder: Username
      visible: false # Wather if visible at content editing in the backend
      group: Details # Fields can be grouped in different groups, so you can organize them better
      template: '@VIEW/some_field_html.twig' # It is used for backend content editing field printing, if not provided the return of  the getTemplate() of field type will be used 
      validators:
          -
              regexp: empty # Built in regexp alias
              message: Username cannot be empty
          -
              regexp: unique # Built in regexp alias
              message: Username already taken
          -
              regexp: '/[a-z]/'
              message: Username have to contain at least one character
```

There a few un-ordinary field types which can have different options as well, like:

slug:
```yaml
  uses: title  # The key of the field which will be used to generate slug
```

timestamp:
```yaml
  default: now
```

random:
```yaml
  visible: false # A random field will always HAVE TO BE INVISIBLE
```

password:
```yaml
  uses: keyOfRandomField # This field will be used as a salt when encrypting the plain password
```

repeater:
```yaml
  fields: # Repeater is a special field which lets you content editors to repeat a certion section in a content. A field with type repeater always have a fields key which can be filled with fields just like the contenttype's fields key BUT you cannot use field fieldtype SLUG. Each repeatable field is called a section, and it can hold >= 0 elements in itself, which all have the fields represented under the fields keys. The elements in a repeateable section can be organized and sorted. 
    attribute:
      type: text
    value:
      type: text
```

image:
```yaml
  watermark: 'path to the watermark file'
```

grid:
```yaml
  columns: A, B, C, D
```
