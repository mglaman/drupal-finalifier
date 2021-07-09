# drupal-finalifier

FOR THE FINALIFIERING OF DRUPAL

## What?!?!!

See https://www.drupal.org/project/drupal/issues/3019332

If we marked classes in Drupal core as `final`, it'd reduce the amount of backwards compatibility concerns and issues with constructors.

This script uses `nikic/php-parser` to find classes in Drupal core that:

* Are not abstract
* No not extend other classes
* Are not aready final

These are candidates for `final`.

THEN those candidates are checked against other modules.

See `resources` for the candidates and the conflicts if ran against Commerce & its dependencies.
