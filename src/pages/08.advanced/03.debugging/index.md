---
title: "デバッグとログ"
layout: ../../../layouts/Default.astro
---

テーマとプラグインを開発するとき、しばしば **デバッグ** 情報を表示する必要があります。Grav にはさまざまな種類の機能により、強力にデバッグできます。

<h2 id="debug-bar">デバッグバー</h2>

Grav には、 **デバッグバー** と呼ばれる、デバッグの努力を簡単にしてくれるすばらしいツールがあります。この機能は、デフォルトでは **無効化** されていますが、`system.yaml` 設定ファイルから有効化できます。すべての環境でも適用できますし、もしくは [開発環境](../04.environment-config/) でのみ適用することもできます。

```yaml
debugger:
  enabled: true                        # Enable Grav debugger and following settings
  provider: debugbar                    # Set provider to debugbar
  shutdown:
    close_connection: true             # Close the connection before calling onShutdown(). false for debugging
```

![](config.png)

> [!Info]  
> The first time you enable it, the debug bar might appear as a little Grav icon in the bottom left of the page. Clicking that will show the full debug bar.

The PHP Debug Bar still provides an overall **processing time** as well as **memory usage**, but it now has several tabs that provide more detailed information.

The first tab is for **Messages** and you can use this to help debug your Grav development process by posting information to this tab from your code.

Along with **Request**, **Exceptions**, and **Configuration** information, you can also see a detailed breakdown of Grav timing in the **Timeline** panel:

![](timeline.png)

### Dump Command for PHP

If you are trying to debug some PHP, for example a custom plugin you are developing, and wish to quickly examine some object or variable, you can use the powerful `dump()` command.  This accepts basically any valid PHP variable and will output the results in a nicely formatted and colorized display in your browser.

For example, you can easily dump a PHP variable or object:

```php
dump($myvariable);
```

and see the results in your browser:

![](dump.png)

You can also dump variables into the **Messages** tab of the Debug Bar by using the syntax:

```php
$this->grav['debugger']->addMessage($myvariable)
```

### Dump command for Twig

You can also display Twig variables from your Twig templates.  This is done in a similar fashion, but the results are displayed in the **Messages** panel of the Debug Bar. This feature is **disabled** by default, but can be turned on either globally or for your [development environment](../environment-config) only via the `system.yaml` configuration file:

```yaml
twig:
  debug: true                        # Enable Twig debugger
```

For example, you can easily dump a Twig variable or object:

```twig
{{ dump(page.header) }}
```

and see the results in the Debugbar:

![](twig-dump.png)

It's possible to output multiple dump commands at the same time but it can get confusing to tell them apart. Add static text like this:

```twig
{{ dump('page.header output:',page.header) }}
```

```
![](twig-dump-text.png)
```

### Dump to browser console from Twig

To display variables before a page is returned by Grav or in case no page refresh occurs such as when using AJAX there is another alternative. By using a single line of Javascript any variable can be displayed in your browser's developer console, for example:

```twig
<script> console.log({{ page.header|json_encode|raw }}) </script>
```

Then examine the value in the browser console:

![](console-dump.png)

## Error Display

Our new error display page provides detailed information, backtraces, and even relevant code blocks.  This helps to more quickly isolate, identify and resolve critical errors. By default in Grav, these are turned off by default, so you will need to enable them to take advantage of this helpful error handling for development:

```yaml
errors:
  display: true
```

![](error.png)

For production environments you can disable the detailed error page with something more subtle by configuring the errors options in your `user/config/system.yaml` file and rely on errors being logged to file:

```yaml
errors:
  display: false
  log: true
```

![](error2.png)

## Logging

The ability to log information is often useful, and once again, Grav provides us with a simple put powerful logging feature.  Use one of the following syntaxes:

```twig
$this->grav['log']->info('My informational message');
$this->grav['log']->notice('My notice message');
$this->grav['log']->debug('My debug message');
$this->grav['log']->warning('My warning message');
$this->grav['log']->error('My error message');
$this->grav['log']->critical('My critical message');
$this->grav['log']->alert('My alert message');
$this->grav['log']->emergency('Emergency, emergency, there is an emergency here!');
```

All your message will be appended to the `/logs/grav.log` file.

