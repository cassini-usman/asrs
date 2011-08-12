class Gosign
  module Perms

    # Sets permissions for the specified path, locally or remotely according
    # to the "local" parameter. You can specify the following options:
    #
    #     type: "d" or "f" for "only directories" and "only files" respectively
    #     name: only change the permissions for matching filenames (e.g "*.phpsh")
    #     chmod: change the file permissions to the value of this options (e.g. 644)
    #     chgrp: change the group of the matching files to the value of this option
    #
    #     e.g. setPermissions("./", true, { chmod: 660, type: "f" })
    def self.set(path, local, opts)
      find = "find #{path} "

      find += "-type #{opts[:type]} " if not opts[:type].nil?
      find += "-name #{opts[:name]} " if not opts[:name].nil?

      cmds = []
      cmds.push find + "-exec chmod #{opts[:chmod]}  {} \\; " if not opts[:chmod].nil?
      cmds.push find + "-exec chgrp #{opts[:chgrp]} {} \\; " if not opts[:chgrp].nil?

      Gosign::Util.exec("Executing: " + cmds.join(" && "), local) do
        cmds.join(" && ")
      end
    end

  end
end