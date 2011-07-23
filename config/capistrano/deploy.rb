require 'rubygems'
require './config/capistrano/util.rb' # Utility methods

begin
	gem 'railsless-deploy'
	gem 'capistrano-ext'
	gem 'rainbow'
    rescue Gem::LoadError => e
	puts "--- " + e.to_s
	exit
end

# Configure directory where stage configurations are located. This has
# to be done before requiring "multistage", because otherwise the
# configuration is ignored.
set :stage_dir, './config/capistrano/stages/'

require 'rainbow' # Shell font colorization support.
require 'railsless-deploy'
require 'capistrano/ext/multistage'
require './config/capistrano/project.rb' # Include Project Configuration


set :scm, :git
set :deploy_via, :remote_cache
set :use_sudo, false


# Load project configuration
self.extend Gosign::Config
loadConfig()



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

    task :create_structure do
      setupDefaultStructure(deploy_to + "/", false)
    end
end
after "deploy:setup", "setup:create_structure", "setup:extra_symlinks"


# Namespace for local tasks
namespace :local do

  # Used to create the correct project structure after cloning the project.
  # This script capExecutes the commands according to the "tutorial" located
  # in the DefaultProjekt readme file:
  # https://github.com/gosign-media/DefaultProjekt/blob/master/README.md
  task :setup do
    Gosign::Util.notice("About to create default project structure...")

    if Dir.exists?("../data") and Dir.exists?("../htdocs") and File.symlink?("../src")
      Gosign::Util.error("The folder structure seems to be set up already! Aborting...")
      exit
    end

    cwd = Dir.pwd + "/"

    capExec("Moving files to htdocs/ subfolder:") do
      <<-eos
        mkdir #{cwd}htdocs &&
        find #{cwd} -mindepth 1 -maxdepth 1 -not -name "htdocs" -prune -exec mv {} #{cwd}htdocs/ \\;
      eos
    end

    setupDefaultStructure(cwd)

  end

end


# This method encapsules the commands needed to set up the project structure
# according to the instructions located in
# https://github.com/gosign-media/DefaultProjekt/blob/master/README.md
# If changes are needed, you should also make these changes in the README file.
def setupDefaultStructure(path, local=true)

  capExec("Cloning default project structure: ", local) do
    <<-eos
      git clone -q git@github.com:gosign-media/DefaultProjektStruktur.git #{path}DefaultProjektStruktur &&
      rm -Rf #{path}DefaultProjektStruktur/.git &&
      rm -Rf #{path}DefaultProjektStruktur/htdocs &&
      find #{path}DefaultProjektStruktur -mindepth 1 -maxdepth 1 -prune -exec mv {} #{path} \\; &&
      rm -Rf #{path}DefaultProjektStruktur
    eos
  end

  capExec("Cloning data folder structure: ", local) do
    <<-eos
      rm #{path}data/index.html &&
      git clone -q git@github.com:gosign-media/DataDummy.git #{path}data/ &&
      rm -Rf #{path}data/.git
    eos
  end

  capExec("Cleaning up: ", local) do
    <<-eos
      rm #{path}README.md &&
      rm #{path}data/README.md &&
      rm #{path}.gitignore &&
      rm #{path}data/.gitignore
    eos
  end

end


# Execute a shell command, either locally or remotely according to the
# "local" flag. When executing locally, a check whether or not a command
# succeeded is made, this check is done automatically by capistrano when
# executing remotely.
def capExec(msg, local=true, &block)

  x = block.call
  Gosign::Util.notice(msg, false)

  if local
    out = %x[#{x}]

    if($? != 0)
      Gosign::Util.error(" failed")
      Gosign::Util.error("Aborting...")
      exit
    end

    Gosign::Util.msg(" success")

    out
  else
    run(x)
  end

end