---
title: 'コンテンツ内の Twig'
lastmod: '2026-08-01T20:16:07+09:00'
description: 'Grav 2.0 がページコンテンツ内の Twig をどのように入り口制御し、サンドボックス化するのか、その有効化方法、サンドボックスのカスタマイズ化、そしてより安全な代替手段を説明します。'
weight: 43
params:
    srcPath: /content/twig-in-content
---

Grav では、ページのマークダウンコンテンツ内に **Twig** 処理を書けます。
そのため、 `{{ ... }}` 表現や `{% ... %}` タグをページの本文に直接書くことができます。
Grav 2.0 では、このふるまいをセキュリティ的な理由から変更します：コンテンツ内の Twig は、 **デフォルトで無効** とし、それを有効化したとき、 **セキュリティサンドボックス** 内でコンテンツが実行されます。

===

> [!Note]  
> このページは、 **ページコンテンツ** 内の Twig についてです。テーマやプラグイン内の `html.twig` ファイルは、ディスク上の信頼できるコードであり、 **決して** サンドボックス化されません。このページの内容は、テーマ開発やプラグイン開発に影響を与えません。

## 2.0 での変更点{#what-changed-in-2-0}

Grav 1.7 では、どんなページでも、フロントマターに `process.twig: true` を書くと、制限なくそのページのコンテンツを Twig 処理していました。ページコンテンツは、ページ編集権限があるなら誰でも編集できるので、ページ編集者は、秘匿すべき設定を読み取ったり、ファイルシステムにファイルを作成したり、より悪い場合は、サーバー上でコードを実行したりする Twig を書くことができました。
この分野の脆弱性は、 **サーバー再度テンプレートインジェクション（SSTI）** として知られています。

Grav 2.0 では、この穴を2つの独立したレイヤーで塞ぎます：

1. **ゲート** コンテンツ内の Twig は、管理者がセキュリティ設定で明示的に有効化しない限り、全く実行されません。
2. **サンドボックス** コンテンツの Twig が実行されるとき、安全なタグ、フィルター、関数、メソッド、及びプロパティの許可リストのみが実行されます。それ以外は、すべてブロックされます。

この2つは、別々のものです。
ゲートは、コンテンツの Twig が実行 *されるかどうか* を決定し、サンドボックスはそれが実行されるときに *何ができるか* を決定します。
ゲートを有効化しても、それがすなわちサンドボックスを無効化するわけではありません。

## コンテンツ内の Twig の有効化{#enabling-twig-in-content}

### 1. プロフィールを選択{#1-choose-a-profile}

管理パネルでは、 **Configuration -> Security** へ移動してください。
ページの上部に一つの **Twig in Content** プロフィール選択があり、一般的なケースをカバーしています：

| プロフィール | 影響 |
| --- | --- |
| **Off** | Twig コンテンツを決して実行しない。デフォルト |
| **Trusted roles only** | Twig コンテンツを実行するが、 super user 及び `admin.pages_twig` パーミッションを持つユーザーのみがページ上で有効化できる |
| **All editors** | Twig コンテンツを実行する。ページ編集可能なユーザーはすべて彼らのページ編集において有効化できる |
| **Custom** | Shown only when your saved settings do not match a named profile (for example a hand-edited combination). Picking a named profile replaces it. |

プロフィールを選択すると、以下の2つのキーが書き込まれます。
そのため、これらを直接操作する必要はほとんどありません。
これらは、セレクターの下に advanced view として利用可能のままです：

| 設定 | デフォルト | 影響 |
| --- | --- | --- |
| **Process Enabled** | Off | マスタースイッチ。off の間は、Twig コンテンツはページのフロントマターによらず実行されません。 |
| **Editor Enabled** | Off | ページ編集から `process.twig` を誰が変更できるかを制御します。 Off: `admin.pages_twig` パーミッション（もしくは super user）のみ。On: ページ編集可能な誰でも。 |

`user/config/security.yaml` の環境設定：

```yaml
twig_content:
  process_enabled: true
  editor_enabled: true
```

これらのキーへのプロフィールマップは、次のようになっています：
**Off** は、両方 off, **Trusted roles only** は、 `process_enabled: true` 及び `editor_enabled: false` そして **All edotors** は、両方 on です。

### 2. Know what the gate turns on

With **Process Enabled** on, the gate is the single source of truth for content Twig, and **every page processes Twig in its content by default**. This is a change from 1.7, where each page opted in individually through its front matter. You no longer flag pages one at a time.

If you want to keep a specific page from running Twig in its content, opt that page *out* in its front matter:

```yaml
---
title: My Page
process:
    twig: false
---
```

An explicit `process.twig` value on a page (`true` or `false`) always wins over the gate, so you can still force Twig on for a single page, but you rarely need to. Because the switch enables content Twig across the whole site, turn it on deliberately.

