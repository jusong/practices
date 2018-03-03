#include <stdio.h>
#include <string.h>

int
main(void) {

	int i = 10;
	int *j_ptr, *i_ptr;

	i_ptr = &i;
	//memcpy(&j_ptr, &i_ptr, sizeof(void *));
	j_ptr = i_ptr;

	printf("i_ptr: ptr: %p, val: %d\n", i_ptr, *i_ptr);
	printf("j_ptr: ptr: %p, val: %d\n", j_ptr, *j_ptr);
	return 0;
}
