require 'rubygems'

begin
	gem 'railsless-deploy'
	gem 'capistrano-ext'
    rescue Gem::LoadError => e
	puts "--- " + e.to_s
	exit
end

require 'railsless-deploy'
require 'capistrano/ext/multistage'

# After you have configured capistrano, you can
# uncomment or remove the following line:
abort("Please configure capistrano first!")


set :stages, %w(production staging)
set :default_stage, "staging"


# The application name, which is also used as
# the applications folder name on the remote server
# and therefore should not contains whitespaces,
# special characters, etc...
set :application, "<PROJECT FOLDER NAME>"

set :scm, :git
set :repository, "<GITHUB REPOSITORY URL>"
set :deploy_via, :remote_cache
set :use_sudo, false


# Task to test our server configuration, using
# the command "cap uname <stage>"
task :uname do
    run "uname -a"
end


# Because of capistrano's default directory strucuture, which adds
# one level of depth compared to our default structure, we need to
# create some extra symlinks so our default project's symlinks don't
# break.
namespace :setup do
    task :extra_symlinks do
        run "cd #{deploy_to}/releases && ln -s ../src src && ln -s ../data data"
    end
end
after "deploy:setup", "setup:extra_symlinks"
