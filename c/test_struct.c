/*****************************************************************
 * 文件名称：test_struct.c
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-01-10 14:03
 * 描    述：
 *****************************************************************/

#include <stdio.h>
#include <string.h>
#include <stdlib.h>

int main(void) {

    typedef struct {
        char* name;
        int age;
    } mystruct;

    mystruct s1 = {(char *)malloc(100), 26};
    mystruct s2 = s1;

    strcpy(s1.name, "jiafd");
    printf("s1.name: %s\n", s1.name);
    printf("s1.age: %d\n", s1.age);
    printf("s2.name: %s\n", s2.name);
    printf("s2.age: %d\n", s2.age);

    printf("&s1: %p\n", &s1);
    printf("&s2: %p\n", &s2);
    printf("s1.name: %s\n", s1.name);
    printf("s2.name: %s\n", s2.name);
    printf("p s1.name: %p\n", s1.name);
    printf("p s2.name: %p\n", s2.name);
    printf("&s1.name: %p\n", &s1.name);
    printf("&s2.name: %p\n", &s2.name);

    free(s2.name);
    s2.name = NULL;
    printf("s1.name: %s\n", s1.name);
    printf("s2.name: %p\n", s2.name);

    return 0;
}
