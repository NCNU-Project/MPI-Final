/* hello.c */
#include <mpi.h>
#include <stdio.h>
#include <stdlib.h>
#define TAG 0
int main(int argc, char ** argv)
{
	int rank, size;
	MPI_Init(&argc, &argv);
	MPI_Comm_rank(MPI_COMM_WORLD, &rank);
	MPI_Comm_size(MPI_COMM_WORLD, &size);

	if (rank == 0)
	{
		printf("size: %d\n", size);
		char** filename = malloc((size + 4) * sizeof(char*));
		for (int i = 1; i < size; i++)
		{
			filename[i] = malloc(100 * sizeof(char));
			sprintf(filename[i], "/tmp/%s.%d.mp4", argv[1], i);
			
		}
		char len_cmd[200], video_sec_string[50];
		sprintf(len_cmd, "ffprobe -v error -select_streams v:0 -show_entries stream=duration -of default=noprint_wrappers=1:nokey=1 %s", argv[1]);
		FILE* fp = popen(len_cmd, "r");
		fgets(video_sec_string, 50, fp);
		pclose(fp);
		printf("video length: %s\n", video_sec_string);
		int video_sec = (int)atof(video_sec_string);
		printf("video length in num: %d\n", video_sec);

		int slice_time = video_sec / (size - 1);
		for (int i = 1; i < size; i++)
		{
			char slice_cmd[200];
			sprintf(slice_cmd, "ffmpeg -ss %d -i %s -c copy -t %d -avoid_negative_ts 1 -strict -2 %s", slice_time * (i-1), argv[1], slice_time, filename[i]);
			system(slice_cmd);
		}
		
		char slice_cmd[200];
		sprintf(slice_cmd, "ffmpeg -ss %d -i %s -c copy -avoid_negative_ts 1 -strict -2 %s", slice_time * (size-2), argv[1], filename[size-1]);

		for (int i = 1; i < size; i++)
		{
			FILE* f = fopen(filename[i], "rb");
			if (f == NULL)
			{
				printf("can not open file\n");
				MPI_Abort(MPI_COMM_WORLD, 1);
			}
			fseek(f, 0, SEEK_END);
			long filesize = ftell(f);
			fseek(f, 0, SEEK_SET);
			MPI_Send(&filesize, 1, MPI_LONG, i, TAG, MPI_COMM_WORLD);
			char* buffer = (char*)malloc(filesize);
			fread(buffer, sizeof(char), filesize, f);
			MPI_Send(buffer, filesize, MPI_CHAR, i, TAG, MPI_COMM_WORLD);
			free(buffer);
			fclose(f);
		}	
		
		FILE* filelist = fopen("filelist.txt", "w");
		for (int i = 1; i < size; i++)
		{
			long filesize;
			MPI_Recv(&filesize, 1, MPI_LONG, i, TAG, MPI_COMM_WORLD, MPI_STATUS_IGNORE);
			char* buffer = (char*)malloc(filesize);
			MPI_Recv(buffer, filesize, MPI_CHAR, i, TAG, MPI_COMM_WORLD, MPI_STATUS_IGNORE);

			
			char tempname[50];
			sprintf(tempname, "temp%d.mp4", i);
			FILE* file = fopen(tempname, "wb");
			if (file == NULL) {
				printf("無法寫入檔案\n");
				MPI_Abort(MPI_COMM_WORLD, 1);
			}
			fwrite(buffer, sizeof(char), filesize, file);
			fprintf(filelist, "file '%s'\n", tempname);
			fclose(file);
			
		}
		fclose(filelist);
		char merge_cmd[200];
		sprintf(merge_cmd, "ffmpeg -f concat -i filelist.txt -c copy -strict -2 %s", argv[2]);
		system(merge_cmd);

		printf("0 has done\n");
	}
	else
	{
		
		printf("I am %d\n", rank);
		long filesize;
		MPI_Recv(&filesize, 1, MPI_LONG, 0, TAG, MPI_COMM_WORLD, MPI_STATUS_IGNORE);

		char* buffer = (char*)malloc(filesize);
		MPI_Recv(buffer, filesize, MPI_CHAR, 0, TAG, MPI_COMM_WORLD, MPI_STATUS_IGNORE);

		
		char filename[50];
		sprintf(filename, "worker%d.mp4", rank);

		FILE* file = fopen(filename, "wb");
		if (file == NULL) {
			printf("無法寫入檔案\n");
			MPI_Abort(MPI_COMM_WORLD, 1);
		}
		fwrite(buffer, sizeof(char), filesize, file);
		fclose(file);
		free(buffer);

		
		char scale_cmd[200];
		sprintf(scale_cmd, "ffmpeg -i worker%d.mp4 -vf scale=-1:1080 scaled%d.mp4", rank, rank);
		system(scale_cmd);

		char scaled_name[50];
		sprintf(scaled_name, "scaled%d.mp4", rank);
		FILE* scaled_file = fopen(scaled_name, "rb");
		if (scaled_file == NULL)
		{
			printf("can not open file\n");
			MPI_Abort(MPI_COMM_WORLD, 1);
		}
		fseek(scaled_file, 0, SEEK_END);
		long scaled_filesize = ftell(scaled_file);
		fseek(scaled_file, 0, SEEK_SET);
		
		MPI_Send(&scaled_filesize, 1, MPI_LONG, 0, TAG, MPI_COMM_WORLD);
		printf("%ld", scaled_filesize);
		char* scaled_buffer = (char*)malloc(filesize * sizeof(short));
		fread(scaled_buffer, sizeof(char), scaled_filesize, scaled_file);
		MPI_Send(scaled_buffer, scaled_filesize, MPI_CHAR, 0, TAG, MPI_COMM_WORLD);
		free(scaled_buffer);
		fclose(scaled_file);
		

		printf("%d has done\n", rank);
	}
	MPI_Finalize();
	return 0;
}
