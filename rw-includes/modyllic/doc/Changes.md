Revision history for PHP module Modyllic

v0.2.4 2012-08-09

* Only wrap general errors for procs that we fetch the results on
* Fix colorize to make it handle commandline arguments in a standard way--
  the move to a unified modyllic commandline had broken it.
* Add an exception->error handler and made using the CommandLine class load
  it and the autoloader
* Fix case undefined indexes involving aliases (#186)
* Make boolean (and serial) persistent metadata in MySQL

v0.2.3 2012-07-30

* Fully automate the release process
* Move the tokenizer unit test into the unit tests directory
* Fix formatting of routine_args_type
* Fix formatting of proc_return_type and routine_args_type docs
* Give modyllic a single commandline interface
* Comments in tables can now attach to columns and the table
* Make tokens stringify to their debug representation and take advantage
* Fix normalization for dates and years the way we did for nums
* Fix #172 Non-token numification always set the value to 0
* Fix bug where data updates would get lost if a tables static status did not change
* Add coverage tools, a bunch of new unit tests, organize tests better.

v0.2.2 2012-05-17

* Add aliases for the old dialect names and warnings if you use them
* Fix method signature mismatches and bogus defaults
* Correct type hints from bulk type hint change

v0.2.1 2012-05-08

* Call CREATE TABLE correctly for SQLMETA
* Full support for 5.2-5.4 testing via Travis-CI
* Switch to using an auto loader
  NOTE: This means that any commandline tools will need to be updated to
  load the autoloader.
* Lots of refactoring to support the autoloader
* Strictness related cleanup:
  * Declare our abstract classes
  * Add private constructors to static-only classes
* Add type hints to our methods (#140)
* Fix #109, allow index comparisions able to be aware of column name aliases.
* Fix #33-- binary types never emit charset data

v0.2.0 2012-04-30

* Rework our package build process to be more palatable
* Rename schema classes to be Modyllic_Schema_*
* Move more documentation into the wiki and update the publishing document (Aria Stewart)
* Rename SQL generator dialects to be more useful
* Rename Modyllic_Commandline->Modyllic_CommandLine to match Console_CommandLine
* Add support for --version to all of the commandline tools
* Remove unused sqlmeta_exists from changeset support
* Remove now unused schema level sqlmeta tracking
* #103 Converting from static to non-static no longer results in deleted rows
* Fix strict error-- array_shift must take a variable as an argument
* #85 Recursive scan directories for .sql files
* Fix #110 - In sqlcolorize, on exit reset colors rather then explicitly setting white

v0.1.7 2012-04-26

* Add IF EXISTS to all of our DROPs
* Fix bugs in --only support
* Fix bugs in tracking sqlmeta_exists

v0.1.6 2012-04-26

* Stop Modyllic_Parser::partial from having a return value
* Complain if no toschema is provided to sqldiff
* Fix a --progress divide by zero when the source file was empty
* Fix bug where a DROP DATABASE would ignore any following CREATEs
* Make the SQL generator able to only output specific kinds of data
* Boolean types were being treated as exactly equivalent to TINYINT and this isn't actually the case
* displayError should really be a static function
* Fix migration --create option
* Fix bugs around when SQLMETA is created
* Add support for MySQL Triggers
* Fix view changeset handling

v0.1.5 2012-04-26

* Add IF EXISTS to events. Fixes #93.
* Stop using 5.3 Exception form and just rethrow non-general errors
* RETURNS COLUMN assertions should allow empty result sets (#56)
* Implement support for extended inserts (#88)
* Fix missing is_primary attribute on columns
* Improve error messages for invalid delimiters (#82)

v0.1.4 2012-04-26

* Add a debug flag to aid in debugging parser errors
* Remove verbose output from debugging
* Fix innumerable problems with migrate since the commandline refactor
* Allow colons or semicolons in dsns, to make command lines less painful
* Fix bug where the database name wasn't being emitted in some circumstances
* SQLMETA updates were bogus, changed to just delete and insert. 
* The static property on tables wasn't being emitted correctly.
* Fix bugs in primary key handling caused by support for key lengths
* Fix bug in how dynamically named indexes were emitted. Now use the name for the purposes of diffs but don't emit it
* Fix the file roles

v0.1.3 2012-04-26

* Remove unused method Modyllic_Loader::from_db
* Correct DSN loading to allow equals signs in values.
* URL decode DSN values prior to using them.
* Change terminal detection to only run tput if it can plausibly work.

v0.1.2 2012-04-26

* Packaging updates to use our own channel and document releases
* Class rename bug in SQL generators
* Loader fix required for static tables, spread across multiple files

v0.1.1 2012-01-31

* No changelog for this version.

v0.1.0 2012-01-31

* No changelog for this version.

