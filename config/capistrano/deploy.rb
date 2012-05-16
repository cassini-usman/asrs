require 'rubygems'
require './config/capistrano/gosign/gosign.rb'

begin
	gem 'railsless-deploy'
	gem 'capistrano-ext'
	gem 'rainbow'
    rescue Gem::LoadError => e
	abort( "--- " + e.to_s )
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

ssh_options[:forward_agent] = true

# Load project configuration
self.extend Gosign::Config
loadConfig()


# We need a reference to the capistrano stuff in our utility functions, so
# we set it here.
Gosign.cap = self


# Task to test our server configuration, using
# the command "cap uname <stage>"
task :uname do
    run "uname -a"
end



namespace :deploy do

  # Create a symlink from htdocs to current to keep our
  # DefaultProjekt structure intact.
  task :resymlink, :roles => :app do
    run "ln -s #{current_path} #{deploy_to}/htdocs"
  end

  task :permissions do
    Gosign::Perms.set(release_path, false, { chmod: filePermissions, type: "f", chgrp: group })
    Gosign::Perms.set(release_path, false, { chmod: setDirectoryPermissions, type: "d", chgrp: group })
    Gosign::Perms.set(release_path, false, { chmod: phpshPermissions, type: "f", name: "*.phpsh", chgrp: group })

    # Set permissions for the local/ server-configuration folder too, just in case
    Gosign::Perms.set(deploy_to + "/local/", false, { chmod: 660, type: "f", chgrp: group })
    Gosign::Perms.set(deploy_to + "/local/", false, { chmod: 770, type: "d", chgrp: group })
  end

  # Synchronizes the remote user_uploads/ folder with the local one. Note that
  # the synchronization is non-destructive, that is, newer files on the remote
  # system won't be overwritten and locally deleted files won't be deleted
  # remotely.
  task :user_upload do
    source = Dir.pwd + "/fileadmin/user_upload/"
    target = deploy_to + "/data/fileadmin/user_upload/"
    roles[:app].servers.each do |server|
      Gosign::Util.exec("Transmitting local user_uploads/ to #{server}:") do
        "rsync -auxzv #{source} #{server}:#{target}"
      end
    end
  end
end
before "deploy:symlink", "deploy:permissions"
after "deploy:symlink", "deploy:resymlink"

after "deploy:user_upload", "setup:set_data_permissions"

namespace :setup do

    # Because of capistrano's default directory strucuture, which adds
    # one level of depth compared to our default structure, we need to
    # create some extra symlinks so our default project's symlinks don't
    # break.
    task :extra_symlinks do
        run <<-eos
          cd #{deploy_to}/releases &&
          ln -s ../src src &&
          ln -s ../data data &&
          ln -s ../local local
        eos
    end

    # Set up the default project structure
    # See Gosign::Structure.setup() for more information
    task :create_structure do
      Gosign::Structure.setup(deploy_to + "/", false)
    end

    task :set_data_permissions do
      Gosign::Perms.set(deploy_to + "/data/", false, { chmod: 660, type: "f", chgrp: group })
      Gosign::Perms.set(deploy_to + "/data/", false, { chmod: 770, type: "d", chgrp: group })
      Gosign::Perms.set(deploy_to + "/data/", false, { chmod: 770, type: "f", name: "*.phpsh", chgrp: group })
    end

end
after "deploy:setup", "setup:create_structure", "setup:extra_symlinks", "setup:set_data_permissions"


# Namespace for local tasks
namespace :local do

  # Used to create the correct project structure after cloning the project.
  # This script Gosign::Util.executes the commands according to the "tutorial" located
  # in the DefaultProjekt readme file:
  # https://github.com/gosign-media/DefaultProjekt/blob/master/README.md
  task :setup do
    Gosign::Util.notice("About to create default project structure...")

    if Gosign::Structure.correct?
      Gosign::Util.error("The folder structure seems to be set up already! Aborting...")
      exit
    end

    cwd = Dir.pwd + "/"

    Gosign::Util.exec("Moving files to htdocs/ subfolder:") do
      <<-eos
        mkdir #{cwd}htdocs &&
        find #{cwd} -mindepth 1 -maxdepth 1 -not -name "htdocs" -prune -exec mv {} #{cwd}htdocs/ \\;
      eos
    end

    Gosign::Structure.setup(cwd)
  end

  # Set local permissions to 644 (files) and 755 (directories)
  # Also make *.phpsh scripts executable (775)
  task :permissions do
    cwd = Dir.pwd + "/"

    if not Gosign::Structure.correct?
      Gosign::Util.error("The correct directory structure is not yet set up, please run 'local:setup'")
      exit
    end

    Gosign::Perms.set(cwd + "../", true, { chmod: 644, type: "f" })
    Gosign::Perms.set(cwd + "../", true, { chmod: 755, type: "d" })
    Gosign::Perms.set(cwd + "../", true, { chmod: 775, type: "f", name: "*.phpsh" })
  end

end

load './config/capistrano/gosign/tasks.rb'
