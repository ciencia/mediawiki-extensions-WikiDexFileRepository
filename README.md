# mediawiki-extensions-WikiDexFileRepository
Custom file repository that adds some virtual paths to file URLs, so they can
be cached more efficiently (generating sort of permanent URLs), and change
when a new version of the file is uploaded (using the file timestamp).

This extension requires custom rewrite rules on the web server to work.
