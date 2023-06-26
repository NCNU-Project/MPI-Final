resource "digitalocean_droplet" "msater-1" {
  image = "ubuntu-20-04-x64"
  name = "master-1"
  region = "sgp1"
  size = "s-1vcpu-1gb"
  ssh_keys = [
    data.digitalocean_ssh_key.sysadm.id
  ]
  
  connection {
    host = self.ipv4_address
    user = "root"
    type = "ssh"
    private_key = file(var.pvt_key)
    timeout = "2m"
  }
  
  provisioner "remote-exec" {
    inline = [
      "export PATH=$PATH:/usr/bin",
      "sudo apt update",
      "sudo apt install -y curl",
      # install docker
      "curl -L get.docker.com | sudo bash",
      "docker run -d --restart always -p 2222:2222 ghcr.io/efficacy38/mpi-worker:v1.1"
    ]
  }
}

resource "digitalocean_droplet" "workers" {
  count = 3
  image = "ubuntu-20-04-x64"
  name = "web-${count.index + 1}"
  region = "sgp1"
  size = "c-4"
  ssh_keys = [
    data.digitalocean_ssh_key.sysadm.id
  ]
  
  connection {
    host = self.ipv4_address
    user = "root"
    type = "ssh"
    private_key = file(var.pvt_key)
    timeout = "2m"
  }
  
  provisioner "remote-exec" {
    inline = [
      "export PATH=$PATH:/usr/bin",
      # install nginx
      "sudo apt update",
      "sudo apt install -y curl",
      # install docker
      "curl -L get.docker.com | sudo bash",
      "docker run -d --restart always -p 2222:2222 ghcr.io/efficacy38/mpi-worker:v1.1"
    ]
  }
}
