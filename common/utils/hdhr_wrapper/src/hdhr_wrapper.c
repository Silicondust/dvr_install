/******************************************************************************
 *  hdhr_wrapper.c
 *  Description: simple C wrapper to be compiled for NAS which do not have by
 *               default, su or sudo enabled to be able to run the HDHR DVR as
 *               an alternative user. This resulting binary needs to be run as
 *               root and will lower privileges on the system to the user
 *               specified on the command line.
 *  Version:     1.0
 *  
 *****************************************************************************/

#include <stdio.h>
#include <unistd.h>
#include <stdlib.h>
#include <pwd.h>
#include <sys/types.h>
#include <string.h>

void dump_header(void) {
	printf("+===========================================================+\n");
	printf("| Simple wrapper for HDHomeRun DVR Record Engine  v0.1      |\n");
	printf("| changes effective user to same user as web engine for the |\n");
	printf("| web UI to have sufficient privileges to stop/start the    |\n");
	printf("| DVR engine backend without                                |\n");
	printf("| Must be run as admin/root user                            |\n");
	printf("| This script will not elevate privleges.                   |\n");
	printf("+===========================================================+\n");
}

void usage(void) {
	printf("+===========================================================+\n");
	printf("|                                                           |\n");
	printf("| hdhr_wrapper -u <username> -b <binary to execute>         |\n");
	printf("|                                                           |\n");
	printf("+===========================================================+\n");
}

int main(int argc, char* argv[]) {
	int opt,i,j = 0;
	struct passwd *new_user;
	int new_userid,cur_euserid, cur_userid = 0;
	char *binary;
	char **passonarg;
	char *passonenv[] = {NULL};
	
	dump_header();

	// Parse the command line options
	if (argc < 2) {
		usage();
		return 0;
	}

	while ((opt = getopt(argc, argv, "u:b:-")) != -1) {
		switch (opt) {
			case 'u':
				new_user = getpwnam(optarg);
				if (new_user == NULL) {
					printf("Can't find User: %s \n", optarg);
					return 0;
				}
				printf("User: %s \n", new_user->pw_name);
				break;
			case 'b':
				binary = optarg;
				if (access(binary, F_OK) != -1) {
					printf("Binary: %s \n", binary);
				} else {
					printf("Can't access the binary file at: %s \n", optarg);
					return 0;
				}
				break;

			default:
				usage();
				return 0;
		}
	}

	printf("Allocating %d entries\n",argc - optind);
	passonarg = malloc(sizeof(char *) * (argc - optind + 1));

	// catch the rest of the params to send to the binary/script being called.
	passonarg[0] = binary;
	j = 1;
	for (i = optind ; i < argc ; i++, j++) {
		passonarg[j] = malloc(strlen(argv[i]))+1;
		strcpy(passonarg[j],argv[i]);
		printf("Passing on [%d] %s\n",j ,argv[i]);
	}
	passonarg[j] = (char*) NULL;

	// Checking UserID is root/admin
	cur_userid = getuid();
	if (cur_userid != 0) {
		printf("ERROR - Must be run as root/admin user\n");
		return 0;
	} 

	// no way back from here - cannot come back to root....
	printf("Attempting to change user\n");
	setuid(new_user->pw_uid);

	printf("Executing the binary\n");
	execv(binary,passonarg);
	perror(":exec failed:");

	//clean up
	for (i=0;i==j;i++) {
		free(passonarg[i]);
	}
	free(passonarg);
	return 0;
}
