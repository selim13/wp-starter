<?php

use Extended\ACF\Location;
use Extended\ACF\Fields\Text;

return [
  'title' => 'Front page',
  'key' => 'front_page',
  'location' => [
    Location::where('page_type', 'front_page'),
  ],
  'position' => 'acf_after_title',
  'hide_on_screen' => ['the_content'],
  'style' => 'default',
  'fields' => [
    Text::make('Example field', 'example_field')
  ],
];
