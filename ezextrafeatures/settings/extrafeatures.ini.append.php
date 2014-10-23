<?php /* #?ini charset="utf-8"?

# AuthorizedFunction can be:
# a function like AuthorizedFunctions[]=str_replace
# a class method like AuthorizedFunctions[]=DateTime::createFromFormat
# a class method for a namespace like AuthorizedFunctions[]=DateTime::createFromFormat
# Note: for class method, the method should be static
[eZPHPFunc]
AuthorizedFunctions[]
AuthorizedFunctions[]=str_replace
AuthorizedFunctions[]=strip_tags
AuthorizedFunctions[]=var_dump
AuthorizedFunctions[]=DateTime::createFromFormat

*/ ?>
