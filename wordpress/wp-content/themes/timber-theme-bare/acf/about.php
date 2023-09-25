<?php

use Extended\ACF\Location;
use Extended\ACF\Fields\Text;

return [
  'title' => 'About',
  'key' => 'about',
  'location' => [
    Location::where('page_template', 'templates/about.php'),
  ],
  'position' => 'acf_after_title',
  'hide_on_screen' => ['the_content'],
  'style' => 'default',
  'fields' => [
    Text::make('Example field', 'example_field')
  ],
];
