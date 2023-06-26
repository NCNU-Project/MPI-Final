FROM ubuntu:22.04

RUN apt-get update -y && apt-get install -y \
  gcc \
  ffmpeg \
  mpich \
  openssh-server

# RUN mpicc /file_transfer/ugly.c -o ugly
RUN useradd -m runner

RUN mkdir ~runner/.ssh
RUN sh -c "echo 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABgQDQjQ9CPmPxt1OvmEv7SfcmAZGQZVm7BADcTDMMlwLKnP3WolZGQ5B+XT2Z4og6hzu9edhNLXYtzN1ltXW0bFjcDNgkjBhSfEVDsnvvVyXqi1ikfot7AJqGy7AFsCZu3PbUxyo+HGnqYWECg187+0FlcdJWKJCujO0gHp2DelyGSGxdwfhMbkVPEsiBZSrZwFGht1xnxwZBMr/oSRxt62ZpNyZzCFQjx8HgLLx/PVKEA0s+W1qR6Oyu+3DGkUEEqqZVWEc3rPm7BQLvYqK6w2mVqusn9Axh/uFUjLAovYoBnxwuv9OQJbKB/yzSVaR9t8/f4JEO7dAW2w9KsvQIUEHEUVODdZPoWVo1D8vtPm3XspLpsqBYtWkKvyh0WT5/GgduvVk/X42AOQbRIUR9yynoFgdvSKgB9Hn9KlGXBRQ4uJeXNaETC7aKiv3FEY2BKVlXrvdcor8A9p3aX4E13mQZh7tS+5slaMBQlr82+C6F6gQiBv+SCQryKjHWO5R8gH8= efficacy38@phoenixton' | tee ~runner/.ssh/authorized_keys"
RUN chown runner:runner ~runner/.ssh/authorized_keys

RUN mkdir /run/sshd

ADD ./video_scaling.c /

RUN mpicc /video_scaling.c -o /video_scaling.o

ENTRYPOINT [ "/usr/sbin/sshd", "-D", "-p", "2222" ]
