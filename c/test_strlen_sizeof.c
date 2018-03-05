/*****************************************************************
 * 文件名称：test_strlen_sizeof.c
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-01-09 13:58
 * 描    述：测试strlen和sizeof计算字符串长度哪个更快
 *****************************************************************/

#include <stdio.h>
#include <string.h>
#include <time.h>

#define STR "ab"

int main(void) {
    
    time_t start, end;
    int i, count;
    long len;

    //测试次数
    count = 100000000;

    //strlen
    len = 0;
    start = time(NULL);
    for (i = 0; i < count; i++) {
        len += strlen(STR);
        len += strlen(STR);
        len += strlen(STR);
        len += strlen(STR);
        len += strlen(STR);
    }
    end = time(NULL);
    printf("strlen: %lds, len: %ld\n", end - start, len);

    //sizeof
    len = 0;
    start = time(NULL);
    for (i = 0; i < count; i++) {
        len += sizeof(STR);
        len += sizeof(STR);
        len += sizeof(STR);
        len += sizeof(STR);
        len += sizeof(STR);
    }
    end = time(NULL);
    printf("sizeof: %lds, len: %ld\n", end - start, len);

    return 0;
}
