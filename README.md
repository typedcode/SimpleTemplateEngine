SimpleTemplateEngine
--------------------

Simple Template Engine written in PHP

1. [Initialization](#initialization)
1. [Parsing a template](#parsing-a-template)
1. [Assigning and using variables](#assigning-and-using-variables)
1. [Using session variables](#using-session-variables)
1. [CSS and JS files](#css-and-js-files)

Initialization
--------------
With initializing the Engine you also set the Template-Directory. The Template Directory is the directory where the Engine will search for the Files to parse (the template files). If you don't enter a template path the templates will be searched relative to the current path.

E.g.
```php
$engine = new SimpleTemplateEngine();
```
Will initialize the Engine with the current Directory as the Template-Directory.
```php
$engine = new SimpleTemplateEngine( "./templates" );
```
Will initialize the Engine with the given template path. When parsing templates they will be expected relative to the given path.

Parsing a template
------------------
For getting a result out of the engine one must parse a template file.
```php
echo $engine->parse( "myTemplate.html" );
```
This will trigger the parsing process within the engine. Here `myTemplate.html` has to be a file inside the `template`-Directory which was set with the initialization. The parsed Result will be returned as a string.

Assigning and using variables
-----------------------------
Assigning a variable is done by the `assign( $key, $value )`-Method.

E.g. the following php-script
```php
$myVariable = "This is a dummy text.";
$engine->assign( 'dummyText', $myVariable );
```
when parsed with this Template
```html
<div>{$dummyText}</div>
```
Will result in
```html
<div>This is a dummy text.</div>
```
One is not limited in assigning String values. One also can assign objects and access fields or functions:
```html
<!-- Accessing a field -->
{$object->myField}
<!-- Accessing a function return value -->
{$object->function()}
```
Accessing an array can be done via the arrow notation `->`.
```php
$array = array( "value" );
//or
$array = array( "key" => "value" );

$engine->assign( "array", $array );
```
Can be accessed like this
```html
<div>{$array->0}</div> <!-- for the indexed array -->
<div>{$array->key}</div> <!-- for the associative array-->
```
Chaining is not supported.

Using session variables
-----------------------
If a session was started when the engine is initialized a session variable will be automatically set and can be accessed in the template through `{$SESSION->identifier}`


CSS and JS Files
----------------
The engine provides the possibility to add paths of CSS and JS files.

Adding a css path is done with `$engine->addCSSFilePath( "/path/to/style.css" )` which adds `"/path/to/style.css"` to the List of *.css files.

In your template file you then can use `{foreachcss}` to include the css files.

E.g.

Adding paths to the Engine:
```php
$engine->addCSSFilePath( "path/to/firstCSSFile.css");
$engine->addCSSFilePath( "path/to/secondCSSFile.css");

```
Template (not that {__CSS} is a special variable which you don't have to declare here):
```html
<html>
    <head>
        {foreachcss}
            <link rel="stylesheet" type="text/css" href="{__CSS}">
        {/foreachcss}
    </head>
</html>
```

Will result in the output:

```html
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="path/to/firstCSSFile.css">
        <link rel="stylesheet" type="text/css" href="path/to/secondCSSFile.css">
    </head>
</html>
```

For JS simply use `$engine->addJSFilePath( $path )` to set the paths and `{foreachjs}...{/foreachjs}` in the template file.
