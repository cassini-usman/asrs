require 'rubygems'

begin
	gem 'railsless-deploy'
	gem 'capistrano-ext'
	gem 'rainbow'
    rescue Gem::LoadError => e
	puts "--- " + e.to_s
	exit
end

require 'rainbow'

# After you have configured capistrano, you can
# uncomment or remove the following line:
abort("Please configure capistrano first!")


set :customer "<WICHTIG: Name des Kunden wie in SysCP>"


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


# Create a symlink from htdocs to current to keep our
# DefaultProjekt structure intact.
namespace :deploy do
  task :resymlink, :roles => :app do
    run "ln -s #{current_path} #{deploy_to}/htdocs"
  end
end
after "deploy:symlink", "deploy:resymlink"


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




namespace :local do


  # Used to create the correct project structure after cloning the project.
  # This script executes the commands according to the "tutorial" located
  # in the DefaultProjekt readme file:
  # https://github.com/gosign-media/DefaultProjekt/blob/master/README.md
  task :setup do
    Gosign::Util.notice("About to create default project structure...")

    if Dir.exists?("../data") and Dir.exists?("../htdocs") and File.symlink?("../src")
      Gosign::Util.error("The folder structure seems to be set up already! Aborting...")
      exit
    end

    Gosign::Util.exec("Moving files to htdocs/ subfolder:") do
      <<-eos
        mkdir htdocs &&
        find . -depth 1 -not -name "htdocs" -exec mv {} htdocs/ \\; -prune
      eos
    end

    Gosign::Util.exec("Cloning default project structure: ") do
      <<-eos
        git clone -q git@github.com:gosign-media/DefaultProjektStruktur.git &&
        rm -Rf DefaultProjektStruktur/.git &&
        rm -Rf DefaultProjektStruktur/htdocs &&
        find ./DefaultProjektStruktur -depth 1 -exec mv {} ./ \\; -prune &&
        rm -Rf DefaultProjektStruktur
      eos
    end

    Gosign::Util.exec("Cloning data folder structure: ") do
      <<-eos
        cd data/ &&
        rm index.html &&
        git clone -q git@github.com:gosign-media/DataDummy.git . &&
        rm -Rf ./.git
      eos
    end

    Gosign::Util.exec("Cleaning up: ") do
      <<-eos
        rm README.md &&
        rm data/README.md
      eos
    end

  end


end
