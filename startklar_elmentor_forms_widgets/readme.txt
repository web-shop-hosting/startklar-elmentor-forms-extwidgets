=== Startklar Elmentor Forms ExtWidgets ===

Tested up to: 5.8
Stable tag: 1.0
License: GPLv2 or later


Description.
The plugin is designed to expand the list of form fields for the Elementor-PRO Forms builder.
At the moment, the following fields of the builder forms are implemented:
- widget for choosing a telephone prefix depending on the country.

A widget for choosing a telephone prefix depending on the country.
    -Provides the ability to select a searchable telephone prefix.
    -The search works both by prefix number and by country name.
    -The standard for WP language file is used, then it allows to translate the selector depending on the change of the locale inside WP.
    -In the display of countries there is a flag image, which makes it easier to find and makes it richer. When displaying an empty value, 
        there is an automatic determination of the current country using geolocation based on IP identification.

Plugin installation.
Installation is done as standard for WP. It is important that the plugins “Elementor” and “Elementor PRO” must be installed and activated beforehand.

Plugin setup.
The setup interface is as simple and intuitive as possible.

If necessary, you can add a translation for the language you need. At the moment, the languages ​​English and Russian have been implemented. 
To add a new language, you need to copy the existing translation /wp-content/plugins/startklar_elmentor_forms_widgets/lang/ru_RU.po into the same folder, 
replacing the file name with the language code you need according to the ISO 639-1 standard. After that, we translate in this files the original values ​​that 
are located after the tag: msgid, the translation result is placed after the msgstr tag. An empty line must be skipped between translation elements.
After manually generating the translation file, you need to compile the * .po file into * .mo. You can learn more about how to do this from 
the documentation: https://wplang.org/translate-theme-plugin/

Usage.
Using the plugin is no different from using other form fields in the Elementor Forms Builder.