<?php

return [
  # docker master container's image name
  'master' => env('DOCKER_MASTER', 'master'),

  # docker master's video's mount path
  'video_path' => env('DOCKER_VIDEO_PATH', '/var/www/html/storage/app/public/videos'),
];
