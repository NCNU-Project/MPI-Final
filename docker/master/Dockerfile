FROM ubuntu:22.04

WORKDIR /

RUN apt-get update -y && apt-get install -y \
  gcc \
  ffmpeg \
  mpich

ADD ./entrypoint.sh ./video_scaling.c /

RUN mpicc /video_scaling.c -o /video_scaling.o

RUN mkdir /tmp/videos

ENTRYPOINT [ "/entrypoint.sh" ]
