#include <stdio.h>
#include <stdlib.h>
#include <time.h>

__attribute__((constructor)) void before_main() {
	printf("--- %ld\n", time(NULL));
}

__attribute__((destructor)) void after_main() {
	printf("--- %ld\n", time(NULL));
}

int main(int argc, char **argv) {
	printf("--- %s\n", __func__);

	exit(0);

	printf("--- %s, exit ?\n", __func__);

	return 0;
}