### Keeping literal `{{ }}` in content

When content Twig is enabled, Grav evaluates every `{{ ... }}` and `{% ... %}` in a page's content. That is a problem if the double braces are meant for something other than Grav, such as a Formspree form field, or a Vue, Handlebars, or Angular template you are embedding. Grav resolves the expression, finds nothing, and the braces disappear from the output.

You have two ways to keep the literal braces:

1. Opt the whole page out of content Twig with `process.twig: false` in its front matter (shown above).
2. Wrap just the affected markup in a `verbatim` tag so Grav leaves it untouched while the rest of the page still processes Twig:

```twig
{% verbatim %}
<input type="hidden" name="subject" value="Feedback: {{ user_subject }} {{ topic }}" />
{% endverbatim %}
```

On a default install content Twig is off, so these braces already pass straight through and no escaping is needed.

### 3. Clear the cache

After changing security configuration or a page's `process` setting, clear the cache so pages re-render:

```bash
bin/grav cache
```

!!! If **Process Enabled** is off and a page sets `process.twig: true`, the page's Twig is **not** rendered. The raw `{{ ... }}` and `{% ... %}` markup is returned as literal text, and a notice is written to `logs/security.log`.

## The Sandbox

Once content Twig runs, it runs inside the sandbox defined under `twig_sandbox` in `system/config/security.yaml`. The sandbox allows a generous set of safe, read-only Twig out of the box, which covers the large majority of real-world use:

* **Tags** for control flow and composition: `if`, `for`, `set`, `block`, `include`, `embed`, `macro`, `apply`, `with`, `verbatim`, and more.
* **Filters** such as `date`, `upper`, `lower`, `default`, `markdown`, `json_encode`, `number_format`, `nicetime`, `slug`, `length`, `join`, `sort`, and many others.
* **Functions** such as `url`, `authorize`, `t`, `media_directory`, `theme_var`, `nicetime`, `dump`, and the standard Twig helpers like `range`, `min`, `max`, and `cycle`.
* **Read-only methods and properties** on everyday objects: the current `page` (title, header, media, children, collection, taxonomy, and so on), its `media`, the `uri`, the active `user`, and the active `language`.

The complete default lists live in `system/config/security.yaml` under `twig_sandbox` and can be reviewed in the Admin panel under **Configuration → Security → Twig Sandbox**.

### What happens when something is blocked

The sandbox fails gently rather than throwing a fatal error:

* The blocked expression **renders as literal text** in the output, so you can see exactly what was stopped.
* A line is written to `logs/security.log` naming the blocked tag, filter, function, method, or property, along with the page route and a hint on how to allow it.
* The same event is recorded for the **Twig in Content report** (see below), so you do not have to read the log file to find out what was blocked.
* If you are logged in as a super administrator and `admin_hint` is enabled, Grav adds an HTML comment near the blocked expression pointing you at the log.

So if a page shows raw `{{ something() }}` after you've enabled the gate, the sandbox blocked `something()`. The report names the exact member it did not recognize and how to allow it.

## The content XSS scan

The blueprint XSS validator inspects the **raw** content you save, so a payload assembled at render time (for example `{{ "on" ~ "error" }}`, which only becomes `onerror` once Twig runs) slips past it. To close that gap Grav re-scans the **resolved content** for XSS after its own Twig has run, and blanks the content if it finds any. A blanked render is logged to `logs/security.log` as `[TwigContentXss]` and shown in the report above.

The scan runs on the editor's content **before** the theme or modular template wraps it, so trusted template markup, such as a form's reCAPTCHA script or a provider embed, is never scanned and cannot be blanked by mistake.

It is controlled by a single setting, on by default:

```yaml
security:
  content:
    xss_scan_output: true
```

!! This is a **content** setting, not a Twig-in-content one, so it also applies to modular pages (whose bodies are always Twig-processed). In earlier 2.0 releases it lived at `security.twig_content.xss_scan_output`; that location still works and is moved to `security.content.xss_scan_output` automatically the next time Grav upgrades.

## The Twig in Content Report

Every silent failure on this page (a gated page, a sandbox block, a page leaking raw Twig) is collected into one place in Admin: **Tools → Reports → Twig in Content**. It saves you from reading `logs/security.log` by hand. The report shows:

