Realistic dummy content
=======================

generate kick-ass realistic demo content with [Devel](https://drupal.org/project/devel)'s `devel_generate` module.

Usage
-----

Enable this module and [Devel](https://drupal.org/project/devel)'s `devel_generate`. You will now see portraits used for profile pictures, and stock photos instead of the color blocks generated by `devel_generate`. All images included in this module are freely licensed (see the README.txt in the directories containing the images).

Extending this module
-----

This project contains two modules:

 * Realistic Dummy Content API (realistic\_dummy\_content\_api), which looks inside every enabled module for files which contain images or text, and replaces available fields.

 * Realistic Dummy Content (realistic\_dummy\_content), which replaces user pictures and node article images with portraits and stock photography. You can reproduce the `realistic_dummy_content/realistic_dummy_content` directory structure in your own modules as `my_custom_module/realistic_dummy_content` for better control of the realistic dummy content you want to generate. If you don't want the example stock images that ship with this module, you can disable Realistic Dummy Content (realistic\_dummy\_content) and leave Realistic Dummy Content API (realistic\_dummy\_content\_api) enabled.

Developers can also extend Realistic Dummy Content by implementing hooks defined in `api/realistic_dummy_content_api.api.php`. Specifically, if you want to be able to define realistic dummy content for a custom field type and the standard technique is not working, you can submit an issue or patch to the [issue queue](https://drupal.org/project/issues/2253941?categories=All) for this module; but you can also implement the field modifier yourself by looking at Realistic Dummy Content API's implementation of `hook_realistic_dummy_content_attribute_manipulator_alter()`, and the classes which are referenced from there.

Creating recipes
-----

Often, sites require a set number of entities to be created in a specific sequence. For example, if your site defines schools which have [entity references](https://www.drupal.org/project/entityreference) to school boards, a realistic scenario may be to generate 3 school boards followed by 20 schools. You can define this type of recipe for your [site deployment module](http://dcycleproject.org/blog/44/what-site-deployment-module) (or any module), by creating a file called `./sites/*/modules/mymodule/realistic_dummy_content/recipe/mymodule.recipe.inc`. [An example is included herein](http://cgit.drupalcode.org/realistic_dummy_content/tree/realistic_dummy_content/recipe/realistic_dummy_content.recipe.inc).

Field meta data
-----

Some fields have special meta data: body fields can have input formats in addition to body text; image fields can have alt text in addition to the image. This can be achieved using a specific naming scheme, and you will find an example in the enclosed data, which looks like:

    realistic_dummy_content/fields/node/article/
      - body/
        - ipsum.txt
        - ipsum.txt.format.txt
        - lorem.txt
     - field_image/
        - 1.jpg
        - 2.jpg
        - 2.jpg.alt.txt

In the above example, `realistic_dummy_content` sees two possible body values, _one of which with a specific input format_; and two possible images, _one of which with a specific alt text_. Meta data is never compulsory, and in the case where a meta attribute is needed, a reasonable fallback value is used, for example `filtered_html` will be used if no format is specified for the body.

Issue queue and pull requests
-----

See the [issue queue](https://drupal.org/project/issues/2253941?categories=All) if you have questions, bug reports or feature requests.

Pull requests can be filed against the [GitHub repo](https://github.com/dcycle/realistic_dummy_content).

Drupal 7 and Drupal 8 in one "universal" codebase
-----

The 7.x-2.x and 8.x-2.x codebases are identical and both work on Drupal 7 or Drupal 8 sites. A blog post is planned on [Dcycle](http://dcycleproject.org/) to explain how this works.

Docker integration
-----

To test this module you can run:

    cd ./development && ./test.sh

To create a development environment for Drupal 7, make sure you have Docker installed (for example on a CoreOS vagrant machine on Mac OS), then you can run:

    cd ./development && ./build-dev-environment.sh

This will install two development environments, one for Drupal 7 and one for Drupal 8. When you change any code, it will reflected in both your environments in real time.

For more information see [A quick intro to Docker for a Drupal project (Dcycle Project, Feb. 18, 2015)](http://dcycleproject.org/blog/91/quick-intro-docker-drupal-project). These scripts are meant to be used with [Docker](https://www.docker.com) and [CoreOS](https://coreos.com).

Continuous integration with Circle CI
-----

[CircleCI](https://circleci.com/gh/alberto56/realistic_dummy_content) is a continuous integration platform for Drupal projects. In [Continuous integration with Circle CI and Docker for your Drupal project (Dcycle project, Feb. 20, 2015)](http://dcycleproject.org/blog/92/continuous-integration-circle-ci-and-docker-your-drupal-project), I documented how and why I set up continuous integration with Circle CI and Docker for Realistic Dummy Content. Because the 8.x-2.x uses Docker's `exec` function which [Circle CI does not support](http://stackoverflow.com/questions/30970969/exec-is-not-supported-by-the-lxc-driver-how-to-get-around-this), I am using [Travis CI](https://travis-ci.org/alberto56/realistic_dummy_content) for continuous integration of the 8.x-2.x branch.

# See http://stackoverflow.com/questions/30970969/exec-is-not-supported-by-the-lxc-driver-how-to-get-around-this
8.x-2.x branch:
[![Build Status](https://travis-ci.org/alberto56/realistic_dummy_content.svg?branch=8.x-2.x)](https://travis-ci.org/alberto56/realistic_dummy_content)
7.x-1.x branch: [![CircleCI](https://circleci.com/gh/alberto56/realistic_dummy_content/tree/7.x-1.x.svg?style=svg)](https://circleci.com/gh/alberto56/realistic_dummy_content/tree/7.x-1.x)

Sponsors
-----

 * [Dcycle](http://dcycle.com/) (Current)
 * [The Linux Foundation](http://www.linuxfoundation.org/) (previous)  
 * [CGI](http://cgi.com/) (Initial development)
