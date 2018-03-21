/*****************************************************************
 * 文件名称：test_defind.c
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-03-15 13:33
 * 描    述：
 *****************************************************************/

#include <stdio.h>

#define LOG(format, ...) fprintf(stdout, format, ##__VA_ARGS__)
#define LOG1(format, args...) fprintf(stdout, format, ##args)

int
main() {
    LOG("name %s\n", "jiafd");
    LOG1("name\n");
}
