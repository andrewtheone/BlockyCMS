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

## Hiearchy
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

## Creating a FieldType
In your Extension class you may implement the Blocky\Extension\FieldTypeProvider interface. By doing that you'll end up something like this:

```php
   public function getFieldTypes() {
      return [
          new Fields\TextField(),
          new Fields\CustomField()
      ];
   }
```
Sample of a Text field type:
```php
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

## Unordinary field types

### slug
```yaml
  uses: title  # The key of the field which will be used to generate slug
```

### timestamp
```yaml
  default: now
```

### random
```yaml
  visible: false # A random field will always HAVE TO BE INVISIBLE
```

### password
```yaml
  uses: keyOfRandomField # This field will be used as a salt when encrypting the plain password
```

### repeater
```yaml
  fields: # Repeater is a special field which lets you content editors to repeat a certion section in a content. A field with type repeater always have a fields key which can be filled with fields just like the contenttype's fields key BUT you cannot use field fieldtype SLUG. Each repeatable field is called a section, and it can hold >= 0 elements in itself, which all have the fields represented under the fields keys. The elements in a repeateable section can be organized and sorted. 
    attribute:
      type: text
    value:
      type: text
```

### image
```yaml
  watermark: 'path to the watermark file'
```

### grid
```yaml
  columns: A, B, C, D
```

### select:
```yaml
  foreign: foreign_content_type # If ommited options key will be used for options. The foreign contenttype have to have a field called title!
  multiple: true
  options:  # foreign have a higher precedence, if foreign key exists options will be ignored!
    key1: text1
    key2: text2
```
## Default FieldTypes
Bultin FieldTypes which are shipped with Blocky:
- text
- slug
- html
- random
- password
- image
- grid
- repeater
- timestamp
- select

# Bultin Extensions

## Core\Content
This is the heart of the system, the Content service functions as an extension.
It provides the following Twig Functions:
- ```getContent(contenttypeslug, where, args)``` and returns a contentlist

## Fields

Fields extension provides the default fields - listed above - and a few twig functionalities. Notables are:
- ```|image()``` filter which can be applied to either a image fieldtype or a string which locates a file (NOTE: the path of the file should be from the root dir!) and it accepts an object as a parameter, sample object to be passed: 
```yaml
{
size: [w, h],
quality: 100,
watermark: '...',
alt: '...',
title: '...',
lazy: true,
onlySource: false,
data: {},
class: ''
}
```
## Forms
The forms extension handles every form in a blocky page. It creates a forms.yml in the main config directory, in which you can create your forms.

### Twig functionalities
It helps you build forms quickly, and manage them. Capable of rendering standard forms and ajax handled forms.
It provides the following Twig Functions:
- ```forms_form(formName, defaultContent, additionalOptions)``` default content has to be a Blocky\Content\Content instance and is of the same contenttype as the form is!
- ```forms_ajaxForm(formName, defaultContent, additionOptions)``` same as the above one, except it renders an ajax form.

Additonal Options and forms.yml options are merged together. Optional options are:
```yaml
handler: 'someJavascriptFormsClass' # it is only used in ajax forms, you can override the default client side form handler class
```

### Ajax forms
The default client side ajax form handler class is called SimpleForm and is built up like this:
```js

 	var simpleHandler = function(form) {
 		this.form = form;
 		this.name = name;

 		this.form.bind("submit", this.onSubmit.bind(this));
 	}

 	simpleHandler.prototype.onSubmit = function() {
 		this.form.find("input, select, textarea").click(this.resetErrorMessages.bind(this))
 		$.ajax(this.form.attr('action'), {
 			data: this.form.find("input, select, textarea").serializeObject(),
 			type: 'post',
 			dataType: 'json',
 			success: this.onResponse.bind(this)
 		})
 		return false;
 	}

 	simpleHandler.prototype.onResponse = function(resp) {
 		if(resp.success == 0) {
 			for(var i in resp.errors) {
 				var key = resp.errors[i].field;
 				var msg = resp.errors[i].message;
 				this.form.find("[name='form_data["+key+"]']").each(function() {
 					$( $(this).attr('data-error-element') ).html(msg);
 				})
 			}
 		} else {
 			this.form.trigger("reset");
 			if(resp.messages.length > 0) {
 				for(var i in resp.messages) {
 					alert(resp.messages[i]);
 				}
 			}
 			var redir = resp.redirect;
 			if(redir == "__none__")
 				return;
 			if(redir == "__self__")
 				return window.location.reload();

 			window.location = redir;
 		}
 	}

 	simpleHandler.prototype.onKeyDown = function() {

 	}

 	simpleHandler.prototype.resetErrorMessages = function() {
 		this.form.find("input, select, textarea").each(function() {
 			$( $(this).attr('data-error-element') ).html('');
 		})
 	}

 	simpleHandler.prototype.unregister = function() {
 		this.form.unbind("submit");
 	}
```

You may extend simple form in the following way:
```js
$(document).ready(function() {
	$.fn.ajaxForm.extend("HANDLERNAME", {
		onSubmit: function() {
			alert("now it is not sending a request, just alerting.. :P");
			return false;
		}
	});
})
```

### Having a custom FormManager
By creating a class which extends Blocky\Forms\BaseFormManager ... you pretty much end up with a working form manager.
The only notable method in a form manager class is the ```onProcess($process, $etc = null)```. Where $process is a const from the BaseFormManager. The way it works is that the FormController calles the onProcess method of the provided form manager through-out the form procession stages with different $process. In each process you may be able, to modify both how the form works, and the content itself which is created from the submitted data. ContentSaveExceptions thrown from the onProcess method are handled as validation errors. In the /extensions/Blocky/Forms/BaseFormManager.php you may find all the $process const values which are currently can be used!

A sample forms configuration:
```yaml
form_name: # through this you can access your form
    contenttype: contenttype_of_the_form # Every form has to have a contenttype, in which the submitted data will be saved and/or the fields of the form are gathered
    manager: '\Blocky\EXTENSION\Form\FormManager' # You may want to modify how your form works
    fields: # If ommitted and you are using the default form view then all text/select/html fields will be used which can be found in the provided contenttype
        - email
        - password
    store: true # Determines if the forms should be saved or not
    success_message: 'Success message' # A success message
    success_redirect: '__self__' # Redirection notes uppon successful form submit, options (__self__, __none__, route name, or full route)
    email: # If present, an email will be sent uppon form submit
       to: your email
       subject: subject
       template: '@VIEW/email.twig'
    layout: '@VIEW/_form_layout.twig' # If you in the mood of unique form layouts :)
```
## Members

## Members / Passport
