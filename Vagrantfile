Vagrant.configure("2") do |config|
    config.vm.box = "cent65"
    config.vm.box_url = "https://github.com/2creatives/vagrant-centos/releases/download/v6.5.1/centos65-x86_64-20131205.box"
    config.vm.provision :shell, :path => "bootstrap.sh"
    config.vm.network :forwarded_port, guest: 80, host: 8080
    config.ssh.forward_agent = true
    config.vm.provider :virtualbox do |vb|
        vb.customize [ "modifyvm", :id, "--memory", 512 ]
    end
end