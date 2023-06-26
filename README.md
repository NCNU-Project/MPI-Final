# MPI-Final

## Initialize Web

1. build the images

```
./build.sh
```
2. setup the environment

```
./setup.sh
```
3. build master 部分的 image :
  `docker build -t master`
4. (測試用) 以本機 videos/short.mp4 來做測試，轉檔後輸出檔案為 videos/new_short.mp4
    `docker run -it --rm -v "/home/runner/file_transfer/videos:/videos" master -f videos/short.mp4 -d videos/new_short.mp4`

## config
1. 修改 `.env` 內的 video_path 成本機的 path

## run/stop it

1. start: `docker compose up -d`
2. start: `docker compose down`
