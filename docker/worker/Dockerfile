FROM ubuntu:22.04

WORKDIR /file_transfer

RUN apt-get update -y && apt-get install -y \
  gcc \
  ffmpeg \
  mpich \
  openssh-server

# RUN mpicc /file_transfer/ugly.c -o ugly
RUN useradd -m runner

# FIXME: change password's method is not secured!!!
RUN sh -c 'echo runner:123456 | chpasswd'

RUN mkdir /run/sshd

ENTRYPOINT [ "/usr/sbin/sshd", "-D", "-p", "2222" ]
