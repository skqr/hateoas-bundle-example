Vagrant.configure("2") do |config|
  config.vm.box = "bento/ubuntu-16.04"

  config.vm.provider "virtualbox" do |v|
    v.memory = 3072
    v.cpus = 2
  end

  config.vm.hostname = "hateoas-bundle-example"
  config.vm.network "forwarded_port", guest: 8000, host: 8000
end
