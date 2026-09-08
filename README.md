# Modern WordPress Plugin Boilerplate

Plugin Name:    `plugin name goes here`  
Plugin URI: 	`Plugin URI goes here`  
Description:    `Description goes here`  
Version:        1.0.0  
Author:         `Author goes here`  
Author URI: 	`Author URI goes here` 

## Description

A reusable, production-minded foundation for modern WordPress plugins. It keeps hooks explicit, uses small namespaced services, supports dynamic Gutenberg blocks, and includes the tooling needed to ship confidently.

## Requirements

- WordPress 6.7+, PHP 8.1+, Composer 2, Node.js 22 and npm
- Docker Desktop and VS Code only for the optional Dev Container

These are project defaults, not WordPress core's minimum versions. Adjust them for your audience.

## Start a new plugin

Copy this directory and run the initializer **before editing files**:

```bash
php bin/init-plugin --name="Acme Search" --slug=acme-search --namespace="Acme\\Search" --vendor=acme
composer install
npm install
npm run build
```

It updates the name, slug, text domain, namespace, Composer package, Docker mount and main filename. Then review the plugin header and replace its placeholder URI and author.

Alternatively, choose **Reopen in Container** in VS Code. Dependencies install automatically. WordPress is at `http://localhost:8080`; phpMyAdmin is at `http://localhost:8081`.

## Architecture

```text
plugin entry point
  -> requirement check
  -> Plugin coordinator
     -> independent services register their own hooks
```

`Plugin::$services` is the visible composition root. This deliberate list is easier to debug than runtime auto-discovery and makes optional features easy to remove.

```text
src/                 PHP application code
templates/           front-end and admin PHP partials
blocks/              block source and metadata
assets/              shared JavaScript and Sass
build/               generated assets (do not edit)
tests/               fast unit tests
bin/                 initializer, generators and packager
.devcontainer/       PHP, Node, WP-CLI, WordPress and MariaDB
.github/workflows/   automated PHP and JavaScript checks
```

## Included examples

- `ExamplePostType`: REST-enabled custom post type.
- `ExampleTaxonomy`: REST-enabled hierarchical taxonomy.
- `ExampleRoute`: public `GET /wp-json/modern-plugin/v1/status` route.
- `SettingsPage`: Settings API page with capability checks, sanitisation and an admin PHP template.
- `ExampleDisplay`: front-end action hook and shortcode sharing one PHP template.
- `blocks/example`: dynamic API v3 block using `block.json`, React and Sass, rendering the same front-end partial.

Rename and adapt them, or delete their files and remove them from `Plugin::$services`. If removing the CPT/taxonomy, also remove their activation calls.

## PHP templates: action hooks, shortcodes and blocks

The `ExampleDisplay` service is already registered in `Plugin::$services`. All three front-end approaches load `templates/frontend/example.php`; edit that file for the shared HTML. The action and shortcode prepare their message in `src/Frontend/class-exampledisplay.php`; the block prepares it from its attributes in `blocks/example/render.php`. The partial uses `wp_kses_post()` to preserve safe RichText formatting while filtering disallowed HTML.

### Front end: action hook

Place this in a **PHP theme template** where the example should appear:

```php
<?php do_action( 'modern_plugin_example' ); ?>
```

The service's `add_action()` registers its `render()` callback. When the theme reaches `do_action()`, WordPress calls `render()`, which includes the partial and outputs its HTML at that position. Nothing is displayed merely by registering the action. The example does not attach itself to a global hook or create a page.

WordPress block-theme `.html` templates and editor Custom HTML blocks do not execute PHP. Use the shortcode approach below for those layouts, or a dynamic block that renders your partial. An existing display action supplied by a theme or plugin can also be used by changing the hook name in `register()`; choose a hook that runs at the intended HTML position.

### Front end: shortcode

Add a **Shortcode block** to a WordPress page or block template and enter:

```text
[modern_plugin_example]
```

Publish the page to display the example at the shortcode's position. `renderShortcode()` buffers the same `render()` callback and returns its HTML, as required for shortcodes. The action callback outputs HTML directly. Use either placement method; using both will display the example twice.

### Front end: dynamic block

Insert **Modern Plugin Example** into a page or a Site Editor block template. Edit its message directly in the editor. On the front end, WordPress runs `blocks/example/render.php` (from its compiled copy in `build/blocks/example/`), which passes the message to the same `templates/frontend/example.php` partial used by the action and shortcode.

The renderer retains `get_block_wrapper_attributes()` around the partial so block alignment, spacing and CSS classes still apply. The editor provides an editable message preview; the shared partial supplies the final front-end markup, including its heading.

Changes to the shared partial take effect without a build. After changing block source files, run `npm run build` to refresh `build/`; the checked-in PHP renderer copy is already updated for this example. Use whichever placement method suits the layout; each placement renders its own instance.

### Back end: admin template

Open **Settings → Modern Plugin**. The existing `SettingsPage` service registers this screen through `admin_menu` and `add_options_page()`. WordPress calls its `render()` method when the screen is opened. After checking `manage_options`, it includes `templates/admin/settings.php`.

The partial contains the settings form; `settings_fields()` retains the Settings API nonce and option-group fields, while registration and sanitisation remain in the service. Adapt the admin HTML in the partial and the behaviour in `src/Settings/class-settingspage.php`.

