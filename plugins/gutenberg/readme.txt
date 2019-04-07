=== Gutenberg ===
Contributors: matveb, joen, karmatosed
Requires at least: 5.0.0
Tested up to: 5.1
Stable tag: 5.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A new editing experience for WordPress is in the works, with the goal of making it easier than ever to make your words, pictures, and layout look just right. This is the beta plugin for the project.

== Description ==

Gutenberg is more than an editor. While the editor is the focus right now, the project will ultimately impact the entire publishing experience including customization (the next focus area).

<a href="https://wordpress.org/gutenberg">Discover more about the project</a>.

= Editing focus =

> The editor will create a new page- and post-building experience that makes writing rich posts effortless, and has “blocks” to make it easy what today might take shortcodes, custom HTML, or “mystery meat” embed discovery. — Matt Mullenweg

One thing that sets WordPress apart from other systems is that it allows you to create as rich a post layout as you can imagine -- but only if you know HTML and CSS and build your own custom theme. By thinking of the editor as a tool to let you write rich posts and create beautiful layouts, we can transform WordPress into something users _love_ WordPress, as opposed something they pick it because it's what everyone else uses.

Gutenberg looks at the editor as more than a content field, revisiting a layout that has been largely unchanged for almost a decade.This allows us to holistically design a modern editing experience and build a foundation for things to come.

Here's why we're looking at the whole editing screen, as opposed to just the content field:

1. The block unifies multiple interfaces. If we add that on top of the existing interface, it would _add_ complexity, as opposed to remove it.
2. By revisiting the interface, we can modernize the writing, editing, and publishing experience, with usability and simplicity in mind, benefitting both new and casual users.
3. When singular block interface takes center stage, it demonstrates a clear path forward for developers to create premium blocks, superior to both shortcodes and widgets.
4. Considering the whole interface lays a solid foundation for the next focus, full site customization.
5. Looking at the full editor screen also gives us the opportunity to drastically modernize the foundation, and take steps towards a more fluid and JavaScript powered future that fully leverages the WordPress REST API.

= Blocks =

Blocks are the unifying evolution of what is now covered, in different ways, by shortcodes, embeds, widgets, post formats, custom post types, theme options, meta-boxes, and other formatting elements. They embrace the breadth of functionality WordPress is capable of, with the clarity of a consistent user experience.

Imagine a custom “employee” block that a client can drag to an About page to automatically display a picture, name, and bio. A whole universe of plugins that all extend WordPress in the same way. Simplified menus and widgets. Users who can instantly understand and use WordPress  -- and 90% of plugins. This will allow you to easily compose beautiful posts like <a href="http://moc.co/sandbox/example-post/">this example</a>.

Check out the <a href="https://wordpress.org/gutenberg/handbook/reference/faq/">FAQ</a> for answers to the most common questions about the project.

= Compatibility =

Posts are backwards compatible, and shortcodes will still work. We are continuously exploring how highly-tailored metaboxes can be accommodated, and are looking at solutions ranging from a plugin to disable Gutenberg to automatically detecting whether to load Gutenberg or not. While we want to make sure the new editing experience from writing to publishing is user-friendly, we’re committed to finding  a good solution for highly-tailored existing sites.

= The stages of Gutenberg =

Gutenberg has three planned stages. The first, aimed for inclusion in WordPress 5.0, focuses on the post editing experience and the implementation of blocks. This initial phase focuses on a content-first approach. The use of blocks, as detailed above, allows you to focus on how your content will look without the distraction of other configuration options. This ultimately will help all users present their content in a way that is engaging, direct, and visual.

These foundational elements will pave the way for stages two and three, planned for the next year, to go beyond the post into page templates and ultimately, full site customization.

Gutenberg is a big change, and there will be ways to ensure that existing functionality (like shortcodes and meta-boxes) continue to work while allowing developers the time and paths to transition effectively. Ultimately, it will open new opportunities for plugin and theme developers to better serve users through a more engaging and visual experience that takes advantage of a toolset supported by core.

= Contributors =

Gutenberg is built by many contributors and volunteers. Please see the full list in <a href="https://github.com/WordPress/gutenberg/blob/master/CONTRIBUTORS.md">CONTRIBUTORS.md</a>.

== Frequently Asked Questions ==

= How can I send feedback or get help with a bug? =

We'd love to hear your bug reports, feature suggestions and any other feedback! Please head over to <a href="https://github.com/WordPress/gutenberg/issues">the GitHub issues page</a> to search for existing issues or open a new one. While we'll try to triage issues reported here on the plugin forum, you'll get a faster response (and reduce duplication of effort) by keeping everything centralized in the GitHub repository.

= How can I contribute? =

We’re calling this editor project "Gutenberg" because it's a big undertaking. We are working on it every day in GitHub, and we'd love your help building it.You’re also welcome to give feedback, the easiest is to join us in <a href="https://make.wordpress.org/chat/">our Slack channel</a>, `#core-editor`.

