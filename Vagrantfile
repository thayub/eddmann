Vagrant.configure("2") do |config|
    config.vm.box = "cent64"
    config.vm.box_url = "http://developer.nrel.gov/downloads/vagrant-boxes/CentOS-6.4-x86_64-v20130427.box"
    config.vm.provision :shell, :path => "bootstrap.sh"
    config.vm.network :forwarded_port, guest: 80, host: 8080
    config.ssh.forward_agent = true
end