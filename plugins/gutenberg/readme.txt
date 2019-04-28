=== Gutenberg ===
Contributors: matveb, joen, karmatosed
Requires at least: 5.0.0
Tested up to: 5.1
Stable tag: 5.5.0
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

For 5.5.0.

= Features =

- Add a new [Group](https://github.com/WordPress/gutenberg/pull/13964) [block](https://github.com/WordPress/gutenberg/pull/14920).
- Add [vertical alignment](https://github.com/WordPress/gutenberg/pull/13989) support to the Media & Text block.
- Add [the image fill option](https://github.com/WordPress/gutenberg/pull/14445) to the Media & Text block.

= Enhancements =

- Improvements to the [Image Block](https://github.com/WordPress/gutenberg/pull/14142) [flows](https://github.com/WordPress/gutenberg/pull/14807).
- Automatically [add `mailto:` to email addresses](https://github.com/WordPress/gutenberg/pull/14857) when linking.
- Add [visual handles for side resizers](https://github.com/WordPress/gutenberg/pull/14543) for various blocks.
- Improve the [performance of the](https://github.com/WordPress/gutenberg/pull/14664) [annotations](https://github.com/WordPress/gutenberg/pull/14808) by avoiding excessive memoization.
- Announce the [color accessibility issues](https://github.com/WordPress/gutenberg/pull/14649) to screen readers.
- Add [enum block attributes validation](https://github.com/WordPress/gutenberg/pull/14810) to browser block parser.
- Use a [consistent grey background](https://github.com/WordPress/gutenberg/pull/14719) in the Shortcode block.
- Improve accessibility of video block [select poster image](https://github.com/WordPress/gutenberg/pull/14752).
- Respect [prefers-reduced-motion for fixed backgrounds](https://github.com/WordPress/gutenberg/pull/14848) in Cover block.
- Prevent ArrowLeft key press in multi-line selection from [prematurely triggering multi-selection](https://github.com/WordPress/gutenberg/pull/14906).

= Bug Fixes =

- Avoid keeping the [RichText value in cache](https://github.com/WordPress/gutenberg/pull/14750) indefinitely.
- Fix the [post title input borders](https://github.com/WordPress/gutenberg/pull/14771) in the code editor.
- Fix the [block restrictions](https://github.com/WordPress/gutenberg/pull/14003) to insert, replace or move blocks.
- [Select gallery images](https://github.com/WordPress/gutenberg/pull/14813) on focus.
- Fix [removing gallery images](https://github.com/WordPress/gutenberg/pull/14822) on delete/backspace key press.
- Fix small visual regression in the [block autocompete popover](https://github.com/WordPress/gutenberg/pull/14772).
- Fix the data module[ resolver resolution status](https://github.com/WordPress/gutenberg/pull/14711).
- Avoid [saving metaboxes when previewing](https://github.com/WordPress/gutenberg/pull/14877) [changes](https://github.com/WordPress/gutenberg/pull/14894).
- Fix [selecting the separator block](https://github.com/WordPress/gutenberg/pull/14854).
- Fix displaying the [color palette](https://github.com/WordPress/gutenberg/pull/14693) [tooltips](https://github.com/WordPress/gutenberg/pull/14944) on hover.
- Fix Firefox/NVDA bug not [announcing the toggle settings](https://github.com/WordPress/gutenberg/pull/14475) button.
- Fix [arrow navigation in paragraph](https://github.com/WordPress/gutenberg/pull/14804) blocks with backgrounds.
- Fix the [columns block click to select](https://github.com/WordPress/gutenberg/pull/14876).
- Fix [post dirtiness](https://github.com/WordPress/gutenberg/pull/14916) after fetching reusable blocks.
- Fix the hover and focus styles for [buttons with the isBusy](https://github.com/WordPress/gutenberg/pull/14469) prop.
- Remove the box [shadow from the side inserter](https://github.com/WordPress/gutenberg/pull/14936) button.
- Changing the [region navigation shortcuts](https://github.com/WordPress/gutenberg/pull/14681) to avoid conflicts.
- Fix [space insertion in the Button block](https://github.com/WordPress/gutenberg/pull/14925) in Firefox.
- Prevent the [link popover from animating constantly](https://github.com/WordPress/gutenberg/pull/14938) as we type. 
- Fix the warning triggered by [clearing the height of the spacer block](https://github.com/WordPress/gutenberg/pull/14785).
- Fix [undo behavior](https://github.com/WordPress/gutenberg/pull/14955) after fresh post loading.

= Documentation =

- Add design documentation to the [Modal component](https://github.com/WordPress/gutenberg/pull/14757).
- Clarify the [CSS naming coding guidelines](https://github.com/WordPress/gutenberg/pull/14556).
- Add [InspectorControls usage example](https://github.com/WordPress/gutenberg/pull/11736).
- Tweaks and typos: [1](https://github.com/WordPress/gutenberg/pull/14736), [2](https://github.com/WordPress/gutenberg/pull/14737), [3](https://github.com/WordPress/gutenberg/pull/14762), [4](https://github.com/WordPress/gutenberg/pull/14741), [5](https://github.com/WordPress/gutenberg/pull/14756), [6](https://github.com/WordPress/gutenberg/pull/14778), [7](https://github.com/WordPress/gutenberg/pull/14827), [8](https://github.com/WordPress/gutenberg/pull/14895), [9](https://github.com/WordPress/gutenberg/pull/14909), [10](https://github.com/WordPress/gutenberg/pull/14917), [11](https://github.com/WordPress/gutenberg/pull/14940), [12](https://github.com/WordPress/gutenberg/pull/14941), [13](https://github.com/WordPress/gutenberg/pull/14964).

= Various =

- Bootstrap the design of the [new widgets screen](https://github.com/WordPress/gutenberg/pull/14612) (non functional yet).
- Reorganization of the block-library code base:
    - Use a babel plugin to [load block.json files](https://github.com/WordPress/gutenberg/pull/14551).
    - Introduce [block.json metadata](https://github.com/WordPress/gutenberg/pull/14770) [for](https://github.com/WordPress/gutenberg/pull/14863) all client side blocks.
    - Move [the edit functions and the icons](https://github.com/WordPress/gutenberg/pull/14743) to separate files.
    - Move the [transforms and save functions](https://github.com/WordPress/gutenberg/pull/14882) to separate files.
- Add [forwardRef support to the PlainText](https://github.com/WordPress/gutenberg/pull/14866) component.
- Support [Button Block Appender](https://github.com/WordPress/gutenberg/pull/14241) in the InnerBlocks component.
- [Unset the focal point attributes](https://github.com/WordPress/gutenberg/pull/14746) from the Cover block if not needed.
- [Consistently return promises](https://github.com/WordPress/gutenberg/pull/14830) from the data module action calls.
- Remove obsolete [CSS currentColor](https://github.com/WordPress/gutenberg/pull/14119) usage.
- Improve the [e2e test CLI arguments](https://github.com/WordPress/gutenberg/pull/14717) and docs.
- Improve the [e2e test login stability](https://github.com/WordPress/gutenberg/pull/14243) on MacOS.
- Remove [is-plain-obj package](https://github.com/WordPress/gutenberg/pull/14751) dependency.
- Remove invalid urls and ids from [media test](https://github.com/WordPress/gutenberg/pull/14625/files) [fixtures](https://github.com/WordPress/gutenberg/pull/14790).
- Remove the [deprecated Gutenberg plugin functions](https://github.com/WordPress/gutenberg/pull/14806) slated for 5.4 and 5.5.
- remove or rename [undocumented RichText package functions](https://github.com/WordPress/gutenberg/pull/14239) and constants.
- Adjust [paragraph block spacing](https://github.com/WordPress/gutenberg/pull/14679) to use standardised variables.
- Allow [spaces in file paths](https://github.com/WordPress/gutenberg/pull/14789) for package build process. 
- Update pre-commit to [check modified files only](https://github.com/WordPress/gutenberg/pull/14971).
- Improve the [performance of the webpack build](https://github.com/WordPress/gutenberg/pull/14860) configuration.
- [Update dependencies](https://github.com/WordPress/gutenberg/pull/14978) with known vulnerabilities.
- Fix [typo in variable names](https://github.com/WordPress/gutenberg/pull/14970).

= Mobile =

- [Accessibility improvements to the Button](https://github.com/WordPress/gutenberg/pull/14697) component.
- Enhance the [unsupported block type](https://github.com/WordPress/gutenberg/pull/14577). 
- Fix [image upload progress](https://github.com/WordPress/gutenberg/pull/14799) not being displayed consistently.
- Support [copy/pasting images](https://github.com/WordPress/gutenberg/pull/14802).
- Add [the](https://github.com/WordPress/gutenberg/pull/14865) [list block](https://github.com/WordPress/gutenberg/pull/14636).
- Visual [refinements for the native nextpage](https://github.com/WordPress/gutenberg/pull/14826) block.
- Fix [importing the column](https://github.com/WordPress/gutenberg/pull/14880) block.
- Remove DOM logic from the [list block toolbar](https://github.com/WordPress/gutenberg/pull/14840).
- Put the caret at the end of the text field after [merging blocks](https://github.com/WordPress/gutenberg/pull/14820).
- Fix caret position [after](https://github.com/WordPress/gutenberg/pull/14957) [inline paste](https://github.com/WordPress/gutenberg/pull/14893).