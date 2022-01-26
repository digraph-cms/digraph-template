# Files directory

Digraph uses this directory to store processed files that need to be accessible on the public site. Things like resized images, compiled CSS and JS files, and copies of files that users are able to download will be kept here.

By default files in this directory are kept indefinitely, and many files in this directory will be named based on their content and should be considered more or less immutable once written.

You may wish to clear the contents of this directory to free up disk space or force regeneration of images or other files, and this is possible. You should always clear the cache directory at the same time though, otherwise there will be a time period of broken links throughout the site as some cached content will likely reference files that need to be regenerated.
