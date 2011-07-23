server '<remote server, e.g. go11.gosign.de>', :app, :web, :primary => true

# Path we want to deploy to. Note that this is not
# htdocs/, but the folder we cloned our "DefaultProjektStruktur"-
# repository to!
set :deploy_to, "/home/kunden/webs/#{customer}/#{application}/#{stage}"

# User and group of the deploy user on the remote server
set :user, "<user>"
set :group, "<group>"


# Which branch of our repository do we want to check out?
set :branch, "master"


# Set the correct group and access rights for all our files.
# This may be different for every server, and therefore is
# is located in the stage file.
# TODO: Implement properly
#namespace :deploy do
#    task :permissions do
#        run "cd #{deploy_to}/current && chgrp -Rf www-data . && chmod -Rf 775 ."
#    end
#end
#after :deploy, "deploy:permissions"
