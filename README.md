# Create entries in a form for each hook in a project

## Setup
1. Download https://github.com/jrfnl/wp-hook-documentor and drop it in your WordPress plugins folder
2. Download and activate this plugin
3. The form and field ids are currently hardcoded. Create your form and set the correct ids.

## Use
`s11_hooks_to_forms('plugin-folder-name');`

For bigger projects, you may need to check smaller chunks.

`s11_hooks_to_forms('formidable/classes');`

`s11_hooks_to_forms('formidable/pro');`
