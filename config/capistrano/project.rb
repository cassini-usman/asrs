module Gosign
  module Config

    def loadConfig

# After you have configured capistrano, you canuncomment or remove the
# "abort()"-call. Note that you also need to configure the stages located
# in config/deploy/.
abort("Please configure capistrano first!")

# Default permissions which are set on each deploy (by "deploy:permissions")
# These may be overriden in the stage configuration.
set :filePermissions, 660
set :setDirectoryPermissions, 770
set :phpshPermissions, 770

# The customer config variable is needed for our server directory structure,
# which looks something like [..]/webs/<customer>/<project>/<stage>/[..]
set :customer, "WICHTIG: Name des Kunden wie in SysCP"

# The stages we can deploy to, and the automatically selected stage if
# none is specified.
set :stages, %w(production staging)
set :default_stage, "staging"

# The application name, which is also used as the applications folder name
#on the remote server and therefore should not contains whitespaces,
# special characters, etc...
set :application, "PROJEKT NAME - WICHTIG: Wie GitHub Repository"

# Repository URL. Note that if you need to modify this, you have done something
# wrong when creating the repository on GitHub, so please correct the repo
# name if somehow possible.
set :repository, "git@github.com:gosign-media/#{application}.git"

    end

  end

end