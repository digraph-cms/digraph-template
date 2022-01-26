# Digraph CMS project template

This repository is meant to be used as a template for creating new Digraph sites. It contains a minimal amount of configuration and dependencies that should get you up and running *relatively* easily for most server configurations.

## Assumptions

This project template assumes the following characteristics of your use case, and will need to be modified if any of the following are not true:

* Apache web server
  * `mod_rewrite` is required
  * `mod_filter`, `mod_headers`, and `mod_expires` are recommended for optimal static content performance
* Composer is available in your development and deploy environments
* MySQL or SQLite available to store site data
* Deploying using GitHub actions
  * Some basic actions are included that should work for many simple use cases, see Deploying for more information
  * If you will not use GitHub for deploying, you should delete the `.github/` directory
* Web root should be pointed at the `web/` directory, in order to keep the other directories from being available

## Installation

Clone/copy this repository, either using GitHub's "Use this template" button or Composer's `create-project` command.

## Configuration

Site configuration is stored in `digraph.json`, and the values in it can be overridden by `digraph-env.json`, which is kept out of the repository with the `.gitignore` file. To get off the ground you should only need to configure your site name at `fields.site.name`, a user sign-in method under `user_sources` (see the next section for more info).

By default a local SQLite database will be used, if you would like to use an external MySQL server, configure it under `db`.

### User sources

Digraph does not contain its own sign-in system, and is instead designed to use external OAuth or CAS providers for handling user logins. You can configure any number of user providers you like, including manually-configured servers.

Digraph comes pre-configured to use the following providers, and they can be enabled by adding the OAuth app ID and secret provided by their OAauth provider tools to their respective config locations:

* GitHub  
  `user_sources.oauth2.providers.github.id` and  
  `user_sources.oauth2.providers.github.secret`
* Google  
  `user_sources.oauth2.providers.google.id` and  
  `user_sources.oauth2.providers.google.secret`
* Facebook  
  `user_sources.oauth2.providers.facebook.id` and  
  `user_sources.oauth2.providers.facebook.secret`

#### Mock sign-in systems for development

You may want to enable a mock sign-in system on a private development environment, so that you can have accounts on it without the complexity of connecting to external servers. If you would like to do this, you can enable a mock CAS provider by adding the following to your development environment's `digraph-env.json`. This will enable a user source named "Mock CAS provider" that allows you to sign in using only a username. A mock user provider should **never** be enabled on a publicly-accessible server.

```json
    "user_sources": {
        "cas": {
            "providers": {
                "mock": {
                    "active": true,
                    "name": "Mock CAS provider",
                    "mock_cas_user": true
                }
            }
        }
    }
```

### External database

If you would like to configure an external MySQL database instead of using a local SQLite file, use something similar to the following configuration. This config includes credentials, so put it in `digraph-env.json` and be careful to not commit that file to your own repository.

```json
    "db": {
        "adapter": "mysql",
        "dsn": "mysql:host=localhost;port=3307;dbname=testdb",
        "user": "username",
        "pass": "password",
        "pdo_options": []
    }
```

### File permissions

Digraph uses the local filesystem for storage and caching, and needs to be able to write static files into a public directory. With the default configuration the following directories must be writeable by Apache (i.e. chmod `0770` and owned by the user or group that your web server runs under):

* `cache/`
* `storage/`
* `web/files/`

## Installing dependencies

Digraph uses Composer to manage its dependencies and Phinx to maintain its database migrations. In this template Phinx is automatically configured and run whenever Composer installs or updates dependencies.

## Creating the first user account

... coming soon ...

## Running a local