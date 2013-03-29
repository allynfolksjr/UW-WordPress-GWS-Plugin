# Authentication and Restriction plugin for WordPress

This plugin is designed to provide a number of desired features to our WordPress instance. 

## Current features include:


## Planned Improvements Include:

* Ability to restrict entire site to particular NetIDs
* Ability to restrict specified pages to particular NetIDs
* Ability to restrict specific posts to particular NetIDs
* Ability to restrict specific categories to particular NetIDs
* Ability to use UW Groups service to set restrictions instead of specififying specific NetIDs

## What this plugin doesn't do:

* Restrict media content. If a user knows the direct URL to media, they can access it without needing to
authenticate. 

=======
UW-WordPress-GWS-Plugin
=======================

Plugin to allow Shibboleth-authenticated WP instances to consume user groups in GWS.

What is this?
-------------

The University of Washington has the confluence of the following attributes

* We have lots of WordPress instances.
* We use the http-authentication plugin to authenticate users with WordPress, a lot.
* We have an awesome Groups Web Service (GWS), that enables centralized group management.
* We support Shibboleth for authentication, and Shibboleth can send a user's groups to an application.
* WordPress, via HTTP-Authentication, can consume these attributes.

The goal is to allow WordPress to be able to view and display a user's groups, and store (well, cache until next login) their groups locally this enables all sorts of fun possibilities, including:

* View-protect blog or page natively in WordPress, using groups as the gatekeepers.
* Assign groups to roles (eg: all members of uw_super_awesome_squad are also editors)

Warning
-------

This project is so experimental it's not even funny. I assume you:

* Either have or know how to get a shibboleth-enabled IDP
* Know what the UW Group Web Service is
* Are familiar with WordPress. Or even super familiar with WordPress.
* Like mucking around with configuration files on servers.
* Really like Apache
* Can troubleshoot like a superstar.
* Enjoy watching things break.
