Vagrant.configure("2") do |config|
    config.vm.box = "cent63"
    config.vm.box_url = "https://dl.dropbox.com/u/7225008/Vagrant/CentOS-6.3-x86_64-minimal.box"
    config.vm.provision :shell, :path => "bootstrap.sh"
    config.vm.network :forwarded_port, guest: 80, host: 8080
    config.vm.network :forwarded_port, guest: 7070, host: 7070
end