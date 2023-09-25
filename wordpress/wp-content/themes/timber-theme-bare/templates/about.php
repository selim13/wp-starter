<?php
/**
 * Template Name: About
 */

$context = Timber::context();

$timber_post = Timber::get_post();
$context['post'] = $timber_post;
Timber::render(['about.twig'], $context);