* **The current state** at a glance: whether the gate, the sandbox, and the output XSS scan are on.
* **Pages leaking raw Twig.** Any page whose content contains `{{ ... }}` or `{% ... %}` that will not render (because the gate is off, or that page has Twig turned off) is listed with a plain-language reason and a link straight to the page editor.
* **Recent blocks.** The most recent gate blocks, sandbox blocks, and XSS-blanked renders, each with the page route and the same hint that goes to the log. A sandbox block carries an **Add to allowlist** button that appends the blocked member to the correct list for you, with the full existing list preserved (see [Customizing the Sandbox](#customizing-the-sandbox)).
* **Scan content.** A button that scans every page's content for tags, filters, and functions the sandbox does not currently allow, so you can see what your content needs *before* you turn the gate on rather than discovering it page by page.

When you open a page in the editor, the same information appears as an inline banner at the top of that page if its content would leak raw Twig or recently hit a block, with a link to the full report.

!! A page can carry `process.twig: true` (often left over from a Grav 1.7 site) while the gate is off. In 2.0 that flag is only a request: the gate still has the final say, so those pages render raw. The report calls this out explicitly so you can either pick a profile to enable Twig or remove the stale flag.

## Customizing the Sandbox

If you have decided that a particular Twig member is safe to run against content your authors could write, you can add it to the allow-list. First, an important caveat about how these lists merge.

The sandbox allow-lists are **not additive when edited by hand.** The flat lists (`allowed_functions`, `allowed_filters`, `allowed_tags`) are each replaced wholesale by whatever you put under that key in `user/config/security.yaml`, and the per-class lists (`allowed_methods`, `allowed_properties`) merge by position, so a partial list silently overwrites the existing entries. Writing this:

```yaml
twig_sandbox:
  allowed_functions:
    - my_safe_function
```

would drop every built-in safe function (`url`, `t`, and the rest) and leave only `my_safe_function`, breaking far more than it fixes. There are three safe ways to add a member:

* **Use the report (easiest).** When a member is blocked, the **Twig in Content** report (under **Tools → Reports**) lists it with an **Add to allowlist** button. One click appends it to the correct list with every existing entry preserved. This is the quickest safe path because you act on the exact member Grav just blocked.
* **Use Admin.** Under **Configuration → Security → Twig Sandbox**, each field is pre-loaded with the current full list. Add your entry and save, and Grav writes the complete list back, so the defaults are never lost.
* **Edit by hand with the full list.** Copy the entire default list for that key out of `system/config/security.yaml` into your `user/config/security.yaml`, then append your addition, keeping every existing entry intact.

The same rule applies to the per-class methods and properties lists: include every existing `class`/`methods` (or `class`/`properties`) row alongside any you add.

```yaml
twig_sandbox:
  allowed_methods:
    - class: 'Grav\Plugin\MyGallery\Gallery'
      methods: 'render, thumbnail'
    # ...plus every default row copied from system/config/security.yaml
```

Plugin developers can register their own safe members programmatically through the `onBuildTwigSandboxPolicy` event, so a plugin works in sandboxed content without each site having to edit `security.yaml` at all. **This is the durable fix for plugin-provided members**, and it avoids the full-list maintenance burden entirely. See the [Developer Upgrade Guide](../../migration/developer-upgrade-guide#twig-content-sandbox) for the event signature and a worked example.

!!! Only allow members that are safe to run against content authored by anyone with page-edit access. Adding a member to the allow-list is the same trust decision as exposing it in the first place. If a function reads files, evaluates strings, or reaches into Grav's container, leave it off the list.

### The `config` variable

By default the `config` Twig variable is **empty** inside sandboxed content, so page authors cannot read your site configuration (which may contain secrets). If you need read access to non-sensitive config from content, turn on **Config Access** in the **Twig Content** section, or set `twig_content.config_access: true`. With it on, `config` becomes a filtered facade that still redacts sensitive subtrees listed under `twig_sandbox.config_denied_paths` (by default: `plugins`, `streams`, `security`, `backups`, `scheduler`).

### Disabling the sandbox

The sandbox can be turned off entirely with `twig_sandbox.enabled: false`, which removes all SSTI protection from editor-authored content. **This is strongly discouraged** on any site where more than one fully trusted person can edit pages. There is almost always a narrower customization (allow-listing a specific member) that solves the real problem without removing the protection.

## A Better Path: Move Twig Out of Content

The sandbox exists for the cases where you genuinely need Twig in content, but the recommended approach in Grav 2.0 is to avoid Twig in content wherever you can. It mixes presentation logic into plain writing, it is harder for non-technical authors to edit safely, and it ties content to template internals. Two patterns cover almost every case.

### Use a page template

If a single page needs template logic, give it its own template in your theme and reference it with `template:` in the front matter. Templates are trusted and unsandboxed, and template logic belongs there. The page body goes back to being clean Markdown.

### Use a shortcode

For repeatable, author-friendly pieces, a custom **shortcode** is usually the better answer. Instead of teaching authors a Twig snippet (and allow-listing whatever it needs), you give them one short, readable tag, and all the logic lives in your plugin's PHP. No Twig runs in the content, so there is nothing for the sandbox to block.

See [Creating a Shortcode](../creating-a-shortcode) for a step-by-step "Twig before, shortcode after" walkthrough. The community [Shortcode Core](https://github.com/getgrav/grav-plugin-shortcode-core) plugin also provides a large set of ready-made shortcodes.

