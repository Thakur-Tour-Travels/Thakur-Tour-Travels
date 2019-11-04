/*
[Main Script] Copyright © 2016
Title      : SimonCabs - Responsive Taxi Booking HTML Template
Version    : 1.0
Author     : ConquerorsMarket
Author URL : https://conquerorsmarket.com
Support    : conquerorsmarket@gmail.com
*/
/*jslint browser: true*/
/*global $, jQuery, alert*/

/*--------------------------------------------------------------
TABLE OF CONTENTS:
----------------------------------------------------------------
# Document Ready
## Vars
## Page Pre Loading
## Navbar Sticky
## Grid List View
## Team Slider - SlickSlider
## Vehicles Galley - SlickSlider
## Mobile Navigation Menu

--------------------------------------------------------------*/

/* Document Ready */

jQuery(document).ready(function ($) {

	"use strict";

	// Vars
	var $products_item,
		$pw_navbar,
		$datepicker;

	// Page pre loader
	$(window).load(function () {
		$('#status').fadeOut();
		$('#preloader').delay(250).fadeOut('slow');
	});

	// Navbar sticky
	$(window).scroll(function () {
		$pw_navbar = $('.st-navbar-default');
		if ($($pw_navbar).offset().top > 50) {
			$($pw_navbar).addClass("st-nav-collapse");
		} else {
			$($pw_navbar).removeClass("st-nav-collapse");
		}
	});

	// Grid list view
	$products_item = $('.st-item');
	$('#st-list').on("click", function (event) {
		event.preventDefault();
		$products_item.addClass('st-list-view');
		$(this).addClass('active');
		$('#st-grid').removeClass('active');

	});
	$('#st-grid').on("click", function (event) {
		event.preventDefault();
		$products_item.removeClass('st-list-view');
		$(this).addClass('active');
		$('#st-list').removeClass('active');
	});

	// Add class on click on radio button
	$(':radio').change(function () {
		$(':radio[name=' + this.name + ']').closest('tr').removeClass('st-cab-select');
		$(this).closest('tr').addClass('st-cab-select');
	});

	// Team slider (slickslider)
	$('.st-team-slider').slick({
		infinite: true,
		autoplay: true,
		speed: 300,
		slidesToShow: 2,
		slidesToScroll: 2,
		arrows: false,
		dots: false,
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2,
					infinite: true,
					dots: false
				}
			},
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2,
					dots: true
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					dots: true
				}
			}
		]
	});

	// Vehicles galley slider (slickslider)
	$('.st-slider-for').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		arrows: true,
		fade: true,
		dots: false,
		asNavFor: '.st-slider-nav'
	});
	$('.st-slider-nav').slick({
		slidesToShow: 5,
		slidesToScroll: 1,
		asNavFor: '.st-slider-for',
		dots: false,
		arrows: false,
		focusOnSelect: true
	});

	// Mobile navigation menu
	$('.stellarnav').stellarNav({
		theme: 'light',
		sticky: true,
		breakpoint: 768,
		position: 'static'
	});
});