### Rename or remove the examples

`bin/init-plugin` renames the hook, shortcode, namespace and text domain along with the rest of the boilerplate. To remove the front-end example, remove `ExampleDisplay` from `Plugin::$services`, its import and its class. Keep the shared partial while the example block still uses it; delete it only after removing all consumers. Remove any corresponding shortcode or theme hook placements as well. Keep `templates/` in release packages: these PHP files are loaded at runtime.

## Generate components

```bash
php bin/make-service Notifications
php bin/make-block testimonial "Testimonial"
```

Add a new service to `Plugin::$services` and run `composer dump-autoload` so Composer discovers the new class. Source classes use `class-` followed by the lowercase class name; interfaces use `interface-` followed by the lowercase interface name. PHPUnit tests retain their standard `*Test.php` filenames and PSR-4 loading; the filename check excludes the test directory. Add each generated block to the `entry` map in `webpack.config.js`:

```js
'blocks/testimonial/index': path.resolve(process.cwd(), 'blocks/testimonial/index.js'),
```

Every built `block.json` under `build/blocks/` is then registered automatically by PHP.

## Daily commands

```bash
npm run start          # watch JavaScript and Sass
npm run build          # production assets
npm run lint:js
npm run lint:css
composer lint          # WordPress Coding Standards
composer lint:fix
composer analyse       # PHPStan level 8
composer test          # PHPUnit
composer check         # all PHP checks
```

## Security conventions

- Every REST route must have a `permission_callback`.
- Use capabilities, not roles, for authorisation.
- Nonces protect against CSRF; they are not permission checks.
- Sanitise input, validate business rules, and escape at output.
- Use `$wpdb->prepare()` for dynamic SQL and prefer core APIs.
- Never put secrets in JavaScript, source control or Dev Container files.

The status route is intentionally public. Replace `__return_true` for private or mutating routes.

## Data lifecycle

Activation registers content types before flushing rewrite rules. Deactivation does not delete data. `uninstall.php` keeps data by default; explicitly add cleanup only when the product's retention policy requires it.

## Testing and CI

Unit tests use PHPUnit and Brain Monkey. Add the WordPress integration test suite or end-to-end tests for core-dependent behaviour rather than over-mocking it.

CI tests PHP 8.1 and 8.3, runs WPCS, PHPStan and PHPUnit, then checks and builds browser assets. Commit `package-lock.json` after the first install so `npm ci` is reproducible. Consider committing `composer.lock` for a shipped plugin.

## Package a release

```bash
composer install --no-dev --classmap-authoritative
npm ci
npm run build
./bin/package-plugin acme-search
```

The ZIP appears in `dist/`. The default `.distignore` excludes source, tests and development configuration while retaining runtime Composer files and compiled assets.

## Before shipping

1. Review headers, versions, URLs, author, licence and text domain.
2. Remove unused examples and registrations.
3. Decide whether built assets and `vendor/` are committed or made only for releases.
4. Add integration/e2e coverage for critical behaviour.
5. Test activation, deactivation, uninstall, multisite and oldest supported versions.
6. Review accessibility, translations, permissions, privacy and retention.
7. Run all checks, a production build and a clean-install smoke test.

## Design choices

- Explicit services instead of a container: less magic and easier debugging.
- Composer classmap loading supports WordPress class filenames (for example, `class-exampleposttype.php`) without manual includes.
- `block.json` as the block source of truth.
- No database abstraction or framework until complexity proves a need.
- No automatic deletion: data loss must be a conscious decision.

## Licence

GPL-2.0-or-later. Replace the placeholder brand and metadata for each project.

## Composer autoloader collisions

Each plugin must have its own generated Composer bootstrap class. This boilerplate sets `config.autoloader-suffix` to `modern_plugin`; the initializer changes it to match your new plugin slug. After copying or renaming a plugin, regenerate its autoloader with `composer install` (or `composer dump-autoload` when dependencies are already installed). Do not reuse another plugin's generated `vendor/` autoloader unchanged. A `Cannot redeclare class ComposerAutoloaderInit...` fatal indicates colliding bootstrap classes.

## Shared settings message

A non-empty message saved under **Settings → Modern Plugin** takes precedence in the action hook, shortcode and dynamic block output. Changes apply on the next uncached page render. If the setting is blank, the action and shortcode use their default greeting and each block uses its own message attribute. The block editor's editable message is this per-block fallback; the public output uses the shared setting when populated.

## GitHub Releases

Included in the codebase is the plugin-update-checked code. This allows tagged version from the github repository to trigger the WordPress update availability function. For this to work you must define the following constants in the wp-config.php file:-

`define( 'WPPB_CI_CD_PLUGIN_GIT_URL', '' );`  
`define( 'WPPB_CI_CD_PLUGIN_SLUG', '' );`  
`define( 'WPPB_CI_CD_PLUGIN_GIT_RELEASE_TOKEN', '' );`

The release token is generated from the github account by going to : Settings > Developer Settings > Personal Access Tokens > Fine-grained Tokens. Click the 'Generate New Token' button and complete the form. N.B. Permissions for the token should just be read-only on code and meta data (Content).

https://github.com/YahnisElsts/plugin-update-checker

## Changelog

`1.0.0`
* 7th Sept 2026 - Initial release.