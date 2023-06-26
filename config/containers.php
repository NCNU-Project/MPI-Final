<?php

return [
  # docker master container's image name
  'master' => env('DOCKER_MASTER', 'ghcr.io/efficacy38/mpi-master:v1.1'),

  # docker master's video's mount path
  'video_path' => env('DOCKER_VIDEO_PATH', '/var/www/html/storage/app/public/videos'),
];
