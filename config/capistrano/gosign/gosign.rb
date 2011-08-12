require './config/capistrano/gosign/util.rb'
require './config/capistrano/gosign/perms.rb'
require './config/capistrano/gosign/structure.rb'

# We need a reference to capistrano in our Gosign utility functions,
# so we prepare it here. It's set in deploy.rb
# http://coding-journal.com/ruby-attr_accessor-for-class-variables/
class Gosign
  class << self
    attr_accessor :cap
  end
end