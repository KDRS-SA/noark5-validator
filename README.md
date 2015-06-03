# noark5-validator
This code provides the basic ability to check a Noark 5 v3.1 extraction. Code is built so that it should be possible to add functionality for a later version of Noark 5 say v3.2 or v4

## DEPENDENCIES ##

The code has a dependency to the WordPHP library that is included with the code. We copied the library, we may later decide to just let it be a dependency. Only the PHP file Reportbuilder.php has a dependency to WordPHP so it can easily be removed if requred. This is just to create an OpenOffce/MS Office word file that shows the results of the validation.

If the inclusion of WordPHP (LGPL) affects the entire validator, then all the code is LGPL. The official license we are setting on this now is LGPL because of WordPHP, but if you remove that dependency, you can assume this code is a variant of BSD. We want this code to be used and reused so contact us if you need it under a particular open source license.