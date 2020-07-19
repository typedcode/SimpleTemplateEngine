SimpleTemplateEngine
--------------------

Simple Template Engine written in PHP

* [CSS and JS files](#css-and-js-files)

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
        {foreachcss}
            <link rel="stylesheet" type="text/css" href="path/to/firstCSSFile.css">
            <link rel="stylesheet" type="text/css" href="path/to/secondCSSFile.css">
        {/foreachcss}
    </head>
</html>
```

For JS simply use `$engine->addJSFilePath( $path )` to set the paths and `{foreachjs}...{/foreachjs}` in the template file.