See also <a href="https://github.com/WordPress/gutenberg/blob/master/CONTRIBUTING.md">CONTRIBUTING.md</a>.

= Where can I read more about Gutenberg? =

- <a href="http://matiasventura.com/post/gutenberg-or-the-ship-of-theseus/">Gutenberg, or the Ship of Theseus</a>, with examples of what Gutenberg might do in the future
- <a href="https://make.wordpress.org/core/2017/01/17/editor-technical-overview/">Editor Technical Overview</a>
- <a href="https://wordpress.org/gutenberg/handbook/reference/design-principles/">Design Principles and block design best practices</a>
- <a href="https://github.com/Automattic/wp-post-grammar">WP Post Grammar Parser</a>
- <a href="https://make.wordpress.org/core/tag/gutenberg/">Development updates on make.wordpress.org</a>
- <a href="https://wordpress.org/gutenberg/handbook/">Documentation: Creating Blocks, Reference, and Guidelines</a>
- <a href="https://wordpress.org/gutenberg/handbook/reference/faq/">Additional frequently asked questions</a>


== Changelog ==

For 5.4.0.

= Features =

- Add [vertical alignment support for the columns](https://github.com/WordPress/gutenberg/pull/13899) [block](https://github.com/WordPress/gutenberg/pull/14614).
- Add [playsinline support](https://github.com/WordPress/gutenberg/pull/14500) for the video block.

= Enhancements =

- Add the [Media Library button](https://github.com/WordPress/gutenberg/issues/8309) to the gallery block appender.
- Improve appearance of the block [hover state on colored backgrounds](https://github.com/WordPress/gutenberg/pull/14501).
- Move the [color and font size caption styles](https://github.com/WordPress/gutenberg/pull/14366) into theme styles.
- Replace the [verse block icon](https://github.com/WordPress/gutenberg/pull/14622).
- Standardize [align and className attributes](https://github.com/WordPress/gutenberg/pull/14533) for dynamic blocks.
- Remove the [title from mobile inserters](https://github.com/WordPress/gutenberg/pull/14493).
- Capitalize [button labels](https://github.com/WordPress/gutenberg/pull/14591).
- [Remove menu toggling](https://github.com/WordPress/gutenberg/pull/14456) on checkbox, radio buttons clicks.
- Make the [invisible image resize handlers](https://github.com/WordPress/gutenberg/pull/14481) bigger.
- Improve the [alt text field description](https://github.com/WordPress/gutenberg/pull/14668).

= Bug Fixes =

- Improve the [format boundary styles](https://github.com/WordPress/gutenberg/pull/14519).
- [Convert void blocks properly](https://github.com/WordPress/gutenberg/pull/14536) when converting or pasting content.
- Fix the [ClipboardButton](https://github.com/WordPress/gutenberg/pull/7106) component behavior in Safari.
- Fix [expanding the text selection](https://github.com/WordPress/gutenberg/pull/14487) when using shift + vertical arrows.
- Fix the alignment of the [third-party block settings items](https://github.com/WordPress/gutenberg/pull/14569).
- Fix [invalid HTML](https://github.com/WordPress/gutenberg/pull/14423) [in](https://github.com/WordPress/gutenberg/pull/14599) some more menu items.
- Fix [JavaScript error in the columns](https://github.com/WordPress/gutenberg/pull/14605) block.
- Fix [radio button](https://github.com/WordPress/gutenberg/pull/14624) [appearance](https://github.com/WordPress/gutenberg/pull/14684) on small screens.
- Save [line breaks in the preformatted](https://github.com/WordPress/gutenberg/pull/14653) block.
- Fix edge case in the [is_gutenberg_page](https://github.com/WordPress/gutenberg/pull/14558) plugin function.
- Fix [toolbar position](https://github.com/WordPress/gutenberg/pull/14669) in full size aligned blocks on small screens.
- Fix [double scrollbar issue](https://github.com/WordPress/gutenberg/pull/14677) in Full Screen mode.
- Fix JavaScript error when [downgrading to an old Gutenberg version](https://github.com/WordPress/gutenberg/pull/14691).
- Fix the [WordPress embed block](https://github.com/WordPress/gutenberg/pull/14658) resolution.
- Fix embedding [links with trailing slashes](https://github.com/WordPress/gutenberg/pull/14705).
- Fix the [preloading apiFetch middleware](https://github.com/WordPress/gutenberg/pull/14714) when initialized empty.
- Fix php notice when using [widgets without](https://github.com/WordPress/gutenberg/pull/14587) [description](https://github.com/WordPress/gutenberg/pull/14615).
- Better [horizontal edge detection](https://github.com/WordPress/gutenberg/pull/14462) to fix the arrow key navigation in the table block.
- Fix error in [API Fetch initialization](https://github.com/WordPress/gutenberg/pull/14714).
- Fix [unwanted margin](https://github.com/WordPress/gutenberg/pull/14614) in Column block.
- Fixes [issue where emoji would be destroyed](https://github.com/WordPress/gutenberg/pull/14411).

= Documentation =

- Document the [block editor module](https://github.com/WordPress/gutenberg/pull/14566).
- Add design documentation for the [Panel](https://github.com/WordPress/gutenberg/pull/14504) component.
- Document [webpack config extensibility](https://github.com/WordPress/gutenberg/pull/14590).
- Clarify [experimental and unstable API](https://github.com/WordPress/gutenberg/pull/14557) guidelines.
- Setup automatic [API documentation for the data](https://github.com/WordPress/gutenberg/pull/14277) module.
- Improve the [automatic](https://github.com/WordPress/gutenberg/pull/14549) [API](https://github.com/WordPress/gutenberg/pull/14656) documentation tool.
- Enhance the components documentation:
  - [FormFileUpload](https://github.com/WordPress/gutenberg/pull/14661) component.
  - [MediaPlaceholder](https://github.com/WordPress/gutenberg/pull/14645) component.
  - [Notice](https://github.com/WordPress/gutenberg/pull/14514) component.
  - [TextControl](https://github.com/WordPress/gutenberg/pull/14710) component.
- Updates the [blocks creation tutorial](https://github.com/WordPress/gutenberg/pull/14584).
- Typos & tweaks: [1](https://github.com/WordPress/gutenberg/pull/14490), [2](https://github.com/WordPress/gutenberg/pull/14516), [3](https://github.com/WordPress/gutenberg/pull/14530), [4](https://github.com/WordPress/gutenberg/pull/14565), [5](https://github.com/WordPress/gutenberg/pull/14597), [6](https://github.com/WordPress/gutenberg/pull/14368), [7](https://github.com/WordPress/gutenberg/pull/14666), [8](https://github.com/WordPress/gutenberg/pull/14686), [9](https://github.com/WordPress/gutenberg/pull/14690).

= Various =

- Implement a built-in static [Gutenberg Playground](https://github.com/WordPress/gutenberg/pull/14497).
- [Override core block server-side code](https://github.com/WordPress/gutenberg/pull/13521) when using the plugin.
- Make the [block](https://github.com/WordPress/gutenberg/pull/14527) [editor](https://github.com/WordPress/gutenberg/pull/14387) [module](https://github.com/WordPress/gutenberg/pull/14548) [more](https://github.com/WordPress/gutenberg/pull/14678) reusable.
- Expose the [lazy and Suspence](https://github.com/WordPress/gutenberg/pull/14412) React features in the element package.
- Avoid assuming persisted [preferences state shape](https://github.com/WordPress/gutenberg/pull/14692).
- Remove dead code from the [calendar block renderer](https://github.com/WordPress/gutenberg/pull/14546).
- Extract [global CSS resets](https://github.com/WordPress/gutenberg/pull/14509) [into](https://github.com/WordPress/gutenberg/pull/14572) reusable mixins.
- Replace [image urls by base 64 encoded images](https://github.com/WordPress/gutenberg/pull/14544) in reusable CSS files.
- Add default empty implementation for the [block types save](https://github.com/WordPress/gutenberg/pull/14510) [function](https://github.com/WordPress/gutenberg/pull/14529).
- Add a new data action to [replace the inner blocks](https://github.com/WordPress/gutenberg/pull/14291).
- Support [parent data registry inheritance](https://github.com/WordPress/gutenberg/pull/14369) in the data module.
- Add [extra props support for the Dashicon](https://github.com/WordPress/gutenberg/pull/14631) component.
- Add a [BaseControl.VisualLabel](https://github.com/WordPress/gutenberg/pull/14179) component for purely visual labels.
- Refactor [setupEditor effects to actions](https://github.com/WordPress/gutenberg/pull/14513).
- Refactor the [core/data store](https://github.com/WordPress/gutenberg/pull/14634) to be independent from the registry object.
- Remove [componentWillMount](https://github.com/WordPress/gutenberg/pull/14637) usage from LatestPostEdit component.
- Add a generic [e2e test for block transforms](https://github.com/WordPress/gutenberg/pull/12336) and work on its [stability](https://github.com/WordPress/gutenberg/pull/14632).
- Allow e2e test failures for [php versions lower than 5.6](https://github.com/WordPress/gutenberg/pull/14541).
- Add eslint rule to prevent [incorrect truthy length property](https://github.com/WordPress/gutenberg/pull/14579/) checks.
- Add eslint rule to [prevent unsafe setTimeout usage](https://github.com/WordPress/gutenberg/pull/14650) in components.
- Run the local gutenberg environment in [debug mode](https://github.com/WordPress/gutenberg/pull/14371).
- Disable [debug mode in local e2e tests](https://github.com/WordPress/gutenberg/pull/14638).
- [Exclude test files](https://github.com/WordPress/gutenberg/pull/14468) while rebuilding packages.
- [Make E2E tests resilient](https://github.com/WordPress/gutenberg/pull/14632) against transforms added by plugins.
- Add [LGPL](https://github.com/WordPress/gutenberg/pull/14734) as an OSS license.

