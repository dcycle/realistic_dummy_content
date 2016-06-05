These files are to be copied to the container and include some configuration files such
as composer, etc.

These will be copied one by one for finer control on caching.

These come from the tutorial "Unit Testing Tutorial Part I: Introduction to PHPUnit" on the website, Juan Treminio, March 2013 [1]

A note about the PHPUnit being used
-----

We are using > 5.0 at the time of this writing, because 5.0 has an issue [2] which prevents the generation of coverage reports.

-----

[1] https://jtreminio.com/2013/03/unit-testing-tutorial-introduction-to-phpunit/
[2] https://github.com/sebastianbergmann/phpunit/issues/1872
